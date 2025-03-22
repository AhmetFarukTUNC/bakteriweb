
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
<title>Giriş Yap</title>
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
				<a class="nav-link" href="<?php echo isset($_SESSION['user']) ? 'AiBactoLens.php' : 'loginn.php'; ?>">AiBactoLens</a>
                </li>
                <li class="nav-item">
<a class="nav-link" href="İletişim.php" >İletişim</a>
                </li>

        </ul>
    </div>
     <div class="login_text"><a href="start.php">Giriş Yap</a></div>
</nav>
  <!-- header section end-->
  <!-- login section start-->
  <!-- Slider Container -->
<div class="container my-5">
  <div class="row justify-content-center">
      <div class="col-md-6">
          <div id="formSlider" class="card shadow-lg p-4">
              <!-- Giriş Yap Formu -->
              <div id="loginForm">
                  <h3 class="text-center mb-4">Giriş Yap</h3>
                  <form action="login.php" method="post">
                      <!-- E-posta -->
                      <div class="mb-3">
                          <label for="loginEmail" class="form-label">E-posta</label>
                          <input type="email" class="form-control" id="loginEmail" placeholder="E-posta adresinizi girin" required name="email">
                      </div>
                      <!-- Şifre -->
                      <div class="mb-3">
                          <label for="loginPassword" class="form-label">Şifre</label>
                          <input type="password" class="form-control" id="loginPassword" placeholder="Şifrenizi girin" required name="password">
                      </div>
                      <!-- Giriş Butonu -->
                      <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                  </form>
                  <!-- Kayıt Ol Butonu -->
                  <div class="mt-3 text-center">
                      <button type="button" class="btn btn-link" onclick="toggleForm()">Kayıt Ol</button>
                  </div>
              </div>

              <!-- Kayıt Ol Formu -->
              <div id="registerForm" style="display: none;">
                  <h3 class="text-center mb-4">Kayıt Ol</h3>
                  <form action="signup.php" method="post">
                      <!-- Ad -->
                      <div class="mb-3">
                          <label for="registerFirstName" class="form-label">Ad</label>
                          <input type="text" class="form-control" id="registerFirstName" placeholder="Adınızı girin" required name = "registerFirstName">
                      </div>
                      <!-- Soyad -->
                      <div class="mb-3">
                          <label for="registerLastName" class="form-label">Soyad</label>
                          <input type="text" class="form-control" id="registerLastName" placeholder="Soyadınızı girin" required name="registerLastName">
                      </div>
                      <!-- E-posta -->
                      <div class="mb-3">
                          <label for="registerEmail" class="form-label">E-posta</label>
                          <input type="email" class="form-control" id="registerEmail" placeholder="E-posta adresinizi girin" required name="email">
                      </div>
                      <!-- Uzmanlık Alanı -->
                      <div class="mb-3">
                          <label for="specialization" class="form-label">Uzmanlık Alanı</label>
                          <input type="text" class="form-control" id="specialization" placeholder="Uzmanlık alanınızı girin" required name="area">
                      </div>
                      <!-- Şifre -->
                      <div class="mb-3">
                          <label for="registerPassword" class="form-label">Şifre</label>
                          <input type="password" class="form-control" id="registerPassword" placeholder="Şifrenizi girin" required name="password">
                      </div>
                      <!-- Kayıt Ol Butonu -->
                      <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
                  </form>
                  <!-- Giriş Yap Butonu -->
                  <div class="mt-3 text-center">
                      <button type="button" class="btn btn-link" onclick="toggleForm()">Zaten Hesabım Var</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


  <!-- login section end-->

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
      $(document).ready(function()
      {
      $(".fancybox").fancybox({
         openEffect: "none",
         closeEffect: "none"
         });
        })
         </script>
         <!-- JavaScript to Toggle Forms -->
<script>
  function toggleForm() {
      const loginForm = document.getElementById('loginForm');
      const registerForm = document.getElementById('registerForm');
      if (loginForm.style.display === 'none') {
          loginForm.style.display = 'block';
          registerForm.style.display = 'none';
      } else {
          loginForm.style.display = 'none';
          registerForm.style.display = 'block';
      }
  }
</script>


     
</body>
</html>