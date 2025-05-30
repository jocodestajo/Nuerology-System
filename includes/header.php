<?php
// Add this at the top of the file if not already present
if(!isset($_SESSION['auth_user'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
?>
    <div class="header-1">
        <div class="logo">
            <img src="img/MMWGH_logo.png" alt="mmwgh logo">
            <h2>Neurology Unit</h2>
        </div>
        <div class="navbar">
            <!-- hide by default, display in tablet mode -->
            <i class="menu-icon" id="menu-toggle">&#9776;</i>
            
            <div class="nav-list">
                <!-- <a href="#neurology" class="btn-navbar">Neurology</a> -->
                <!-- <a href="#EEG" class="btn-navbar">EEG</a> -->
                <!-- <a href="#reports" class="btn-navbar">Reports</a> -->
                <!-- <span class="btn-navbar">Welcome, <?= $_SESSION['auth_user']['fname'] ?> </span> -->
                <a href="index.php" class="btn-navbar">Home</a>
                <a href="profile.php" class="btn-navbar">Profile</a>
                <a href="#" id="logout-link" class="btn-navbar">Logout</a>
            </div>
        </div>
    </div>
<?php
?>

<!-- Logout Confirmation Modal -->
<div id="logoutConfirmModal" class="confirmModal" style="display:none;">
    <div class="modal-cancelContent">
        <span class="close" id="closeLogoutModal">&times;</span>
        <p>Are you sure you want to logout?</p>
        <div>
            <button id="confirmLogout" class="btn btn-red">Yes</button>
            <button id="cancelLogout" class="btn btn-blue">No</button>
        </div>
    </div>
</div>

<script>
// Logout confirmation logic
const logoutLink = document.getElementById('logout-link');
const logoutModal = document.getElementById('logoutConfirmModal');
const closeLogoutModal = document.getElementById('closeLogoutModal');
const confirmLogout = document.getElementById('confirmLogout');
const cancelLogout = document.getElementById('cancelLogout');

logoutLink.addEventListener('click', function(e) {
    e.preventDefault();
    logoutModal.style.display = 'block';
});

closeLogoutModal.addEventListener('click', function() {
    logoutModal.style.display = 'none';
});
cancelLogout.addEventListener('click', function() {
    logoutModal.style.display = 'none';
});
confirmLogout.addEventListener('click', function() {
    window.location.href = 'index.php?logout=1';
});
// Optional: close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target === logoutModal) {
        logoutModal.style.display = 'none';
    }
});
</script>