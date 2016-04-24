<header class="marginate">
	<div class="row">
		<div class="colGLG-6">
			<h2>This year be a little more lazy</h2>
			<h1>Stay hungry<br>Stay foolish!</h1>
		</div>
		<div class="colGLG-6">
			<div class="card">
				<h3>Place your first order today</h3>
				<form method="post" action="#">
					<div class="query">
						<input type="text" name="location" placeholder="Enter your location" id="auto-pop">
						<ul class="list-select hider">
						<?php foreach($locations as $location): ?>
							<li id="<?php echo $location->id; ?>"><?php echo $location->name; ?></li>
						<?php endforeach; ?>
						</ul>
					</div>
					<input type="text" name="query" placeholder="Search for cuisines, restaurants or chefs">
					<input type="submit" value="Search" name="search" class="colGLG-5 colGMD-6 colGSM-12">
				</form>
			</div>
		</div>
	</div>
</header>

<section class="container row icons" id="features">
<div class="colGLG-4 colGMD-6 colGSM-12">
	<img src="<?php echo Yii::app()->baseUrl; ?>/img/light.png">
</div>
<div class="colGLG-4 colGMD-6 colGSM-12">
	<img src="<?php echo Yii::app()->baseUrl; ?>/img/bowl.png">
</div>
<div class="colGLG-4 colGMD-12 colGSM-12">
	<img src="<?php echo Yii::app()->baseUrl; ?>/img/wallet.png">
</div>
</section>

<section>
<header class="cd-header">
	<h1>Trending around you</h1>
</header>

<main class="cd-main-content">
	<div class="cd-tab-filter-wrapper">
		<div class="cd-tab-filter">
			<ul class="cd-filters">
				<li class="placeholder">
					<a data-type="all" href="#0">Everything</a> <!-- selected option on mobile -->
				</li>
				<li class="filter"><a class="selected" href="#0" data-type="all">Everything</a></li>
				<li class="filter" data-filter=".cuisines"><a href="#0" data-type="cuisines">Cuisines</a></li>
				<li class="filter" data-filter=".restaurants"><a href="#0" data-type="restaurants">Restaurants</a></li>
			</ul> <!-- cd-filters -->
		</div> <!-- cd-tab-filter -->
	</div> <!-- cd-tab-filter-wrapper -->

	<section class="cd-gallery">
		<ul>
			<li class="mix cuisines"><div></div></li>
			<li class="mix restaurants"><div></div></li>
			<li class="mix cuisines"><div></div></li>
			<li class="mix restaurants"><div></div></li>
			<li class="mix cuisines"><div></div></li>
			<li class="mix restaurants"><div></div></li>
			<li class="mix cuisines"><div></div></li>
			<li class="mix restaurants"><div></div></li>
			<li class="mix cuisines"><div></div></li>
			<li class="gap"></li>
			<li class="gap"></li>
			<li class="gap"></li>
		</ul>
	</section> <!-- cd-gallery -->
</main>
</section>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.mixitup.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/main.js"></script> <!-- Filter jQuery -->
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/navup.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#auto-pop").on('focus', function(){
			$('ul').removeClass('hider').addClass('shower');
		});

		$("#auto-pop").keyup(function(){
			$('ul li').each(function(){
				if($(this).text().test($("#auto-pop").val()) == false) {
					$(this).addClass('no-disp');
				} else {
					$(this).removeClass('no-disp');
				}
			});
		});

		$(document).on('click', function(e){
			var container = $('#auto-pop, .list-select');
			if(!container.is(e.target) && container.has(e.target).length === 0) {
				$('ul').removeClass('shower').addClass('hider');
			}
			var targetList = $("li:hover");
			if(targetList.is(e.target)) {
				$("#auto-pop").val(targetList.text());
				$('ul').removeClass('shower').addClass('hider');
			}
		});
	});
</script>