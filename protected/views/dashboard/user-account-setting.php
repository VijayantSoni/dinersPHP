<main class="dash-content-add-res container-fluid">
	<header class="row">
		<form class="row" id="user-settings">
			<figure class="colGLG-3 colGSM-12">
				<div class="image-box">
				<?php if(!$user->profile_image): ?>
					<a href="#" class="user-image" id="image-pick"><i class="fa fa-user fa-5x"></i></a>
					<input type="hidden" id="profile_image" name="profile_image">
				<?php else: ?>
					<img src="<?php echo $user->profile_image; ?>" style="max-width: 100%;">
					<input type="hidden" id="profile_image" name="profile_image" value="<?php $user->profile_image; ?>">
				<?php endif; ?>
				</div>
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
					<input type="password" name="user_pass" placeholder="Password" value="<?php echo base64_decode($user->password); ?>" required>
				</div>
			</div>
			<div class="colGLG-6 colGSM-12">
				<div class="row">
					<i class="fa fa-key"></i>
					<input type="password" name="user_confirm_pass" placeholder="Confirm Password" value="<?php echo base64_decode($user->password); ?>" required>
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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/filepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var key = "AxEAO62SlRhuJ7tUJw73Dz"
		filepicker.setKey(key);
		$('#image-pick').click(function(){
			filepicker.setKey("AfyDwACYjTPaC2oAavYkQz");
			filepicker.pick({
			services: ['COMPUTER', 'FACEBOOK', 'CLOUDAPP'],
			mimetype:'image/*',
			cropRatio:1,
			cropForce:true,
			},
			function onSuccess(Blob){
				alert('Updated');
				$('#profile_image').val(Blob.url);
				$('.image-box').empty();
				$('.image-box').append('<img src="'+Blob.url+'" style="max-width: 100%;">\
				                       <input><input type="hidden" id="profile_image" name="profile_image" value="'+Blob.url+'">');
			})
		});

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