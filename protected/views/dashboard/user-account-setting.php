<main class="dash-content-add-res container-fluid">
	<header class="row">
		<form class="row" id="user-settings">
			<figure class="colGLG-3 colGSM-12">
				<!-- <img src="#"> -->
				<i class="fa fa-user fa-5x"></i>
			</figure>
			<div class="colGLG-9 colGSM-12">
				<div class="row">
					<i class="fa fa-user"></i>
					<input type="text" name="first_name" placeholder="First Name" value="<?php echo $user->first_name;?>">
				</div>
				<div class="row">
					<i class="fa fa-user"></i>
					<input type="text" name="last_name" placeholder="Last Name" value="<?php echo $user->last_name;?>">
				</div>
				<div class="row">
					<i class="fa fa-envelope"></i>
					<input type="email" name="user_email" placeholder="User's Email" value="<?php echo $user->email;?>" required>
				</div>
				<div class="row">
					<i class="fa fa-mobile"></i>
					<input type="text" name="user_mobile" placeholder="User's Contact" value="<?php echo $user->mobile_number;?>" required>
				</div>
			</div>
			<div class="colGLG-6 colGSM-12">
				<div class="row">
					<i class="fa fa-key"></i>
					<input type="password" name="user_pass" placeholder="Password" required>
				</div>
			</div>
			<div class="colGLG-6 colGSM-12">
				<div class="row">
					<i class="fa fa-key"></i>
					<input type="password" name="user_confirm_pass" placeholder="Confirm Password" required>
				</div>
			</div>
			<div class="colGLG-6 colGSM-12 push-right">
				<div class="row">
					<button name="save-settings">Save</button>
					<p class="errors"></p>
				</div>
			</div>
		</form>
	</header>
</main>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#user-settings").submit(function(){
			$.ajax({
				type:'POST',
				url:"<?php echo Yii::app()->createUrl('dashboard/userAccountSettings'); ?>",
				data:$(this).serialize(),
				success:function(data) {
					var response = $.parseJSON(data);
					$('.errors').html(response.msg).addClass('success');
				},
				error:function() {
					$('.errors').html('Some problems occurred').addClass('failed');
				}
			});
			return false;
		});
	});
</script>