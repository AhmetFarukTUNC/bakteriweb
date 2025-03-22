<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$user = $_SESSION['user'];

// Form verilerini sakla
$name = $_POST['name'] ?? '';
$dob = $_POST['dob'] ?? '';
$patient_phone = $_POST['patient_phone'] ?? '';
$patient_info = $_POST['patient_info'] ?? '';
$emergency_contact = $_POST['emergency_contact'] ?? '';
$emergency_phone = $_POST['emergency_phone'] ?? '';
$address = $_POST['address'] ?? '';
$gender = $_POST['gender'] ?? '';

// API'ye fotoğraf gönderip tahmin al
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $photo = $_FILES['photo']['tmp_name'];
    $photoData = curl_file_create($photo);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:5000/predict');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $photoData]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $apiData = json_decode($response, true);
    $bacteriaType = $apiData['predicted_class'] ?? '';
    $accuracy = isset($apiData['max_probability']) ? rtrim($apiData['max_probability'], '%') : '';
} else {
    $bacteriaType = '';
    $accuracy = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
        }
        .navbar {
            display: flex;
            justify-content: center;
            background: #007bff;
            padding: 10px 0;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 18px;
        }
        .navbar a:hover {
            background: #0056b3;
            border-radius: 5px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100% - 50px);
        }
        .user-icon {
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .user-info {
            display: none;
            position: absolute;
            top: 70px;
            right: 20px;
            background: white;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="logo" href="#"><strong style="font-size:200%;">AiBiyo</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                   <a class="nav-link" href="index.html">Anasayfa</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="hakkımızda.html">Hakkımızda</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="AiBactoLens.html">AiBactoLens</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="İletişim.html">İletişim</a>
                </li>

            </ul>
        </div>
         <div class="login_text"><a href="login.html">Giriş Yap</a></div>
    </nav>
    <div class="user-icon" onclick="toggleUserInfo()">👤</div>
    <div class="user-info" id="userInfo">
        <p><strong>AD - SOYAD :</strong> <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
        <p><strong>UZMANLIK ALANI : </strong> <?php echo htmlspecialchars($user['area']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>TELEFON : </strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    </div>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></h1>
    </div>
    <script>
        function toggleUserInfo() {
            const userInfo = document.getElementById('userInfo');
            userInfo.style.display = userInfo.style.display === 'block' ? 'none' : 'block';
        }
    </script>
    
</body>
</html>
