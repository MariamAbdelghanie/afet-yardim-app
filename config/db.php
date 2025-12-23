<?php
// Türkçe Açıklama: PDO veritabanı bağlantısı
$host="127.0.0.1"; $user="root"; $pass=""; $dbname="afet_yardim_db";
$pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$user,$pass,[
  PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
]);





