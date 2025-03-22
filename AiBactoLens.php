<?php
session_start();
if (!isset($_SESSION['user'])) {
error_reporting(0);}

$user = $_SESSION['user'];
$user_id = $user['id'];
$isLoggedIn = isset($_SESSION['user']);

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
    echo $bacteriaType;
    $accuracy = isset($apiData['max_probability']) ? rtrim($apiData['max_probability'], '%') : '';
} else {
    $bacteriaType = '';
    $accuracy = '';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- basic -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- mobile metas -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<!-- site metas -->
<title>AiBactoLens</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">	
<!-- bootstrap css -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<!-- style css -->
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- Responsive-->
<link rel="stylesheet" href="css/responsive.css">
<!-- fevicon -->
<link rel="icon" href="images/fevicon.png" type="image/gif" />
<!-- Scrollbar Custom CSS -->
<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
<!-- Tweaks for older IEs-->
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<!-- owl stylesheets --> 
<link rel="stylesheet" href="css/owl.carousel.min.css">
<link rel="stylesoeet" href="css/owl.theme.default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<script>
  function toggleView() {
      let isChecked = document.getElementById("toggleView").checked;
      document.getElementById("formContainer").style.display = isChecked ? "none" : "block";
      document.getElementById("tableContainer").style.display = isChecked ? "block" : "none";
  }
</script>

</head>
<style>

.logout-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            background:rgb(255, 0, 0);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            text-align: center;
        }

    .user-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            
            border-radius: 50%;
            
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .user-info {
            display: none;
            position: absolute;
            top: 70px;
            right: 5px;
            background: white;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
		.hidden{
			display:"none"
		}
</style>
<body>
	<!-- header section start-->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="logo" href="#"><strong style="font-size:200%;">AiBiyo</strong></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
               <a class="nav-link" href="index.php">Anasayfa</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="hakkımızda.php">Hakkımızda</a>
            </li>
            <li class="nav-item">
				<a class="nav-link" href="<?php echo isset($_SESSION['user']) ? 'AiBactoLens.php' : 'loginn.php'; ?>">AiBactoLens</a>
                </li>
                <li class="nav-item">
<a class="nav-link" href="İletişim.php" >İletişim</a>
                </li>

        </ul>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
		<div class="user-icon" onclick="toggleUserInfo()"><i class="fa-solid fa-user"></i></div>
    <div class="user-info" id="userInfo">
        <p><strong>AD - SOYAD :</strong> <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
        <p><strong>UZMANLIK ALANI : </strong> <?php echo htmlspecialchars($user['area']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
		
        <a href="session.php" class="logout-button">Çıkış Yap</a>
    <?php endif; ?>
        
    </div>
    
	<div class="login_text" style="margin-left: 338px;">
    <?php if ($isLoggedIn): ?>
        <span><?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></span>
    <?php else: ?>
        <a href="loginn.php">Giriş Yap</a>
    <?php endif; ?>
</nav>

<div class="container my-4">
  <div class="text-center mb-3">
      <input type="checkbox" id="toggleView" class="form-check-input" onclick="toggleView()">
      <label for="toggleView" class="form-check-label">Hasta Listesini Göster</label>
  </div>

  <!-- Hasta Kayıt Formu -->
  <div id="formContainer">
      <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
              <div class="card shadow-lg p-4">
                  <h3 class="text-center mb-4">Hasta Kayıt Formu</h3>
                  <form action="" method="POST" enctype="multipart/form-data">
                      <!-- Adı Soyadı -->
                      <div class="mb-3">
                          <label class="form-label">Adı Soyadı</label>
                          <input type="text" class="form-control" required name="name">
                      </div>

                      <!-- Doğum Tarihi -->
                      <div class="mb-3">
                          <label class="form-label">Doğum Tarihi</label>
                          <input type="date" class="form-control" required name="dob">
                      </div>

                      <!-- Hasta Telefon Numarası -->
                      <div class="mb-3">
                          <label class="form-label">Hasta Telefon Numarası</label>
                          <input type="tel" class="form-control" placeholder="05XX XXX XX XX" required name="patient_phone">
                      </div>

                      <!-- Hastalık Bilgisi -->
                      <div class="mb-3">
                          <label class="form-label">Hastalık Bilgisi</label>
                          <textarea class="form-control" rows="3" placeholder="Hastalık hakkında bilgi giriniz..." required name="patient_info"></textarea>
                      </div>

                      <!-- Acil Durum Kişisi -->
                      <div class="mb-3">
                          <label class="form-label">Acil Durum Kişisi</label>
                          <input type="text" class="form-control" required name="emergency_contact">
                      </div>

                      <!-- Acil Durum Telefon Numarası -->
                      <div class="mb-3">
                          <label class="form-label">Acil Durum Telefon Numarası</label>
                          <input type="tel" class="form-control" placeholder="05XX XXX XX XX" required name="emergency_phone">
                      </div>

                      <!-- Adres -->
                      <div class="mb-3">
                          <label class="form-label">Adres</label>
                          <textarea class="form-control" rows="2" placeholder="Adres bilgisi giriniz..." required name="address"></textarea>
                      </div>

                      <!-- Cinsiyet Bilgisi -->
                      <div class="mb-3">
                          <label class="form-label d-block">Cinsiyet</label>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="gender" id="erkek" value="Erkek" required>
                              <label class="form-check-label" for="erkek">Erkek</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="gender" id="kadin" value="Kadın" required>
                              <label class="form-check-label" for="kadin">Kadın</label>
                          </div>
                      </div>

                      <!-- Resim Yükleme -->
                      <div class="mb-3">
                          <label class="form-label">Bakteri Fotoğrafı Yükleyin</label>
                          <input type="file" name="photo" accept="image/*" required>
                      </div>

                      <!-- Gönder Butonu -->
                      <button type="submit" class="btn btn-primary w-100 my-2" formaction = "predict.php">
                          <img src="images/ai.png" width="24"> Tahmin et
                      </button>
                      <button type="submit" class="btn btn-success w-100 my-2">Kaydı Tamamla</button>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <!-- Hasta Listesi Tablosu -->
  <div id="tableContainer" style="display: none;">
      <div class="row justify-content-center">
          <div class="col-md-10">
              <div class="card shadow-lg p-4">
                  <h3 class="text-center mb-4">Hasta Listesi</h3>
                  <table class="table table-bordered table-striped">
                      <thead class="table-primary">
                          <tr>
                              <th>ID</th>
                              <th>Adı Soyadı</th>
                          </tr>
                      </thead>
                      
                      <tbody>
                      <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><a href="hastadetay.php?id=<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></a></td>
    </tr>
<?php endwhile; ?>

                          

                      </tbody>
                  </table>
                  <button class="btn btn-primary w-100 my-2" onclick="document.getElementById('toggleView').click();">
                      Hasta Kayıt Formuna Dön
                  </button>
              </div>
          </div>
      </div>
  </div>
</div>

    <div class="copyright_section" style="background-color: #f2f2f2; margin-top: 20px;">
			<div class="container">
				<div class="row">
					<div class="col-md-2 my-3">
						<div class="lead text-center">
							<i class="fa-solid fa-laptop"></i>
							<br/>
							<bold>Hitit</bold>Yazılım
						</div>
					</div>
					<div class="col-md-8 my-3">
						<div class="lead text-center">
							<bold>AiBactoLens</bold>
							<br/>
							<a href="#" class="text-dark me-3"><i class="fa-brands fa-facebook"></i></a>
							<a href="#" class="text-dark me-3"><i class="fa-brands fa-twitter"></i></a>
							<a href="#" class="text-dark"><i class="fa-brands fa-linkedin"></i></a>
						</div>
					</div>
					<div class="col-md-2 my-3">
						<a href="#" class="btn btn-primary"><i class="fa-solid fa-arrow-down"></i> Uygulamayı İndirin</a>
					</div>
				</div>
			</div>
		</div>
    <!-- copyright section end-->


    <!-- Javascript files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <!-- javascript --> 
      <script src="js/owl.carousel.js"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
      <script>
      $(document).ready(function(){
      $(".fancybox").fancybox({
         openEffect: "none",
         closeEffect: "none"
         });
         })

         function toggleUserInfo() {
            const userInfo = document.getElementById('userInfo');
            userInfo.style.display = userInfo.style.display === 'block' ? 'none' : 'block';
        }
         </script>


     
</body>
</html>