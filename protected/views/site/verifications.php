<?php if($status == 2):?>
<header class="container-fluid verification-errors">
	<div class="marginate">
		<div class="row">
			<p>You seem to have landed on a wrong page or an expired link</p>
			<h4><a href="<?php echo Yii::app()->createUrl('site/index')?>">Click to go back to home</a></h4>
		</div>
	</div>
</header>
<?php endif; ?>
<?php if($status == 1): ?>
<header class="container verification-errors">
	<div class="marginate">
		<div class="row">
			<p>Your email has been successfully verified.</p>
			<h4><a href="<?php echo Yii::app()->createUrl('site/index')?>">Click to go back to home</a></h4>
		</div>
	</div>
</header>
<?php endif;?>