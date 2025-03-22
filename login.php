<?php


session_start();

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakteri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Formdan gelen verileri al
$email = $conn->real_escape_string($_POST['email']);
$pass = md5($_POST['password']);

// Kullanıcıyı kontrol et
$check_user = $conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
$check_user->bind_param("ss", $email, $pass);
$check_user->execute();
$result = $check_user->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user;  // Kullanıcı bilgilerini oturuma kaydet
    header("Location: index.php");  // Başarılı girişte dashboard'a yönlendir
} else {
    echo "<h3 style='color: red; text-align: center; margin: 20% 0; font-size: 2rem;'>Invalid email or password!</h3>";
}

$conn->close();
?>

