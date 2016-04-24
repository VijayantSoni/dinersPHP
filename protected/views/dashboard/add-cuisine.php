<main class="dash-content-add-cus container-fluid">
	<header class="row">
		<form class="row" id="add-cuisines">
			<header class="colGLG-12 colGSM-12">
				<select id="restaurant-option" name="restaurant-option">
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
						<input type="text" name="cuisine_name" placeholder="Cuisine's Name" required>
					</div>
					<div class="row">
						<i class="fa fa-inr"></i>
						<input type="text" name="cuisine_price" placeholder="Cuisine's Price" required>
					</div>
					<div class="row">
						<i class="fa fa-clock-o"></i>
						<input type="text" name="cuisine_time" placeholder="Serving Time" required>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_details" placeholder="Cuisine Details | Example: 12 Piece/Plate" required></textarea>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_price_details" placeholder="Pricing Details | Example: 35 Small Plate, 40 Medium Plate" required></textarea>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="veg" value=1 id="veg" required>
						<label for="veg" class="colGLG-6">Veg</label>
						<input type="radio" name="veg" value=0 id="non-veg" required>
						<label for="non-veg" class="colGLG-6">Non-Veg</label>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="spicy" value=1 id="spicy" required>
						<label for="spicy" class="colGLG-6">Spicy</label>
						<input type="radio" name="spicy" value=0 id="non-spicy" required>
						<label for="non-spicy" class="colGLG-6">Non-Spicy</label>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="delivery" id="yes_delivery" value=1 required>
						<label for="yes_delivery" class="colGLG-6">Deliverable</label>
						<input type="radio" name="delivery" id="no_delivery" value=0 required>
						<label for="no_delivery" class="colGLG-6">Takeaway</label>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 push-right">
					<div class="row">
						<button id="addCuisine" name="addCuisine">Add</button>
						<p id="errors"></p>
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

		$('#add-cuisines').submit(function(){
			$.ajax({
				type:'POST',
				url:"<?php echo Yii::app()->createUrl('dashboard/addCuisine'); ?>",
				data: $('#add-cuisines').serialize(),
				beforeSend:function(){
					$("#errors").html("").removeClass('failed').removeClass('success');
					$("#addCuisine").html("Adding..");
				},
				success:function(data){
					var response = $.parseJSON(data);
					$("#errors").html(response.msg).addClass('success');
					$("input[type='text']").val("");
					$("textarea").html("");
				},
				complete:function(){
					$("#addCuisine").html("Add").addClass('success');
				},
				error:function(data) {
					$("#errors").html("There has been some errors.").addClass('failed');
				},
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