<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Dine In - Bon Ape'tit</title>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/grid.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/material.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/index.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/responsive.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/filterstyle.css">
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.min.css">
	</head>
	<body>
		<!-- Nav for anonymous users -->
		<!-- <div class="bubblingG">
			<span id="bubblingG_1">
			</span>
			<span id="bubblingG_2">
			</span>
			<span id="bubblingG_3">
			</span>
		</div> -->
		<div class="loader">
			 <center>
					 <img class="loading-image" src="<?php echo Yii::app()->request->baseUrl;?>/img/loader.gif" alt="loading..">
			 </center>
		</div>
		<nav class="row top-fixed nav-down">
			<div class="container">
				<a href="#" class="colGLG-4 colGMD-12 colGSM-12"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logonew.png"></a>
				<ul class="colGLG-8 colGMD-12 colGSM-12" id="main-nav">
					<li><a href="#">Restaurants</a></li>
					<li><a href="#">Cuisines</a></li>

					<?php if(Yii::app()->user->isGuest): ?>
					<li>
						<div class="modal">
							<input type="checkbox" id="modal-trigger">
							<label for="modal-trigger">Login</label>
							<div class="modal-overlay">
								<div class="modal-wrap row">
									<div class="colGLG-4 colGSM-12 modal-wrap-banner">
										<i class="fa fa-credit-card-alt fa-3x"></i>
										<h4>ATMs and Debit cards<br> are accepted</h4>
										<h5>Or pay via</h5>
										<h4>Cash On Delivery</h4>
										<h5>No minimum order required !</h5>
									</div>
									<div class="colGLG-8 colGSM-12 modal-wrap-form">
										<ul class="tabs">
											<li class="colGLG-6">
												<input type="radio" name="tabs" id="tab1" />
												<label for="tab1">Login</label>
												<form id="tab-content1" class="tab-content colGLG-12">
													<input type="text" name="username" placeholder="Your email or your mobile number" class="colGLG-12">
													<input type="password" name="password" placeholder="Your password" class="colGLG-12">
													 <p class="forgot-password"><a href="<?php echo Yii::app()->createUrl('site/ForgotPassword'); ?>">Forgot Password ?</a></p>
													<button id="login" name="login" class="colGLG-6">Login</button>
													<label for="modal-trigger" class="colGLG-6 closeLabel"><i class="fa fa-times-circle fa-2x"></i></label>
													<p id="form-error1"></p>
												</form>
											</li>

											<li class="colGLG-6">
												<input type="radio" name="tabs" id="tab2" />
												<label for="tab2">Signup</label>
												<form method="post" action ="#" id="tab-content2" class="tab-content colGLG-12 colGMD-12 colGSM-12">
													<input type="text" name="mobile" placeholder="Your mobile number" class="colGLG-5 colGMD-12 colGSM-12">
													<input type="text" name="email" placeholder="Your email" class="colGLG-6 colGMD-12 colGSM-12">
													<input type="password" name="password" placeholder="Your password" class="colGLG-12 colGMD-12 colGSM-12">
													<div class="row">
														<input type="radio" name="role" id="user" value="2" checked>
														<label for="user" class="colGLG-6">User</label>
														<input type="radio" name="role" id="vendor" value="3">
														<label for="vendor" class="colGLG-6">Vendor</label>
													</div>
													<button id="signup" name="signup" class="colGLG-6">Signup</button>
													<label for="modal-trigger" class="colGLG-6 closeLabel"><i class="fa fa-times-circle fa-2x"></i></label>
													<p id="form-error"></p>
												</form>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>
					<?php else: ?>
					<li class="login-drop">
						<a href="#">My&nbsp;Account&nbsp;&nbsp;<i class="fa fa-sort-desc"></i></a>
						<ul>
							<li><a href="profile.html">Profile</a></li>
							<li><a href="cart.html">Cart</a></li>
							<li><a href="orders.html">Orders</a></li>
							<li><a href="<?php echo Yii::app()->createUrl('site/logout');?>">Logout</a></li>
						</ul>
					</li>
					<?php endif; ?>
					<li>
						<input class="checkbox-toggle" type="checkbox">
						<div class="hamburger">
							<div></div>
						</div>
						<div class="menu">
							<div>
								<div>
									<ul>
										<li><a href="#">About</a></li>
										<li><a href="#">Partners</a></li>
										<li><a href="#">Join us</a></li>
										<li><a href="#">Contact us</a></li>
									</ul>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</nav>

		<?php echo $content; ?>

		<!-- ALL Scripts goes here -->
		<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
		<script>
			$("document").ready(function(){
				$('#tab-content2').submit(function(){
					$.ajax({
						type: 'post',
						data: $(this).serialize(),
						url: "<?php echo Yii::app()->createUrl('site/Signup'); ?>",
						beforeSend: function(){
							$("#form-error").html("");
							$("#signup").html("Please Wait..");
							$("#signup").addClass("button-active");
						},
						complete: function(){
							$("#signup").html("Signup");
							$("#signup").removeClass("button-active");
						},
						success: function(data) {
							var response = $.parseJSON(data);
							if(response.status == '1') {
								$('#form-error').html(response.msg).removeClass('signup-failed').addClass('signup-success');
							} else {
								$('#form-error').html(response.msg).removeClass('signup-success').addClass('signup-failed');
							}
						},
						error: function(data){
							var response = $.parseJSON(data);
						}
					});
					return false;
				});
				$('#tab-content1').submit(function(){
					var resp;
					$.ajax({
						type: 'post',
						data: $(this).serialize(),
						url: "<?php echo Yii::app()->createUrl('site/login'); ?>",
						beforeSend: function(){
							$("#form-error1").html("");
							$("#login").html("Loggin in..");
							$("#login").addClass("button-active");
						},
						success: function(data) {
							var resp = $.parseJSON(data);
							if(resp.status == 1) {
								if(resp.role == 2) {
									window.location.href = "<?php echo Yii::app()->createUrl('site/index');?>";
								} else if (resp.role == 3) {
									window.location.href = "<?php echo Yii::app()->createUrl('dashboard/index');?>";
								}
								$("#form-error1").html(resp.msg).removeClass('login-failed').addClass('login-success');
								$("#modal-trigger").prop('checked',false);
							} else {
								$("#form-error1").html(resp.msg).removeClass('login-success').addClass('login-failed');
							}
						},
						complete: function(){
							$("#login").html("Login");
							$("#login").removeClass("button-active");
						},
						error: function(data){
							var resp = $.parseJSON(data);
						},
					});
					return false;
				});
				$("#tab1").prop("checked",true);
			});

			$("#modal-trigger").click(function(){
				$("#tab1").prop("checked",true);
			});
		</script>
	</body>
</html>