<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakteri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kullanıcının hastalarını çek
$sql = "SELECT * FROM patients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Hasta Bilgileri</title>
    <style>
        body {
            background: #f8f9fa;
        }
        h1{
            margin-top:-30px;
            margin-bottom:420px
        }
        .card-container {
            position: relative;
            perspective: 1000px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease-in-out, box-shadow 0.2s;
        }
        .card:hover {
            transform: scale(1.05) translateY(-5px);
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .card:active {
            transform: scale(1.2);
        }
        .card-title {
            color: #007bff;
            font-weight: bold;
        }
        .card p {
            margin: 0.5rem 0;
        }
        .container h1 {
            color: #343a40;
            margin-bottom: 2rem;
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
<div class="container mt-5">
    <h1 class="text-center" style="margin-top:50px">Hastalarım</h1>
    <br>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-container">
                    <div class="card h-200 w-100">
                        <img src="<?php echo htmlspecialchars($row['photo_path']); ?>" class="card-img-top" alt="Hasta Fotoğrafı">
                        <div class="card-body">
                            <h5 class="card-title">Ad: <?php echo htmlspecialchars($row['name']); ?></h5>
                            <p><strong>Doğum Tarihi:</strong> <?php echo date('d-m-Y', strtotime($row['dob'])); ?></p>
                            <p><strong>Telefon:</strong> <?php echo htmlspecialchars($row['patient_phone']); ?></p>
                            <p><strong>Bakteri Türü:</strong> <?php echo htmlspecialchars($row['bacteria_type']); ?></p>
                            <p><strong>Doğruluk:</strong> <?php echo htmlspecialchars($row['accuracy']); ?>%</p>
                            <p><strong>Daha Önce Konulmuş Tanılar:</strong> <?php echo nl2br(htmlspecialchars($row['patient_info'])); ?></p>
                            <p><strong>Cinsiyet:</strong> <?php echo ucfirst($row['gender']); ?></p>
                            <p><strong>Hasta Yakını:</strong> <?php echo htmlspecialchars($row['emergency_contact']); ?></p>
                            <p style="width:800px"><strong>Hasta Yakını Telefon Numarası:</strong> <?php echo htmlspecialchars($row['emergency_phone']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script>
        function toggleUserInfo() {
            const userInfo = document.getElementById('userInfo');
            userInfo.style.display = userInfo.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</html>

<?php
$conn->close();
?>
