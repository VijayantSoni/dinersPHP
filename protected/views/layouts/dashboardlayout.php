<!DOCTYPE html>
<html>
<head>
	<title>Diners Meet - Vendor Dashboard</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/grid.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/material.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/dashboard-responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.min.css">
</head>
<body>
	<nav class="row">
		<span>
			<input type="checkbox" id="aside-trigger"></input>
			<label for="aside-trigger"><i class="fa fa-bars fa-2x"></i></label>
		</span>
		<figure><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo.png"></figure>
	</nav>
	<section class="content">
		<aside class="hide">
			<ul>
				<li><i class="fa fa-user fa-5x"></i></li>
				<li><p>Hey Vendor !</p></li>
			</ul>
			<ul class="aside-items">
				<li><a href="<?php echo Yii::app()->createUrl('dashboard/index');?>"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
				<li><a href="<?php echo Yii::app()->createUrl('dashboard/addRestaurant');?>"><i class="fa fa-plus"></i><span>Add&nbsp;Restaurant</span></a></li>
				<li><a href="<?php echo Yii::app()->createUrl('dashboard/addCuisine');?>"><i class="fa fa-plus"></i><span>Add&nbsp;Cuisine</span></a></li>
				<li><a href="<?php echo Yii::app()->createUrl('dashboard/editCuisine');?>"><i class="fa fa-pencil"></i><span>Edit&nbsp;Cuisine</span></a></li>
				<li><a href="<?php echo Yii::app()->createUrl('dashboard/editRestaurant');?>"><i class="fa fa-pencil"></i><span>Edit&nbsp;Restaurant</span></a></li>
				<li><a href="<?php echo Yii::app()->createUrl('dashboard/userAccountSettings');?>"><i class="fa fa-cogs"></i><span>Account&nbsp;Settings</span></a></li>
			</ul>
		</aside>

		<?php echo $content; ?>

	</section>

	<!-- All scripts goes here -->
	<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
	<script type="text/javascript">
		$('document').ready(function(){
			$('label[for="aside-trigger"] > i').removeClass('fa-times').addClass('fa-bars');
		});
		$('#aside-trigger').change(function(){
			if($('#aside-trigger').is(':checked')) {
				$('label[for="aside-trigger"] > i').removeClass('fa-bars').addClass('fa-times');
				$('section.content > aside').removeClass('hide').addClass('show');
			} else {
				$('label[for="aside-trigger"] > i').removeClass('fa-times').addClass('fa-bars');
				$('section.content > aside').removeClass('show').addClass('hide');
			}
		});
	</script>
</body>
</html>