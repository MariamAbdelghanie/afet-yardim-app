<?php
// Türkçe Açıklama: Oturum başlatma ve rol kontrol fonksiyonları
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Kullanıcı rolünü kontrol eden fonksiyon
function requireRole($roles = []){
    if(!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)){
        header("Location: login.php");
        exit();
    }
}




