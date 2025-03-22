<?php
// ====== Veritabanı Bağlantısı ======
$servername = "localhost";
$username = "root";  // PHPMyAdmin kullanıcı adı
$password = "";       // PHPMyAdmin şifren (genelde boş bırakılır)
$dbname = "bakteri";

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("<h3 style='color: red; text-align: center; margin: 20% 0; font-size: 2rem;'>Connection failed: " . $conn->connect_error . "</h3>");
}

// ====== Formdan Gelen Verileri Al ======
$name = $conn->real_escape_string($_POST['registerFirstName']);
$surname = $conn->real_escape_string($_POST['registerLastName']);
$area = $conn->real_escape_string($_POST['area']);
$email = $conn->real_escape_string($_POST['email']);
$pass = md5($_POST['password']);  // Şifreyi hashliyoruz

// ====== Aynı E-posta Var Mı Kontrol Et ======
$check_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check_user->bind_param("s", $email);
$check_user->execute();
$result = $check_user->get_result();

if ($result->num_rows > 0) {
    echo "<h3 style='color: red; text-align: center; margin: 20% 0; font-size: 2rem;'>E-posta zaten kayıtlı. Lütfen başka bir e-posta deneyin.</h3>";
} else {
    // ====== Yeni Kullanıcıyı Ekle ======
    $sql = $conn->prepare("INSERT INTO users (name, surname, area, email, password) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("sssss", $name, $surname, $area, $email, $pass);

    if ($sql->execute()) {
        echo "<h3 style='color: green; text-align: center; margin: 20% 0; font-size: 2rem;'>Yeni kullanıcı başarıyla oluşturuldu!</h3>";
        header("Refresh: 2; url=loginn.php");
        exit();  // Header sonrası çıkış yapmak iyi bir uygulamadır
    } else {
        echo "<h3 style='color: red; text-align: center; margin: 20% 0; font-size: 2rem;'>Hata: " . $conn->error . "</h3>";
    }
}

// Bağlantıyı kapat
$conn->close();
?>

