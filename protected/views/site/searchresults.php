
<header class="container-fluid banner">
	<section class="marginate">
		<hgroup class="container">
			<h3>Search all your favourite cusines and restaurants</h3>
			<h4>Meet our partners, place orders, get your order delivered right at your doorstep.</h4>
		</hgroup>
	</section>

	<section class="search-content container-fluid">
		<div class="row">
			<div id="filter-row" class="filter-row-float">
				<input type="checkbox" id="filter-trigger" checked>
				<label for="filter-trigger">
					<i class="fa fa-cog"></i>
					&nbsp; Filters
				</label>
			</div>

			<aside id="filters" class="aside-hide">
				<form class="filter-wrapper">
					<div class="row type">
						<hr>
						<span>Sort&nbsp;by</span>&nbsp;<i class="fa fa-sort-amount-asc push-right"></i>
						<hr>
						<ul class="row">
							<li>
								<input type="checkbox" name="sort-cuisine" id="sort-cuisine" value="cuisine">&nbsp;<label for="sort-cuisine"><i class="fa fa-cutlery"></i>&nbsp;&nbsp;Cuisine</label>
							</li>
							<li>
								<input type="checkbox" name="sort-restaurant" id="sort-restaurant" value="restaurant">&nbsp;<label for="sort-restaurant"><i class="fa fa-h-square"></i>&nbsp;&nbsp;Restaurant</label>
							</li>
						</ul>
					</div>
					<?php $categories = Category::model()->findAllByAttributes(array('status'=>1));?>
					<div class="row category">
						<input type="checkbox" id="category-trigger">
						<label for="category-trigger">Category<i class="fa fa-sort-desc push-right"></i>
						</label>
						<ul class="row">
						<?php foreach($categories as $category): ?>
							<li>
								<input type="checkbox" name="sort-category" id="sort-category" value="<?php echo $category->id; ?>">&nbsp;<label for="sort-category"><?php echo htmlentities($category->name); ?></label>
							</li>
						<?php endforeach; ?>
						</ul>
					</div>

					<div class="row filter">
						<hr>
						<span>Filter</span>&nbsp;<i class="fa fa-filter push-right"></i>
						<hr>
						<ul class="row">
							<li>
								<input type="checkbox" name="filter" id="filter-price" value="price">&nbsp;<label for="filter-price"><i class="fa fa-inr"></i>&nbsp;&nbsp;&nbsp;Price</label>
							</li>
							<li>
								<input type="checkbox" name="filter" id="filter-time" value="time">&nbsp;<label for="filter-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;Time</label>
							</li>
						</ul>
					</div>

				</form>
			</aside>

			<main class="search-results">
				<form class="row container">
					<div class="colGLG-5 colGSM-12">
						<i class="fa fa-map-marker colGLG-1 colGMD-1 colGSM-1"></i><input type="text" class="colGLG-11 colGMD-10 colGSM-10">
					</div>
					<div class="colGLG-5 colGSM-12 push-right">
						<i class="fa fa-search colGLG-1 colGMD-1 colGSM-1"></i><input type="text" class="colGLG-11 colGMD-10 colGSM-10">
					</div>
				</form>

				<div class="row">
				<?php foreach($items as $item): ?>
					<div class="colGLG-3 colGMD-6 colGSM-12">
						<div class="items">
							<img src="<?php echo Yii::app()->request->baseUrl;?>/img/sampleFood.jpg">
							<div class="item-details">
								<div class="row">
									<p class="colGLG-8"><?php echo $item->name; ?></p>
									<p class="colGLG-2"><i class="fa fa-inr"></i><?php echo $item->price; ?></p>
									<span id="<?php echo $item->id;?>" class="cart colGLG-1 cart-tip" <?php echo Yii::app()->user->isGuest?"onClick='openModal()'":"onClick='callCart($(this),event)'" ?>><i class="fa fa-cart-plus" aria-hidden="true"></i></span>
									<span id="<?php echo $item->id;?>" <?php echo Yii::app()->user->isGuest?"onClick='openModal()'":"onClick='callCheckout($(this),event)'" ?> class="colGLG-1 check-tip"><i class="fa fa-check"></i></span>
								</div>
								<div class="row">
									<p class="colGLG-6"><?php echo $item->restaurant->name; ?></p>
									<p class="colGLG-4"><?php echo $item->delivery_available?'Deliverable':'Takeaway'; ?></p>
									<p class="colGLG-1"><?php echo $item->serving_time?>M</p>
								</div>
								<div class="row">
									<h4 class="colGLG-12">Item Details</h4>
									<p class="colGLG-12">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</main>
		</div>
	</section>
</header>

<!-- ALL Scripts goes here -->
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<!-- <script src="js/navup.js"></script> -->
<script>
	function callCheckout(elem,e) {
		$.ajax({
			data:'itemId='+elem.prop('id'),
			type:'POST',
			url:"<?php echo Yii::app()->createUrl('site/checkout'); ?>",
			success:function(data) {
				var response = $.parseJSON(data);
				if(response.status == 1) {
					alert("Status1");
					window.location.href = response.url;
				} else if(response.status == 2){
					alert(response.msg);
				} else if(response.status == 3){
					window.location.href = response.url;
				}
			},
			error:function(data) {
				alert("Sorry errors");
			},
		});
	}

	function callCart(elem,e) {
		$.ajax({
			data:'itemId='+elem.prop('id'),
			type:'POST',
			url:"<?php echo Yii::app()->createUrl('site/addToCart'); ?>",
			success:function(data) {
				var response = $.parseJSON(data);
				alert(response.msg);
			},
			error:function() {
				alert("Sorry could not add");
			},
		});
	}
	function openModal() {
		$("#modal-trigger").prop('checked',true);
	}
	$("document").ready(function(){
		$("#category-trigger").prop("checked",false);
		$('#filter-trigger').prop('checked',true);
			if($('#filter-trigger').is(':checked')) {
				$('#filters').removeClass('aside-show').addClass('aside-hide');
			}
		$('#category-trigger').change(function(){
			if($('#category-trigger').is(':checked')) {
				$('#category-trigger ~ label i').addClass('rotate-i');
			} else {
				$('#category-trigger ~ label i').removeClass('rotate-i');
			}
		});
		$("#filter-trigger").change(function(){
			if ($("#filter-trigger").is(':checked')) {
				$("#filters").removeClass('aside-show').addClass('aside-hide');
			} else {
				$("#filters").removeClass('aside-hide').addClass('aside-show');
			}
		});

		var scroll;
		var del = 150;
		$(window).scroll(function(event) {
		  scroll = true;
		});

		setInterval(function() {
		  if (scroll) {
		  	filterRowHasScrolled();
		    scroll = false;
		  }
		}, 250);
		function filterRowHasScrolled() {
		  var st = $(this).scrollTop();
		  // alert(st);
		  if (st > del) {
		    $('#filter-row').removeClass('filter-row-float').addClass('filter-row-fixed');
		    $('#filters').addClass('aside-fixed');
		  } else {
	      $('#filter-row').removeClass('filter-row-fixed').addClass('filter-row-float');
	      $('#filters').removeClass('aside-fixed');
	    }
		}
	});


</script>