<?php
session_start();

// Require appointment login before accessing this page
if (!isset($_SESSION['appointment_auth'])) {
    header("Location: appointment_login.php");
    exit();
}

require_once __DIR__ . '/config/dbcon.php';
$userid = $_SESSION['appointment_user']['id'];
$sql = "SELECT u.fname, u.lname, u.username, u.deptid, d.deptname FROM users u LEFT JOIN departments d ON u.deptid = d.deptid WHERE u.userid = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/modals.css">

    <script src="js/mainscript.js"></script>
    <style>
        .profile-container {
            max-width: 500px;
            margin: 150px auto 60px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .profile-container h2 {
            margin-bottom: 1rem;
            color: #2a3b4c;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .profile-container .input {
            margin-bottom: 1rem;
        }
        .profile-container label {
            font-weight: 500;
        }
        .profile-container .btn {
            min-width: 120px;
        }
        .profile-container .info-block {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include('includes/messages/message.php'); ?>

    <div class="profile-container">
        <h2>User Profile</h2>
        <div class="info-block">
            <div><strong>Name:</strong> <?= htmlspecialchars($user['fname'] . ' ' . $user['lname']) ?></div>
            <div><strong>Department:</strong> <?= htmlspecialchars($user['deptname'] ?? 'N/A') ?></div>
        </div>
        <hr>
        <h2 style="margin:1rem 0 0.5rem 0;">User Details</h2>
        <form id="profileSettingsForm" method="post" action="api/post/updateUserProfile.php">
            <input type="hidden" name="userid" value="<?= $userid ?>">
            <div class="input">
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="width-100">
            </div>
            <div class="input">
                <label>New Password:</label>
                <input type="password" name="password" placeholder="Leave blank to keep current" class="width-100">
            </div>
            <div class="input">
                <label>First Name:</label>
                <input type="text" name="fname" value="<?= htmlspecialchars($user['fname']) ?>" required class="width-100">
            </div>
            <div class="input">
                <label>Last Name:</label>
                <input type="text" name="lname" value="<?= htmlspecialchars($user['lname']) ?>" required class="width-100">
            </div>
            <div style="margin-top:1rem;text-align:right;">
                <button type="button" id="saveProfileBtn" class="btn btn-green">Save Changes</button>
            </div>
        </form>
    </div>
    <!-- Save Confirmation Modal -->
    <div id="saveConfirmModal" class="confirmModal" style="display:none;">
        <div class="modal-cancelContent">
            <span class="close" id="closeSaveModal">&times;</span>
            <p>Are you sure you want to save changes to your profile?</p>
            <div>
                <button id="confirmSave" class="btn btn-green">Yes</button>
                <button id="cancelSave" class="btn btn-red">No</button>
            </div>
        </div>
    </div>
    
    <script src="js/mainScript.js"></script>
    <script>
    // Save confirmation logic
    const saveBtn = document.getElementById('saveProfileBtn');
    const saveModal = document.getElementById('saveConfirmModal');
    const closeSaveModal = document.getElementById('closeSaveModal');
    const confirmSave = document.getElementById('confirmSave');
    const cancelSave = document.getElementById('cancelSave');
    const profileForm = document.getElementById('profileSettingsForm');

    saveBtn.addEventListener('click', function(e) {
        saveModal.style.display = 'block';
    });
    closeSaveModal.addEventListener('click', function() {
        saveModal.style.display = 'none';
    });
    cancelSave.addEventListener('click', function() {
        saveModal.style.display = 'none';
    });
    confirmSave.addEventListener('click', function() {
        saveModal.style.display = 'none';
        // AJAX submit
        const formData = new FormData(profileForm);
        fetch('api/post/updateUserProfile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // alert('Profile updated successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while updating your profile.');
        });
    });
    // Optional: close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === saveModal) {
            saveModal.style.display = 'none';
        }
    });
    </script>
</body>
</html> 