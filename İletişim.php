<?php
session_start();
if (!isset($_SESSION['user'])) {
    error_reporting(0);
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$isLoggedIn = isset($_SESSION['user']);

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
<title>İletişim</title>
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
				<a class="nav-link" href="<?php echo isset($_SESSION['user']) ? 'AiBactoLens.php' : 'loginn.php'; ?>">AiBactoLens</a>
                </li>
                <li class="nav-item">
<a class="nav-link" href="İletişim.php" >İletişim</a>
                </li>

        </ul>
    </div >
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
  <div class="row justify-content-center">
      <div class="col-md-6">
          <div class="card shadow-lg p-4 text-center">
              <h3 class="mb-3">İletişim Bilgilerimiz</h3>
              <div class="mb-3">
                  <i class="fas fa-phone fa-2x text-primary"></i>
                  <p class="mt-2 mb-0">Telefon Numarası</p>
                  <p class="fw-bold">+90 555 123 45 67</p>
              </div>
              <div class="mb-3">
                  <i class="fas fa-envelope fa-2x text-danger"></i>
                  <p class="mt-2 mb-0">E-Posta</p>
                  <p class="fw-bold">info@ornekmail.com</p>
              </div>
              <hr/>
              <!-- E-posta Gönderme Kutusu -->
              <h4 class="mt-4">Bize Ulaşın</h4>
              <form>
                  <div class="mb-3">
                      <label for="name" class="form-label">Adınız</label>
                      <input type="text" class="form-control" id="name" placeholder="Adınızı girin" required>
                  </div>
                  <div class="mb-3">
                      <label for="email" class="form-label">E-posta Adresiniz</label>
                      <input type="email" class="form-control" id="email" placeholder="E-posta adresinizi girin" required>
                  </div>
                  <div class="mb-3">
                      <label for="message" class="form-label">Mesajınız</label>
                      <textarea class="form-control" id="message" rows="4" placeholder="Mesajınızı buraya yazın" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Gönder</button>
              </form>
          </div>
      </div>
  </div>
</div>

  <!-- footer section start-->
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