<main class="dash-content-add-res container-fluid">
	<header class="row">
		<form class="row" id="edit_restaurant">
			<select id="restaurant-option" name="restaurant-option">
				<option value="NULL">Select Restaurant</option>
				<?php foreach ($restaurant as $res): ?>
					<option id="<?php echo $res->id;?>" value="<?php echo $res->id;?>"><?php echo $res->name;?>&nbsp;-&nbsp;<?php echo $res->location->name; ?></option>
				<?php endforeach; ?>
			</select>
			<figure class="colGLG-3 colGSM-12">
				<i class="fa fa-user fa-5x" id="default" style="cursor: pointer;"></i>
			</figure>
			<div class="colGLG-9 colGSM-12">
				<div class="row">
					<i class="fa fa-bookmark"></i>
					<input type="text" name="restaurant_name" placeholder="Restaurant's Name" required>
				</div>
				<div class="row">
					<i class="fa fa-map-marker"></i>
					<select name="main-location" id="main-location">
					</select>
				</div>
				<div class="row">
					<i class="fa fa-map-marker"></i>
					<select name="sub-location" id="sub-location">
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
					<button name="edit-restaurant">Update</button>
					<p class="errors"></p>
				</div>
			</div>
		</form>
	</header>
</main>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/filepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<script>
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

		$("#restaurant-option").change(function(){
			$("#main-location").empty();
			$("#sub-location").empty();
			if($("#restaurant-option option:selected").val() != 'NULL') {
				var id = $("#restaurant-option option:selected").prop('id');
				var dat = 'restId='+id;
				$.ajax({
					type:'POST',
					data:dat,
					url:"<?php echo Yii::app()->createUrl('dashboard/editRestaurant'); ?>",
					success:function(data) {
						var response = $.parseJSON(data);
						$("input[name='restaurant_name']").val(response.name);
						$("#main-location").append('<option value='+response.parent_location_id+' id='+response.parent_location_id+'>'+response.parent_location_name+'</option>');
						$("#sub-location").append('<option value='+response.sub_location_id+' id='+response.sub_location_id+'>'+response.sub_location_name+'</option>');
						$("input[name='mobile']").val(response.contact);
						$('textarea').html(response.address);
						if(response.url == null) {
							var parent = $('#default').parent();
							parent.append('<input type="hidden" id="logo" name="logo" value="">');
						} else {
							var parent = $('#default').parent();
							$('#default').remove();
							parent.append('<div class="image-box">\
												<img src="'+Blob.url+'" style="max-width: 100%;">\
											</div>\
											<input type="hidden" id="logo" name="logo" value="'+response.logo+'">');
						}
					},
					error:function() {
						alert("Sorry there were some errors");
					}
				});
			}
		});

		$("#edit_restaurant").submit(function(){
			$(".errors").html("").removeClass('failed').removeClass('success');
			$.ajax({
				type:'POST',
				data:$(this).serialize(),
				url:"<?php echo Yii::app()->createUrl('dashboard/editRestaurant'); ?>",
				success:function(data){
					var response = $.parseJSON(data);
					if(response.status == 1)
						$(".errors").html(response.msg).addClass('success');
					else
						$(".errors").html(response.msg).addClass('failed');
				},
				error:function(){
					alert("No");
				},
			});
			return false;
		})
	});

</script>