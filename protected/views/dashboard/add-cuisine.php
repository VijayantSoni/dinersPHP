<main class="dash-content-add-cus container-fluid">
	<header class="row">
		<form class="row" id="add-cuisine">
			<header class="colGLG-12 colGSM-12">
				<select id="restaurant-option" name="restaurant-option[opt]">
					<option value="NULL">Select Restaurant</option>
					<?php foreach ($restaurant as $res): ?>
						<option id="<?php echo $res->id;?>" value="<?php echo $res->id;?>"><?php echo $res->name;?>&nbsp;-&nbsp;<?php echo $res->location->name; ?></option>
					<?php endforeach; ?>
				</select>
			</header>
			<main class="main-hide row">
				<figure class="colGLG-3 colGSM-12">
					<!-- <img src="#"> -->
					<i class="fa fa-user fa-5x"></i>
				</figure>
				<div class="colGLG-9 colGSM-12">
					<div class="row">
						<i class="fa fa-bookmark"></i>
						<input type="text" name="cuisine_name" placeholder="Cuisine's Name">
					</div>
					<div class="row">
						<i class="fa fa-inr"></i>
						<input type="text" name="cuisine_price" placeholder="Cuisine's Price">
					</div>
					<div class="row">
						<i class="fa fa-clock-o"></i>
						<input type="text" name="cuisine_time" placeholder="Serving Time">
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_details" placeholder="Cuisine Details | Example: 12 Piece/Plate"></textarea>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_price_details" placeholder="Pricing Details | Example: 35 Small Plate, 40 Medium Plate"></textarea>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="veg" value="Veg" id="veg">
						<label for="veg" class="colGLG-6">Veg</label>
						<input type="radio" name="veg" value="Non-Veg" id="non-veg">
						<label for="non-veg" class="colGLG-6">Non-Veg</label>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="spicy" value="Spicy" id="spicy">
						<label for="spicy" class="colGLG-6">Spicy</label>
						<input type="radio" name="spicy" value="Non-Spicy" id="non-spicy">
						<label for="non-spicy" class="colGLG-6">Non-Spicy</label>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="delivery" id="yes_delivery" value="delivery">
						<label for="yes_delivery" class="colGLG-6">Deliverable</label>
						<input type="radio" name="delivery" id="no_delivery" value="takeaway">
						<label for="no_delivery" class="colGLG-6">Takeaway</label>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 push-right">
					<div class="row">
						<button name="add-cuisine">Add</button>
					</div>
				</div>
			</main>
		</form>
	</header>
</main>

<!-- All scripts goes here -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$('document').ready(function(){
		$('#restaurant-option').val('NULL');
		$('#veg').prop('checked',true);
		$('#non-spicy').prop('checked',true);
		$('#yes_delivery').prop('checked',true);
		$('label[for="aside-trigger"] > i').removeClass('fa-times').addClass('fa-bars');

		$('#add-cuisine').submit(function(){
			$.ajax({
				type:'POST',
				url:"Yii::app()->createUrl('dashboard/AddCuisine')",
				data: $('#add-cuisine').serialize(),
				success:function(data){
					alert("Yes");
				},
				error:function(data) {
					alert("No");
				}
			});
			return false;
		});
	});

	$('#restaurant-option').change(function(){
		if( $('#restaurant-option option:selected').val() != 'NULL') {
			$('.dash-content-add-cus main').removeClass('main-hide').addClass('main-show');
		} else {
			$('.dash-content-add-cus main').removeClass('main-show').addClass('main-hide');
		}
	});
</script>