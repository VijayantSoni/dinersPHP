<header class="container forgotpassword">
	<div class="marginate">
		<div class="row forgot-content">
			<p>Please enter the OTP sent on your registered number.</p>
			<form id="sms-verify">
				<div class="row">
					<i class="fa fa-key colGLG-1"></i>
					<input class="colGLG-11" type="text" name="otp" placeholder="Enter your OTP here !">
				</div>
				<button id="verify" name="verify" value="verify">Verify</button>
				<h6 id="errors" class="errors"></h6>
			</form>
		</div>
	</div>
</header>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#sms-verify").submit(function(){
			$.ajax({
				type: 'POST',
				url: "<?php echo Yii::app()->createUrl('site/verify'); ?>",
				data: $(this).serialize()+'&id=<?php echo $id;?>',
				beforeSend:function(){
					$("#reset").html("Please wait..").css({'background':'rgba(240,119,98,0.6)','color':'#fff'});
					$(".errors").html("");
				},
				success:function(data){
					var response = $.parseJSON(data);
					if (response.status == 1) {
						alert(response.msg);
						window.location.href = response.url;
					} else {
						alert(response.msg);
					}
				},
				error:function(){
					alert("Errors found");
				},
				complete:function(){
					$("#reset").html("Reset").css({'background':'#f2f2f2','color':'#000'});
				},
			});
			return false;
		});
	});
</script>