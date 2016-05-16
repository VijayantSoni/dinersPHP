<header class="container-fluid contact">
	<div class="marginate">
		<div class="row">
			<h2>Drop us a line here</h2>
		</div>
		<form class="contact-box" method="post" action="<?php echo Yii::app()->createUrl('site/contact'); ?>">
			<div class="row">
				<div class="colGLG-6 colGMD-6 colGSM-12">
					<label for="name">Full Name</label>
					<input name="name" type="text">
				</div>
				<div class="colGLG-6 colGMD-6 colGSM-12">
					<label for="name">Mobile Number</label>
					<input name="mobile" type="text">
				</div>
			</div>

			<div class="row">
				<div class="colGLG-12 colGMD-12 colGSM-12">
					<label for="subject">Subject</label>
					<input type="text" name="subject">
				</div>
			</div>

			<div class="row">
				<div class="colGLG-12 colGMD-12 colGSM-12">
					<label>Matter</label>
					<textarea name="matter">

					</textarea>
				</div>
			</div>

			<div class="row">
				<div class="colGSM-12 colGLG-12 colGMD-12">
					<input type="submit" value="Submit">
				</div>
			</div>
		</form>
	</div>
</header>

<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('input[type="text"], textarea').on('focus',function() {
			var elem = $(this);
			elem.parent().find('label').addClass('label-off');
		})
		$('input[type="text"], textarea').on('focusout',function() {
			var elem = $(this);
			elem.parent().find('label').removeClass('label-off');
		})
	})
</script>