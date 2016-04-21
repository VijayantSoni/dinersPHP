<header class="container reset">
	<div class="marginate">
		<div class="row reset-content">
			<form id="reset-password">
				<div class="row">
					<i class="fa fa-key colGLG-1"></i>
					<input class="colGLG-11" id="password" type="password" name="password" placeholder="Enter your desired password">
				</div>
				<div class="row">
					<i class="fa fa-key colGLG-1"></i>
					<input class="colGLG-11" id="confirm-password" type="password" name="confirm-password" placeholder="Confirm your password">
				</div>
				<button id="reset" name="reset" value="reset">Reset</button>
				<h6 id="errors" class="errors"></h6>
			</form>
		</div>
	</div>
</header>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#reset-password").submit(function(){
			$("#errors").html("").removeClass('success').removeClass('failed');
			if ($("#password").val() !== $("#confirm-password").val()) {
				$("#errors").html("Passwords do not match").addClass('failed');
			} else if($("#password").val().length < 8){
				$("#errors").html("Password length must be greated than 8 characters").addClass('failed');
			} else {
				$.ajax({
					type: 'POST',
					url: "<?php echo Yii::app()->createUrl('site/ResetPassword',array('email'=>$_GET['email'])); ?>",
					data: $(this).serialize(),
					success:function(data) {
						var response = $.parseJSON(data);
						if(response.status == 1){
							alert(response.msg);
							window.location.href = "<?php echo Yii::app()->createUrl('site/index'); ?>";
						} else {
							$("#errors").html(response.msg).addClass('failed');
						}
					},
					error:function(data) {
						var response = $.parseJSON(data);
						$("#errors").html(response.msg).addClass('failed');
					},
				});
			}
			return false;
		});
	});
</script>