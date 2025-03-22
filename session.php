<?php

session_start();  // Mevcut oturumu başlat
session_unset();  // Tüm oturum değişkenlerini temizle
session_destroy(); // Oturumu tamamen yok et

// İstersen bir yere yönlendirme yapabilirsin:
header("Location: loginn.php");
exit();
?>

