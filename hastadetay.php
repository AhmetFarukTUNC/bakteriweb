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


// hastadetay.php sayfasında
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Güvenlik için integer'a çeviriyoruz
    // Şimdi $id değişkenini kullanabilirsin, örneğin veritabanı sorgusunda
    
} else {
    echo "ID belirtilmedi.";
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
         <div class="login_text"><a href="login.html">Giriş Yap</a></div>
    </nav>

	<div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-4">Hasta Bilgileri</h3>
                    <table class="table table-bordered">
                        <tbody>
                        <?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    while ($row = $result->fetch_assoc()):
        if ($row['id'] == $id): ?>
            <tr>
                <th>Adı Soyadı</th>
                <td><a href="hastadetay.php?id=<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></a></td>
            </tr>
            <tr>
                <th>Doğum Tarihi</th>
                <td><?php echo htmlspecialchars($row['dob']); ?></td>
            </tr>
            <tr>
                <th>Telefon Numarası</th>
                <td><?php echo htmlspecialchars($row['patient_phone']); ?></td>
            </tr>
            <tr>
                <th>Hastalık Bilgisi</th>
                <td><?php echo htmlspecialchars($row['patient_info']); ?></td>
            </tr>
            <tr>
                <th>Acil Durum Kişisi</th>
                <td><?php echo htmlspecialchars($row['emergency_contact']); ?></td>
            </tr>
            <tr>
                <th>Acil Durum Telefon</th>
                <td><?php echo htmlspecialchars($row['emergency_phone']); ?></td>
            </tr>
            <tr>
                <th>Adres</th>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
            </tr>
            <tr>
                <th>Cinsiyet</th>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
            </tr>
            <tr>
                <th>Tahmin edilen bakteri</th>
                <td><?php echo htmlspecialchars($row['bacteria_type']); ?></td>
            </tr>
            <tr>
                <th>Tahmin sonucu</th>
                <td><?php echo htmlspecialchars($row['accuracy']); ?></td>
            </tr>
            <tr>
                <th>Bakteri Fotoğrafı</th>
                <td><img src="<?php echo htmlspecialchars($row['photo_path']); ?>" alt="Bakteri Fotoğrafı" width="100"></td>
            </tr>
<?php
        endif;
    endwhile;
} else {
    echo "ID belirtilmedi.";
}
?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
	

	<!-- Government section end-->
	<!-- footer section start-->

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
		
         </script>


     
</body>

</html>