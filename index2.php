

<?php
session_start();
if (!isset($_SESSION['user'])) {
    
    
    
error_reporting(0);
}

$isLoggedIn = isset($_SESSION['user']);


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
<!-- basic -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- mobile metas -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<!-- site metas -->
<title>Anasayfa</title>
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
            right: 70px;
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
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
                   <a class="nav-link" href="AiBactoLens.php">AiBactoLens</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="İletişim.php">İletişim</a>
                </li>
                
            </ul>
        </div>
		<div class="user-icon" onclick="toggleUserInfo()"><i class="fa-solid fa-user"></i></div>
    <div class="user-info" id="userInfo">
        <p><strong>AD - SOYAD :</strong> <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
        <p><strong>UZMANLIK ALANI : </strong> <?php echo htmlspecialchars($user['area']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
		<a href="session.php" class="logout-button">Çıkış Yap</a>
        
    </div>
    
	<div class="login_text">
    <?php if ($isLoggedIn): ?>
        <span><?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></span>
    <?php else: ?>
        <a href="login.html">Giriş Yap</a>
    <?php endif; ?>
</div>


    </nav>
	<!-- header section start-->
	<!-- banner section start-->
	<div class="banner_section layout_padding">
		<div class="container">
			<h1 class="best_taital">Uygulama Hakkında</h1>

		    <p class="there_text">Bu uygulama, petri kaplarında bulunan bakterileri yapay zeka destekli 
				bir mobil uygulama ile otomatik olarak sınıflandırmayı amaçlamaktadır. Uygulama, kullanıcıların makro boyuttaki bakteri 
				görüntülerini analiz ederek bakterileri tespit etmesini ve doğru şekilde sınıflandırmasını sağlar. Bu mobil uygulama, 
				sağlık ve biyoteknoloji alanlarında araştırmacılar, laboratuvar teknisyenleri ve akademisyenler için hızlı ve kolay bir analiz aracı sunar. Kullanıcı dostu arayüzü sayesinde herkesin rahatlıkla kullanabileceği bir çözüm sunan bu sistem, 
				zaman ve maliyet tasarrufu sağlayarak laboratuvar çalışmalarını daha verimli hale getirmektedir.</p>

		</div>
	</div>
	<!-- banner section end-->
	<!-- marketing section start-->
	<div class="marketing_section layout_padding">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="job_section">
					    <h1 class="jobs_text">Anasayfa</h1>
					    <p class="dummy_text">Mobil uygulamanın bu sayfasında yetkili kişi bakteriyi tahmin 
							etmek için hasta ekleme butonuna basmalı. Bu butona basında karşısına hasta bilgileri
							 girilip bakteriyi tahmin ettikten sonra bu bilgileri kaydet butonuna basmalıdır.</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="image_1 padding_0" ><img  src="images/anasayfa.jpg" width="200" ></div>
				</div>
			</div>
		</div>
	</div>
	<br/>
	<!-- marketing section end-->
	<!-- Industrial section start-->
	<div class="marketing_section layout_padding">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="job_section">
					    <h1 class="jobs_text">Hasta Ekleme</h1>
					    <p class="dummy_text">Mobil uygulamanın bu sayfasında hastanın gerekli bilgileri girildikten sonra petri
							 kabındaki bakterinin resmi çekilebilir veya telefonun galerisinden yüklenebilir, 
							 sonraki aşamada ise Yapay zekaya tahmin için gönderilebilir. 
							 Bu işlemleri yaptıktan sonra muhakkak kaydetmeyi unutmayın.</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="image_1 padding_0" ><img  src="images/hastaekleme.jpg" width="200" ></div>
				</div>
			</div>
		</div>
	</div>
	<br/>
	<div class="marketing_section layout_padding">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="job_section">
					    <h1 class="jobs_text">Hasta Listesi</h1>
					    <p class="dummy_text">Mobil uygulamanın bu sayfasında daha önceden girilmiş hasta bilgilerinin listesi yer almaktadır. 
							Detay butonuna basınca girilmiş olan tüm bilgiler gözükmektedir.</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="image_1 padding_0" ><img  src="images/hastalistesi.jpg" width="200" ></div>
				</div>
			</div>
		</div>
	</div>
	<br/>
	<div class="marketing_section layout_padding">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<div class="job_section">
					    <h1 class="jobs_text">Hasta Detay</h1>
					    <p class="dummy_text">Mobil uygulamanın bu sayfasında daha önceden girilmiş olan 
							hasta bilgileri ile birlikte tahmini yapılmış bakteri fotoğrafı yer almaktadır.</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="image_1 padding_0" ><img  src="images/hastadetay.jpg" width="200" ></div>
				</div>
			</div>
		</div>
	</div>
	
	
	

	<!-- Government section end-->
	<!-- footer section start-->
<hr />
	<!-- footer section end-->
	<!-- copyright section start-->

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
      $(document).ready(function()
	  {
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