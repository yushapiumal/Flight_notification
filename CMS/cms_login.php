<?php
    session_start();
    require_once('config/config.inc.php');
    require_once('CMS/cms.class.php');
    $cms = new cms();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CMS - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/loginPage/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="assets/loginPage/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/loginPage/css/main.css">
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="post" action="/cms_ctrl" id="loginForm">
					<span class="login100-form-title p-b-26">
						Welcome
					</span>
					<span class="login100-form-title p-b-48">
						<img src="/assets/images/favicon.png" alt="favicon"/>
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is: a@b.c">
						<input class="input100" type="text" id="uname">
						<span class="focus-input100" data-placeholder="User Name"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass" id="pass">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" id="loginBtn" type="submit">
								Login
							</button>
						</div>
					</div>

					 
				</form>
			</div>
		</div>
	</div>
	<div id="dropDownSelect1"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="assets/loginPage/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="assets/loginPage/vendor/animsition/js/animsition.min.js"></script>
	<script src="assets/loginPage/vendor/bootstrap/js/popper.js"></script>
	<script src="assets/loginPage/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/loginPage/vendor/select2/select2.min.js"></script>
	<script src="assets/loginPage/vendor/daterangepicker/moment.min.js"></script>
	<script src="assets/loginPage/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="assets/loginPage/vendor/countdowntime/countdowntime.js"></script>
	<script src="assets/loginPage/js/main.js" defer></script>

    <script>
        $("#loginForm").submit(function(event) {
            event.preventDefault();
            var $form = $(this);
            var formData = new FormData();
            formData.append('uname', $('#uname').val());
            formData.append('pass', $('#pass').val());
            formData.append('loginBtn', $('#loginBtn').val());
            $.ajax({
                type: "post",
                url: $form.attr('action'),
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data) {
                if (data.status) {
                    window.location.href = "/cms_view";
                } else {

                    alert("User Name or Password Incorrect");
                }
            });
        });
    </script>
</body>
</html>