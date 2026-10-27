<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

$bahasa_valid = ['id', 'en'];

if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $bahasa_valid, true)){
    $_SESSION['lang'] = 'id';
}

$t = require __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';
