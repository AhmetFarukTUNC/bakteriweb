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
            padding: 20px;
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
        form {
            max-width: 500px;
            margin: 20px auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form div {
            margin-bottom: 15px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .user-icon {
            position: absolute;
            top: 20px;
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding:0;
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
<div class="navbar">
        <a href="dashboard.php">Anasayfa</a>
        <a href="addpatient.php">Hasta ekle</a>
        <a href="pm.php">Hasta yönetimi</a>
        
    </div>
    <div class="user-icon" onclick="toggleUserInfo()">👤</div>
    <div class="user-info" id="userInfo">
        <p><strong>AD - SOYAD :</strong> <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
        <p><strong>UZMANLIK ALANI : </strong> <?php echo htmlspecialchars($user['area']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>TELEFON : </strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    </div>
    <form action = ""  method="POST" enctype="multipart/form-data">
        <div><label>Ad Soyad:</label><input type="text" name="name" required></div>
        <div><label>Doğum Tarihi:</label><input type="date" name="dob" required></div>
        <div><label>Hasta Telefonu:</label><input type="tel" name="patient_phone" required></div>
        <div><label>Hasta Bilgisi:</label><textarea name="patient_info" required></textarea></div>
        <div><label>Acil Durum Kişisi:</label><input type="text" name="emergency_contact" required></div>
        <div><label>Acil Durum Telefonu:</label><input type="tel" name="emergency_phone" required></div>
        <div><label>Adres:</label><textarea name="address" required></textarea></div>
        <div>
            <label>Cinsiyet:</label><br>
            <input type="radio" name="gender" value="male" required> Erkek
            <input type="radio" name="gender" value="female" required> Kadın
        </div>
        <div><label>Bakteri Türü:</label><input type="text" name="bacteria_type" value="<?php echo htmlspecialchars($bacteriaType); ?>" readonly></div>
        <div><label>Tahmin Doğruluk Değeri:</label><input type="number" name="accuracy" step="0.01" value="<?php echo htmlspecialchars($accuracy); ?>" readonly></div>
        <div><label>Fotoğraf Yükle:</label><input type="file" name="photo" accept="image/*" required> </div>
        <div>
            <br>
            <button type="submit" formaction = "predict.php" style ="margin-left:190px">Tahmin Yap</button>
            
        </div>
    </form>

</body>
<script>
        function toggleUserInfo() {
            const userInfo = document.getElementById('userInfo');
            userInfo.style.display = userInfo.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</html>
