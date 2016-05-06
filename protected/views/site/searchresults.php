<?php $locations = AvailableInLocation::model()->findAllByAttributes(array('status'=>1,'parent_location_id'=>NULL)); ?>
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
					<?php $categories = Category::model()->findAllByAttributes(array('status'=>1));?>
					<div class="row category">
						<input type="checkbox" id="category-trigger">
						<label for="category-trigger">Category<i class="fa fa-sort-desc push-right"></i>
						</label>
						<ul class="row" id="cat-list">
						<?php foreach($categories as $category): ?>
							<li>
								<input type="checkbox" name="sort-category" id="<?php echo join('-',split(' ', htmlentities($category->name))); ?>" value="<?php echo $category->id; ?>">&nbsp;<label for="<?php echo join('-',split(' ', htmlentities($category->name))); ?>"><?php echo htmlentities($category->name); ?></label>
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
				<form class="row container" id="search">
					<div class="colGLG-5 colGSM-12 loc-search">
						<i class="fa fa-map-marker colGLG-1 colGMD-1 colGSM-1"></i>
						<input type="text" name="location" placeholder="Enter your location" class="colGLG-11 colGMD-10 colGSM-10 auto-pop" id="<?php echo $location['id']; ?>" value="<?php echo $location['name']?>">
						<ul class="list-select hider">
							<?php foreach($locations as $location): ?>
							<li id="<?php echo $location->id; ?>"><?php echo $location->name; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="colGLG-5 colGSM-12 push-right">
						<i class="fa fa-search colGLG-1 colGMD-1 colGSM-1"></i>
						<input id="query" type="text" class="colGLG-11 colGMD-10 colGSM-10" value="<?php echo $query?$query:''; ?>">
					</div>
				</form>

				<div id="data-item" class="row">
				<?php foreach($items as $item): ?>
					<div class="colGLG-3 colGMD-6 colGSM-12">
						<?php if(isset($item['item'])): ?>
						<div class="items">
							<img src="<?php echo Yii::app()->request->baseUrl;?>/img/sampleFood.jpg">
							<div class="item-details">
								<div class="row">
									<p class="colGLG-8"><?php echo $item['name']; ?></p>
									<p class="colGLG-2"><i class="fa fa-inr"></i><?php echo $item['price']; ?></p>
									<span id="<?php echo $item['id'];?>" class="cart colGLG-1 cart-tip" <?php echo Yii::app()->user->isGuest?"onClick='openModal()'":"onClick='callCart($(this),event)'" ?>><i class="fa fa-cart-plus" aria-hidden="true"></i></span>
									<span id="<?php echo $item['id'];?>" <?php echo Yii::app()->user->isGuest?"onClick='openModal()'":"onClick='callCheckout($(this),event)'" ?> class="colGLG-1 check-tip"><i class="fa fa-check"></i></span>
								</div>
								<div class="row">
									<p class="colGLG-6"><?php echo $item['rest_name']; ?></p>
									<p class="colGLG-4" id="deliv"><?php echo $item['deliverable']?'Deliverable':'Takeaway'; ?></p>
									<p class="colGLG-1"><?php echo $item['time']?>M</p>
								</div>
								<div class="row">
									<h4 class="colGLG-12">Item Details</h4>
									<p class="colGLG-12">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
								</div>
							</div>
						</div>
						<?php elseif(isset($item['restaurant'])): ?>
							<div class="restaurant">
								<img src="<?php echo Yii::app()->request->baseUrl;?>/img/sampleFood.jpg">
								<div class="restaurant-details">
									<div class="row">
										<p class="colGLG-8"><?php echo $item['name']; ?></p>
										<a id="<?php echo $item['id']; ?>" href="#" class="colGLG-4">View Items</a>
									</div>
									<div class="row">
										<p class="colGLG-6"><?php echo $item['mainlocation']; ?></p>
										<p class="colGLG-6"><?php echo $item['sublocation']; ?></p>
									</div>
									<div class="row">
										<h4 class="colGLG-12">Item Details</h4>
										<p class="colGLG-12">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
									</div>
								</div>
							</div>
						<?php endif; ?>
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
	var currentRequest = null;
	function callCheckout(elem,e) {
		$.ajax({
			data:'itemId='+elem.prop('id'),
			type:'POST',
			url:"<?php echo Yii::app()->createUrl('site/checkout'); ?>",
			success:function(data) {
				alert("Proceeding to checkout");
				window.location.href = "<?php echo Yii::app()->createUrl('site/cart'); ?>";
			},
			error:function(data) {
				alert("Sorry some errors happened");
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
				$(".auto-pop").prop('value',targetList.text());
				$(".auto-pop").prop('id',targetList.prop("id"));
				$('.list-select').removeClass('shower').addClass('hider');
			}
		});

		function SendData() {
			$.ajax({
				type:'POST',
				url:"<?php echo Yii::app()->createUrl('site/search'); ?>",
				data:'location-id='+$('.auto-pop').prop("id")+'&query='+$('#query').val(),
				success:function(data) {
					var response = $.parseJSON(data);
					window.location.href = response.url;
				},
				error:function() {
					alert("Sorry some error occurred !");
				},
			})
			return false;
		}

		$('#query').keypress(function(e){
			if(e.which == 13) {
				alert('You pressed enter');
				SendData();
			}
		});

		$('input[name="sort-category"]').click(function(){
			var dat = [];
			$('#cat-list li').each(function() {
				if($(this).find('input').is(':checked')) {
					// $(this).find('label').addClass('label-active');
					dat.push($(this).find('input').val());
				}
			});
			currentRequest = jQuery.ajax({
				type:'POST',
				url:'<?php echo Yii::app()->createUrl('site/filter'); ?>',
				data:{filter: 1,categoryIds: dat,locId:$('.auto-pop').prop("id"),query:$('#query').val()},
				beforeSend:function() {
					if (currentRequest != null) {
						currentRequest.abort();
					}
				},
				success:function(ob) {
					var response = $.parseJSON(ob);
					$('#data-item').empty();
					for (var i = 0; i < response.length; i++) {
						$('#data-item').append('<div class="colGLG-3 colGMD-6 colGSM-12">\
												<div class="items">\
													<img src="<?php echo Yii::app()->request->baseUrl;?>/img/sampleFood.jpg">\
													<div class="item-details">\
														<div class="row">\
															<p class="colGLG-8">'+response[i].name+'</p>\
															<p class="colGLG-2"><i class="fa fa-inr"></i>'+response[i].price+'</p>\
															<span id="'+response[i].id+'" class="cart colGLG-1 cart-tip" <?php echo Yii::app()->user->isGuest?"onClick=\"openModal()\"":"onClick=\"callCart($(this),event)\"" ?>><i class="fa fa-cart-plus" aria-hidden="true"></i></span>\
															<span id="'+response[i].id+'" <?php echo Yii::app()->user->isGuest?"onClick=\"openModal()\"":"onClick=\"callCheckout($(this),event)\"" ?> class="colGLG-1 check-tip"><i class="fa fa-check"></i></span>\
														</div>\
														<div class="row">\
															<p class="colGLG-6">'+response[i].restName+'</p>\
															<p class="colGLG-4" id="deliv"></p>\
															<p class="colGLG-1">'+response[i].servingTime+'M</p>\
														</div>\
														<div class="row">\
															<h4 class="colGLG-12">Item Details</h4>\
															<p class="colGLG-12">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>\
														</div>\
													</div>\
												</div>');
						$('#deliv').html(response[i].deliverable?'Deliverable':'Takeaway');
					}
				},
				error:function() {
					alert('Failed');
				}
			});
		});

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