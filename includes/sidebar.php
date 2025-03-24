<?php
require '../config/config.php'; // Make sure this file initializes $pdo properly


// Fetch pending request count using PDO
$query = "SELECT COUNT(*) AS pending_count FROM requests WHERE status = 'Pending'";
$stmt = $pdo->query($query);
$pendingCount = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../includes/sidebar.css">
</head>
<body>
    <div class="sidebar_container">
        <div class="logo">
             <img src="../assets/logo_white.png" alt="">
             <hr>
             <div class="sidenav">
                <ul>
                    <a href="../admin/dashboard.php"> <li><i class='bx bx-home-alt' ></i>Home</li></a>
                    <a href="../admin/inventory.php"><li><i class='bx bx-message-alt-edit'></i>Manage Inventory</li></a>
                    <a href="../admin/approve_requests.php"><li><i class='bx bx-message-square-check'></i>Requests 
             <?php if ($pendingCount > 0): ?>
            <span class="notif-badge"><?= $pendingCount; ?></span>
        <?php endif; ?>
    </li>
</a>

                    <a href="../admin/staff_accounts.php"><li><i class='bx bxs-user-account'></i>Staff Accounts</li></a>
                </a>
                <a href="../admin/reports.php">
                    <li><i class='bx bxs-copy-alt'></i>Report Logs (PDF)</li>
                </a>
                </ul>
             </div>
        </div>

        <div class="account">
    <img src="../assets/profile.png" alt="Profile Image">
    <span class="username">
        <?php 
            echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {;  
            }
        ?>
       <i class='bx bxs-up-arrow bx-tada' style='color:#ffffff' id="dropdownToggle"></i>
        <div class="dropdown-menu" id="dropdownMenu">
            <ul>
                <li><a href="../profile.php">User Profile</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </span>
</div>
    </div>

    <script>
        // Toggle the dropdown menu
document.getElementById('dropdownToggle').addEventListener('click', function() {
    var menu = document.getElementById('dropdownMenu');
    var icon = document.getElementById('dropdownToggle');

    // Toggle visibility of the dropdown menu
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    
    // Toggle the icon rotation
    icon.classList.toggle('rotate');
});

// Close the dropdown if clicked outside
window.addEventListener('click', function(event) {
    var menu = document.getElementById('dropdownMenu');
    var icon = document.getElementById('dropdownToggle');
    
    // Close the dropdown if the click is outside the dropdown or icon
    if (!menu.contains(event.target) && event.target !== icon) {
        menu.style.display = 'none';
        icon.classList.remove('rotate');
    }
});
    </script>

    
</body>
</html>