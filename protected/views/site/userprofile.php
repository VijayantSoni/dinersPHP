<style type="text/css">
.user-image {
	color: #000;
	/*background: #000;*/
}
.image-box {
	width: 150px;
	height: 150px;
	display: flex;
	justify-content: center;
	align-items: center;
	overflow: hidden;
}
</style>
<header class="container-fluid profile">
	<form class="marginate" id="user-profile">
		<div class="row">
			<figure class="colGLG-12">
				<div class="image-box">
				<?php if(!$user->profile_image): ?>
					<a href="#" class="user-image" id="image-pick"><i class="fa fa-user fa-5x"></i></a>
					<input type="hidden" id="profile_image" name="profile_image">
				<?php else: ?>
					<img src="<?php echo $user->profile_image; ?>" style="max-width: 100%;" id="image-pick">
					<input type="hidden" id="profile_image" name="profile_image" value="<?php echo $user->profile_image; ?>">
				<?php endif; ?>
				</div>
			</figure>
		</div>

		<div class="row about">
			<div class="row">
				<div>
					<i class="fa fa-user"></i><input type="text" name="first_name" id="first-name" placeholder="First Name" value="<?php echo $user->first_name?$user->first_name:''; ?>">
				</div>
				<div class="push-right">
					<i class="fa fa-user"></i><input type="text" name="last_name" id="last-name" placeholder="Last Name" value="<?php echo $user->last_name?$user->last_name:''; ?>">
				</div>
			</div>

			<div class="row">
				<div>
					<i class="fa fa-envelope"></i><input type="email" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>" required>
				</div>
				<div class="push-right">
					<i class="fa fa-phone"></i><input type="text" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $user->mobile_number; ?>" required>
				</div>
			</div>

			<div class="row">
				<div>
					<i class="fa fa-key"></i><input value="<?php echo base64_decode($user->password); ?>" type="password" name="password" id="password" placeholder="Password" required>
				</div>
				<div class="push-right">
					<i class="fa fa-key"></i><input value="<?php echo base64_decode($user->password); ?>" type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" required>
				</div>
			</div>
		</div>

		<div class="row address-book">
			<div class="row">
				<h4>Address Book</h4>
				<hr>
			</div>
			<?php if(empty($user->customerAddressBooks)):?>
			<?php echo "No address added yet"; ?>
			<div class="row address">
				<div class="row">
					<div class="colGLG-6 colGMD-6 colGSM-12">
						<input type="text" name="recipient_name" id="recipient_name" placeholder="Your name" disabled="true">
					</div>
					<div class="colGLG-6 colGMD-6 colGSM-12">
						<input type="text" name="recipient_mobile" placeholder="Your Mobile Number" id="recipient_mobile" disabled="true">
					</div>
				</div>

				<div class="row">
					<div class="colGLG-12">
						<textarea class="colGLG-12" placeholder="Address please" disabled="true" name="recipient_addr" id="recipient_addr"></textarea>
					</div>
				</div>

				<div class="row">
					<div class="colGLG-4 colGMD-6 colGSM-12">
						<input type="radio" name="address" id="edit">
						<label for="edit">Edit</label>
					</div>
				</div>
			</div>
			<?php else: ?>
			<div class="row address">
				<div class="row">
					<div class="colGLG-6 colGMD-6 colGSM-12">
						<input type="text" value="<?php echo $user->customerAddressBooks[0]->recipient_name;?>" name="recipient_name" id="recipient_name" disabled="true">
					</div>
					<div class="colGLG-6 colGMD-6 colGSM-12">
						<input type="text" value="<?php echo $user->customerAddressBooks[0]->recipient_mobile;?>" name="recipient_mobile" id="recipient_mobile" disabled="true">
					</div>
				</div>

				<div class="row">
					<div class="colGLG-12">
						<textarea class="colGLG-12" disabled="true" name="recipient_addr" id="recipient_addr"><?php echo $user->customerAddressBooks[0]->address;?></textarea>
					</div>
				</div>

				<div class="row">
					<div class="colGLG-4 colGMD-6 colGSM-12">
						<input type="radio" name="address" id="edit">
						<label for="edit">Edit</label>
					</div>
				</div>
			</div>
			<?php endif;?>
		</div>

		<div class="row">
			<div class="push-right">
				<input type="submit" value="Save" name="profile_save"></input>
			</div>
		</div>
	</form>
</header>


<!-- ALL Scripts goes here -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/filepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<!-- <script src="js/navup.js"></script> -->
<script>
	$("document").ready(function(){
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
				$('#profile_image').val(Blob.url);
				parent = $('#profile_image').parent().parent();
				$('.image-box').remove();
				parent.append('<div class="image-box">\
				              	<img src="'+Blob.url+'" style="max-width: 100%;" id="default">\
				              	<input type="hidden" id="profile_image" name="profile_image" value="'+Blob.url+'">');
			})
		});

		if($("#edit").is(":checked")) {
			$("#edit").prop("checked",false);
		}
		if($("#save").is(":checked")) {
			$("#save").prop("checked",false);
		}
		if($("#delete").is(":checked")) {
			$("#delete").prop("checked",false);
		}
		$("#recipient_name").prop("disabled",true);
		$("#recipient_mobile").prop("disabled",true);
		$("#recipient_addr").prop("disabled",true);

		$("#edit").click(function(){
			if ($("#edit").is(":checked")) {
				$("#recipient_name").addClass("enable");
				$("#recipient_name").prop("disabled",false);

				$("#recipient_mobile").addClass("enable");
				$("#recipient_mobile").prop("disabled",false);

				$("#recipient_addr").addClass("enable");
				$("#recipient_addr").prop("disabled",false);
			}
		});

		$("#user-profile").submit(function() {
			$.ajax({
				data:$(this).serialize(),
				url:"<?php echo Yii::app()->createUrl('site/profile'); ?>",
				type:'POST',
				success:function(data) {
					var response = $.parseJSON(data);
					alert(response.msg);
				},
				error:function(data) {
					alert("Sorry there have been some errors");
				}
			})
			return false;
		})
	});

</script>