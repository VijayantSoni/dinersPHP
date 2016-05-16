<main class="dash-content-edit-cus container-fluid">
	<header class="row">
		<form class="row" id="updateCuisineForm">
			<header class="colGLG-12 colGSM-12">
			</header>
			<main class="main-show row">
				<figure class="colGLG-3 colGSM-12">
					<!-- <img src="#"> -->
					<?php if(!$item->logo): ?>
						<i class="fa fa-user fa-5x" id="default" style="cursor: pointer;"></i>
						<input type="hidden" name="logo" id="logo">
					<?php else: ?>
						<div class="image-box">
							<img src="<?php echo $item->logo; ?>" style="max-width: 100%;">
							<input type="hidden" name="logo" id="logo" value="$item->logo">
						</div>
					<?php endif; ?>
				</figure>
				<div class="colGLG-9 colGSM-12">
					<div class="row">
						<i class="fa fa-bookmark"></i>
						<input type="text" name="cuisine_name" placeholder="Cuisine's Name" value="<?php echo $item->name; ?>">
					</div>
					<div class="row">
						<i class="fa fa-inr"></i>
						<input type="text" name="cuisine_price" placeholder="Cuisine's Price" value="<?php echo $item->price; ?>">
					</div>
					<div class="row">
						<i class="fa fa-clock-o"></i>
						<input type="text" name="cuisine_time" placeholder="Serving Time" value="<?php echo $item->serving_time; ?>">
					</div>
				</div>
				<?php $categories = Category::model()->findAllByAttributes(array('status'=>1));?>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-codiepie"></i>
						<select name="category" id="category">
							<option value="NULL">Select a category</option>
							<?php foreach ($categories as $category): ?>
							<option <?php echo $category->id == $item->category_id?"selected":""; ?> value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_details" placeholder="Cuisine Details | Example: 12 Piece/Plate"><?php echo $item->details; ?></textarea>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_price_details" placeholder="Pricing Details | Example: 35 Small Plate, 40 Medium Plate"><?php echo $item->pricing_detail;?></textarea>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
					<?php if($item->is_veg == 1): ?>
						<input type="radio" name="veg" value=1 id="veg" checked>
						<label for="veg" class="colGLG-6">Veg</label>
						<input type="radio" name="veg" value=0 id="non-veg">
						<label for="non-veg" class="colGLG-6">Non-Veg</label>
					<?php else: ?>
						<input type="radio" name="veg" value=1 id="veg">
						<label for="veg" class="colGLG-6">Veg</label>
						<input type="radio" name="veg" value=0 id="non-veg" checked>
						<label for="non-veg" class="colGLG-6">Non-Veg</label>
					<?php endif; ?>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
					<?php if($item->is_spicy == 1):?>
						<input type="radio" name="spicy" value=1 id="spicy" checked>
						<label for="spicy" class="colGLG-6">Spicy</label>
						<input type="radio" name="spicy" value=0 id="non-spicy">
						<label for="non-spicy" class="colGLG-6">Non-Spicy</label>
					<?php else: ?>
						<input type="radio" name="spicy" value=1 id="spicy">
						<label for="spicy" class="colGLG-6">Spicy</label>
						<input type="radio" name="spicy" value=0 id="non-spicy" checked>
						<label for="non-spicy" class="colGLG-6">Non-Spicy</label>
					<?php endif; ?>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12 radios">
					<div class="row">
					<?php if($item->delivery_available == 1): ?>
						<input type="radio" name="delivery" id="yes_delivery" value=1 checked>
						<label for="yes_delivery" class="colGLG-6">Deliverable</label>
						<input type="radio" name="delivery" id="no_delivery" value=0>
						<label for="no_delivery" class="colGLG-6">Takeaway</label>
					<?php else: ?>
						<input type="radio" name="delivery" id="yes_delivery" value=1>
						<label for="yes_delivery" class="colGLG-6">Deliverable</label>
						<input type="radio" name="delivery" id="no_delivery" value=0 checked>
						<label for="no_delivery" class="colGLG-6">Takeaway</label>
					<?php endif; ?>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 push-right">
					<div class="row">
						<button id="updateCuisine" name="updateCuisine">Update</button>
						<p id="errors"></p>
					</div>
				</div>
			</main>
		</form>
	</header>
</main>

<!-- All scripts goes here -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/filepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#default').on('click',function() {
			filepicker.setKey("AfyDwACYjTPaC2oAavYkQz");
			filepicker.pick({
			services: ['COMPUTER', 'FACEBOOK', 'CLOUDAPP'],
			mimetype:'image/*',
			cropRatio:1,
			cropForce:true,
			},
			function onSuccess(Blob){
				$('#logo').val(Blob.url);
				var parent = $('#default').parent();
				$('#default').remove();
				parent.append('<div class="image-box">\
									<img src="'+Blob.url+'" style="max-width: 100%;">\
								</div>');
			})
		});

		$('#updateCuisineForm').submit(function(){
			$("#errors").removeClass('failed').removeClass('success').html("");
			$.ajax({
				type:'POST',
				url:"<?php echo Yii::app()->createUrl('dashboard/editCuisineForm',array('id'=>$item->id)); ?>",
				data:$(this).serialize(),
				success:function(data){
					var response = $.parseJSON(data);
					$("#errors").html(response.msg).addClass('success');
				},
				error:function(){
					$("#errors").html("There has been some errors").addClass('failed');
				}
			});
			return false;
		});
	});
</script>