<main class="dash-content-add-res container-fluid">
	<header class="row">
		<form class="row" id="add_restaurant">
			<figure class="colGLG-3 colGSM-12">
				<i class="fa fa-user fa-5x"></i>
			</figure>
			<div class="colGLG-9 colGSM-12">
				<div class="row">
					<i class="fa fa-bookmark"></i>
					<input type="text" name="restaurant_name" placeholder="Restaurant's Name" required>
				</div>
				<div class="row">
					<i class="fa fa-map-marker"></i>
					<select name="main-location" id="main-location">
						<option id="0" value="0">Main Location</option>
						<?php foreach ($majorLoc as $loc): ?>
							<option value="<?php echo $loc->id;?>" id="<?php echo $loc->id;?>"><?php echo $loc->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="row">
					<i class="fa fa-map-marker"></i>
					<select name="sub-location" id="sub-location">
						<option id="0" value="0">Sub Location</option>
					</select>
				</div>
				<div class="row">
					<i class="fa fa-mobile"></i>
					<input type="text" name="mobile" placeholder="Restaurant's Contact" required>
				</div>
			</div>
			<div class="colGLG-12 colGSM-12">
				<div class="row">
					<i class="fa fa-globe"></i>
					<textarea name="street_addr" placeholder="Street Address" required></textarea>
				</div>
			</div>
			<div class="colGLG-6 colGSM-12 push-right">
				<div class="row">
					<button name="add-restaurant">Submit</button>
					<p class="errors"></p>
				</div>
			</div>
		</form>
	</header>
</main>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#main-location").change(function(){
			var id = $("#main-location option:selected").prop('id');
			var dat = 'id='+id;
			$.ajax({
				type:'POST',
				url:'<?php echo Yii::app()->createUrl('dashboard/addRestaurant')?>',
				data:dat,
				success:function(data){
					var response = $.parseJSON(data);
					for (var i = 0; i < response.length; i++) {
						// alert(response[i].id);
						$("#sub-location").append('<option value='+response[i].id+' id='+response[i].id+'>'+response[i].name+'</option>');
					}
				},
				error:function(){
					$('.errors').addClass('failed').html('There has been some errors. Please try again');
				}
			});
			return false;
		});

		$("#add_restaurant").submit(function(){
			// alert("Yes");
			$('.errors').html("");
			$.ajax({
				type:'post',
				url:"<?php echo Yii::app()->createUrl('dashboard/addRestaurant');?>",
				data:$(this).serialize(),
				beforeSend:function(){
					$('button[name="add-restaurant"]').addClass('button-active').html('Please wait..');
				},
				success:function(data){
					// alert("Success");
					var response = $.parseJSON(data);
					$('.errors').html(response.msg).addClass('success');
				},
				error:function(data){
					// alert("Error");
					$('.errors').html('There has been some errors in form submission.').addClass('failed');
				},
				complete:function(){
					$('button[name="add-restaurant"]').removeClass('button-active').html('Submit');
				}
			});
			return false;
		});
	});
</script>