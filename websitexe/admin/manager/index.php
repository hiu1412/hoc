<?php
session_start();

if (isset($_SESSION['admin_id']) && $_SESSION['admin_role'] == 2) {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: loginadmin.php");
    exit();
}
