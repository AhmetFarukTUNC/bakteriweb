<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$user = $_SESSION['user'];
echo htmlspecialchars($user['email']);
$user_id = $user['id'];  // Kullanıcı ID'sini al

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakteri";

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Patients tablosu yoksa oluştur
$tableCheck = $conn->query("SHOW TABLES LIKE 'patients'");
if ($tableCheck->num_rows == 0) {
    $createTableSQL = "
        CREATE TABLE patients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            name VARCHAR(100),
            dob DATE,
            patient_phone VARCHAR(15),
            patient_info TEXT,
            emergency_contact VARCHAR(100),
            emergency_phone VARCHAR(15),
            address TEXT,
            gender ENUM('male', 'female'),
            bacteria_type VARCHAR(50),
            accuracy FLOAT,
            photo_path VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
    if (!$conn->query($createTableSQL)) {
        die("Table creation failed: " . $conn->error);
    }
}

// Form verilerini sakla
$name = $_POST['name'] ?? '';

$dob = $_POST['dob'] ?? '';

$patient_phone = $_POST['patient_phone'] ?? '';

$patient_info = $_POST['patient_info'] ?? '';

$emergency_contact = $_POST['emergency_contact'] ?? '';

$emergency_phone = $_POST['emergency_phone'] ?? '';

$address = $_POST['address'] ?? '';

$gender = $_POST['gender'] ?? '';

$photoPath = $_POST['photo_path'] ?? '';

$bacteriaType = $_POST['bacteria_type'] ?? '';

$accuracy = $_POST['accuracy'] ?? '';

$sql = "SELECT * FROM patients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


// hastadetay.php sayfasında
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Güvenlik için integer'a çeviriyoruz
    // Şimdi $id değişkenini kullanabilirsin, örneğin veritabanı sorgusunda
    
} else {
    echo "ID belirtilmedi.";
}

// API'ye fotoğraf gönderip tahmin al
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photoDir = 'uploads/';
    if (!is_dir($photoDir)) {
        mkdir($photoDir, 0777, true);
    }
    $photoPath = $photoDir . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);

    $photoData = new CURLFile($photoPath);

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
    
    $insertSQL = "
    INSERT INTO patients (user_id, name, dob, patient_phone, patient_info, emergency_contact, emergency_phone, address, gender, bacteria_type, accuracy, photo_path)
    VALUES ('$user_id', '$name', '$dob', '$patient_phone', '$patient_info', '$emergency_contact', '$emergency_phone', '$address', '$gender', '$bacteriaType', '$accuracy', '$photoPath')";


if ($conn->query($insertSQL) === TRUE) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            title: 'Başarılı!',
            text: 'Veri başarıyla kaydedildi.',
            icon: 'success',
            confirmButtonText: 'Tamam',
            background: '#f4f6f9',
            color: '#333'
        }).then((result) => {
            if (result.isConfirmed) {
                return null;
            }
        });
    </script>";
}


    
    
    
     else {
    echo "Veri kaydedilirken hata oluştu: " . $conn->error;
}

$conn->close();}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakteri Analizi Sonuçları</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background: #f4f6f9;
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
        .data-item {
            background: #ffffff;
            border: 1px solid #d1d9e6;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .data-item:hover {
            transform: translateY(-5px);
        }
        .data-title {
            font-size: 1.2rem;
            color: #007bff;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .data-content {
            color: #333;
            font-size: 1.1rem;
        }
        img {
            max-width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="data-item">
        <div class="data-title">Bakteri Fotoğrafı:</div>
        <div class="data-content">
            <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="Bakteri Fotoğrafı">
        </div>
    </div>
    <div class="data-item">
        <div class="data-title">Bakteri Türü:</div>
        <div class="data-content">🧫 <?php echo htmlspecialchars($bacteriaType); ?></div>
    </div>
    <div class="data-item">
        <div class="data-title">Doğruluk:</div>
        <div class="data-content">📊 <?php echo number_format((float)$accuracy, 2, '.', ''); ?>%</div>
        <br><br>
        <a href="AiBactoLens.php" style="
  display: inline-block;
  padding: 12px 24px;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  text-decoration: none;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s, box-shadow 0.2s;
  margin-left:40px;
" 
onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 15px rgba(0, 0, 0, 0.3)';" 
onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.2)';">
  ← Hasta Yönetimi Sayfasına Dön
</a>

    </div>
</body>
<script>
    function showSuccessAlert() {
        const confirmation = confirm("Merhaba! Veri başarıyla kaydedildi. Hasta yönetimi sayfasına dönmek istiyor musunuz?");
        if (confirmation) {
            window.location.href = 'predict.php';
        }
    }
</script>

</html>