<?php

session_start();

$lang = $_GET['lang'] ?? '';

if ($lang == 'id' || $lang == 'en'){
    $_SESSION['lang'] = $lang;
} 

$tujuan = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $tujuan);
exit;