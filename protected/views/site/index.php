
<header class="marginate">
	<div class="row">
		<div class="colGLG-6">
			<h2>This year be a little more lazy</h2>
			<h1>Stay hungry<br>Stay foolish!</h1>
		</div>
		<div class="colGLG-6">
			<div class="card">
				<h3>Place your first order today</h3>
				<form id="search">
					<div class="query">
						<input type="text" name="location" placeholder="Enter your location" class="auto-pop" value='<?php echo Yii::app()->user->getState('location_name',NULL);?>' id='<?php echo Yii::app()->user->getState('location_id',NULL);?>' required>
						<ul class="list-select hider">
						<?php foreach($locations as $location): ?>
							<li id="<?php echo $location->id; ?>"><?php echo $location->name; ?></li>
						<?php endforeach; ?>
						</ul>
					</div>
					<input id="query" type="text" name="query" placeholder="Search for cuisines, restaurants or chefs" required>
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

<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/navup.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".auto-pop").on('focus', function(){
			$('.list-select').removeClass('hider').addClass('shower');
		});

		$(".auto-pop").keyup(function(){
			var string = $(".auto-pop").val();
			var expression = new RegExp(string,"gi");
			$('.list-select li').each(function(){
				if($(this).text().search(expression) == -1) {
					$(this).addClass('no-disp');
				} else {
					$(this).removeClass('no-disp');
				}
			});
		});

		$(document).on('click', function(e){
			var container = $('.auto-pop, .list-select');
			if(!container.is(e.target) && container.has(e.target).length === 0) {
				$('.list-select').removeClass('shower').addClass('hider');
			}
			var targetList = $(".list-select li:hover");
			if(targetList.is(e.target)) {
				$(".auto-pop").val(targetList.text());
				$(".auto-pop").prop('id',targetList.prop("id"));
				$('.list-select').removeClass('shower').addClass('hider');
			}
		});

		$("#search").submit(function() {
			$.ajax({
				type:'POST',
				url:"<?php echo Yii::app()->createUrl('site/search'); ?>",
				data:'location-id='+$('.auto-pop').prop("id")+'&query='+$('#query').val(),
				success:function(data) {
					var response = $.parseJSON(data);
					window.location.href = response.url;
				},
				error:function() {
					alert("Errr");
				},
			})
			return false;
		})
	});
</script>