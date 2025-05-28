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
                <span class="btn-navbar">Welcome, <?= $_SESSION['auth_user']['fname'] ?> </span>
                <a href="#profile" class="btn-navbar">Profile</a>
                <a href="index.php?logout=1" class="btn-navbar">Logout</a>
            </div>
        </div>
    </div>
<?php
?>