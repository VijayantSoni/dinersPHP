<header class="container forgotpassword">
	<div class="marginate">
		<div class="row forgot-content">
			<p>Don't Worry! We have your back ;D</p>
			<form id="forgot-password">
				<div class="row">
					<i class="fa fa-envelope colGLG-1"></i>
					<input class="colGLG-11" type="email" name="email" placeholder="Enter your email here !">
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
		$("#forgot-password").submit(function(){
			$.ajax({
				type: 'POST',
				url: "<?php echo Yii::app()->createUrl('site/ForgotPassword'); ?>",
				data: $(this).serialize(),
				beforeSend:function(){
					$("#reset").html("Please wait..").css({'background':'rgba(240,119,98,0.6)','color':'#fff'});
					$(".errors").html("");
				},
				success:function(data){
					var response = $.parseJSON(data);
					if (response.status == 2) {
						$(".errors").html(response.msg).removeClass('success').addClass('failed');
					} else {
						$(".errors").html(response.msg).removeClass('failed').addClass('success');
					}
				},
				error:function(){
					var response = $.parseJSON(data);
					$(".errors").html(response.msg);
				},
				complete:function(){
					$("#reset").html("Reset").css({'background':'#f2f2f2','color':'#000'});
				},
			});
			return false;
		});
	});
</script>