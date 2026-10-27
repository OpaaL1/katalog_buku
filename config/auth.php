<?php
require __DIR__ . '/bahasa.php';

if (!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}