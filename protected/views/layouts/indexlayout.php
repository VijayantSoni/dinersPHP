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
		<div id="loc-mod" class="location-modal-hide">
			<div class="location row" id="controlid">
				<div class="row">
					<input type="text" class="auto-popup" placeholder="Please set your location" required>
					<?php $locations = AvailableInLocation::model()->findAllByAttributes(array('status'=>1,'parent_location_id'=>NULL)); ?>
					<ul class="loc-select hider">
					<?php foreach($locations as $location): ?>
						<li id="<?php echo $location->id; ?>"><?php echo $location->name; ?></li>
					<?php endforeach; ?>
					</ul>
				</div>
				<div class="row">
					<label for="location-trigger" id="set-in">Set</label>
				</div>
			</div>
		</div>
		<nav class="row top-fixed nav-down">
			<div class="container">
				<a href="<?php echo Yii::app()->createUrl('site/index'); ?>" class="colGLG-4 colGMD-12 colGSM-12"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logonew.png"></a>
				<ul class="colGLG-8 colGMD-12 colGSM-12" id="main-nav">
					<li><input type="checkbox" id="location-trigger"><label id="set-out" for="location-trigger">Location</label></li>
					<li><a onClick="checkLoc($(this),event)" href="<?php echo Yii::app()->createUrl('site/search',array('restaurant'=>1,'locId'=>Yii::app()->user->getState("location_id",NULL))); ?>">Restaurants</a></li>
					<li><a onClick="checkLoc($(this),event)" href="<?php echo Yii::app()->createUrl('site/search',array('item'=>1,'locId'=>Yii::app()->user->getState('location_id',NULL))); ?>">Cuisines</a></li>

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
							<li><a href="<?php echo Yii::app()->createUrl('site/profile');?>">Profile</a></li>
							<li><a href="<?php echo Yii::app()->createUrl('site/cart');?>">Cart</a></li>
							<li><a href="<?php echo Yii::app()->createUrl('site/viewOrders');?>">Orders</a></li>
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
										<li><a href="<?php echo Yii::app()->createUrl('site/viewPage',array('about'=>1)); ?>">About</a></li>
										<li><a href="<?php echo Yii::app()->createUrl('site/viewPage',array('partners'=>1)); ?>">Partners</a></li>
										<li><a href="<?php echo Yii::app()->createUrl('site/viewPage',array('contact'=>1)); ?>">Contact us</a></li>
									</ul>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</nav>
		<div id="anchor">
			<?php echo $content; ?>
		</div>
		<footer class="container-fluid row footer">
			<div class="container">
				<div class="colGLG-4 colGMD-5 colGSM-12">
					<ul>
						<li><a href="<?php echo Yii::app()->createUrl('site/index');?>">Home</a></li>
						<li><a href="<?php echo Yii::app()->createUrl('site/viewPage',array('about'=>1)); ?>">About&nbsp;Us</a></li>
						<li><a href="<?php echo Yii::app()->createUrl('site/viewPage',array('contact'=>1)); ?>">Contact&nbsp;Us</a></li>
					</ul>
				</div>
				<div class="colGLG-4 colGMD-3 colGSM-12">
					<p><i class="fa fa-copyright"></i>&nbsp;DinersMeet&nbsp;2016</p>
				</div>
				<div class="colGLG-4 colGMD-4 colGSM-12">
					<ul>
						<li>Follow&nbsp;us&nbsp;@</li>
						<li><a href="#"><i class="fa fa-twitter"></i></a></li>
						<li><a href="#"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
					</ul>
				</div>
			</div>
		</footer>
		<!-- ALL Scripts goes here -->
		<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
		<script>
			function checkLoc(elem,e) {
				e.preventDefault();
				if($('.auto-popup').prop('id') == 'auto-popup') {
					$('#location-trigger').trigger('click');
				} else {
					window.location.href = elem.prop('href');
				}
			}
			function callAjax(e,id) {
				e.preventDefault();
				$.ajax({
					type:'GET',
					url:'<?php echo Yii::app()->createUrl('site/verify');?>',
					data:'verifyid='+id,
					success:function(data) {
						var response = $.parseJSON(data);
						window.location.href = response.url;
					},
					error:function() {
						alert("Sorry there has been some errors. Please verify by email");
					}
				});
			}

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
								if(typeof response[0] === 'undefined') {
									$('#form-error').html("Successully registered. Verify by email").removeClass('signup-failed').addClass('signup-success');
								}
								$('#form-error').html(response.msg).removeClass('signup-failed').addClass('signup-success');
								$('#form-error').append('<a onClick="callAjax(event,'+response.id+')" href="#">Or by SMS</a>');
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
								if(resp.role == 2 && window.location.href == "<?php echo Yii::app()->createUrl('site/index');?>") {
									window.location.href = "<?php echo Yii::app()->createUrl('site/index');?>";
								} else if (resp.role == 2 && window.location.href != "<?php echo Yii::app()->createUrl('site/index');?>") {
									window.location.href = window.location.href;
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

				$('.auto-popup').prop('id',"<?php echo Yii::app()->user->getState('location_id',NULL)?Yii::app()->user->getState('location_id'):'auto-popup'; ?>");
				$('.auto-popup').val("<?php echo Yii::app()->user->getState('location_name',NULL); ?>");
				$('#set-out').html("<?php echo Yii::app()->user->getState('location_id',NULL)?Yii::app()->user->location_name:'Location'; ?>")

				$('#location-trigger').change(function(){
					if($('#location-trigger').is(':checked')) {
						$('#loc-mod').removeClass('location-modal-hide').addClass('location-modal-show');
					} else {
						if($('.auto-popup').prop('id') !== "auto-popup") {
							$('#set-out').html($('.auto-popup').val());
							$.ajax({
								url:"<?php echo Yii::app()->createUrl('site/setLocation'); ?>",
								type:'POST',
								data:"locId="+$('.auto-popup').prop('id')+"&locName="+$('.auto-popup').val(),
								success:function(data) {
									var response = $.parseJSON(data);
									window.location.href = "<?php echo Yii::app()->createUrl('site/index'); ?>"
								},
								error:function() {
									alert("Error in confirming location");
								}
							});
						}
						$('#loc-mod').removeClass('location-modal-show').addClass('location-modal-hide');
					}
				});

				$(".auto-popup").on('focus', function(){
					$('.loc-select').removeClass('hider').addClass('shower');
				});

				$(".auto-popup").keyup(function(){
					$('.loc-select').removeClass('hider').addClass('shower');
					var string = $(".auto-popup").val();
					var expression = new RegExp(string,"gi");
					$('.loc-select li').each(function(){
						if($(this).text().search(expression) == -1) {
							$(this).addClass('no-disp');
						} else {
							$(this).removeClass('no-disp');
						}
					});
				});

				$(document).on('click', function(e){
					var container = $('.auto-popup, .loc-select');
					if(!container.is(e.target) && container.has(e.target).length === 0) {
						$('.loc-select').removeClass('shower').addClass('hider');
					}
					var targetList = $(".loc-select li:hover");
					if(targetList.is(e.target)) {
						$(".auto-popup").val(targetList.text());
						$(".auto-popup").prop("id",targetList.prop("id"));
						$('.loc-select').removeClass('shower').addClass('hider');
					}
				});
			});

			$("#modal-trigger").click(function(){
				$("#tab1").prop("checked",true);
			});
		</script>
	</body>
</html>