<?php
header("Cache-Control: no-cache, must-revalidate, no-store, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: 0");
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit;
}