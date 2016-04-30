<main class="dash-content-view-order container-fluid">
	<header class="row">
		<form id="view-orders"
		 class="row">
			<header class="colGLG-12 colGSM-12">
				<select id="restaurant-option" name="restaurant-option">
					<option value="NULL">Select Restaurant</option>
					<?php foreach ($restaurants as $res): ?>
						<option id="<?php echo $res->id;?>" value="<?php echo $res->id;?>"><?php echo $res->name;?>&nbsp;-&nbsp;<?php echo $res->location->name; ?></option>
					<?php endforeach; ?>
				</select>
			</header>
			<main class="main-hide colGLG-12">
				<table class="colGLG-12 colGSM-12">
					<thead class="row">
						<tr class="row">
							<td class="colGLG-1">Order&nbsp;Id</td>
							<td>Customer&nbsp;</td>
							<td>Serving&nbsp;Type</td>
							<td>Item</td>
							<td>Quantity</td>
							<td>Amount</td>
							<td>Status</td>
							<td>Pickup&nbsp;Time</td>
						</tr>
					</thead>
					<tbody class="row" id="item-data">
					</tbody>
				</table>
			</main>
		</form>
	</header>
</main>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	function loadDataAjax(dat,restId=0) {
		$.ajax({
			type:'POST',
			url:"<?php echo Yii::app()->createUrl('dashboard/loadOrders');?>",
			data:dat,
			success:function(data){
				var response = $.parseJSON(data);
				$("#item-data").empty();
				for (var i = 0; i < response.length; i++) {
					$("#item-data").append('<tr class="row">\
					                       <td class="colGLG-1">'+response[i].id+'</td>\
					                       <td>'+response[i].customer_name+'</td>\
					                       <td>'+response[i].serving_type+'</td>\
					                       <td>'+response[i].item_name+'</td>\
					                       <td>'+response[i].item_quantity+'</td>\
					                       <td>'+response[i].order_amount+'</td>\
					                       <td>'+response[i].order_status+'</td>\
					                       <td>'+response[i].order_time+'</td>\
					                       </tr>');
				}
			},
			error:function(){
				alert("No");
			}
		});
	}

	$('#restaurant-option').change(function(){
		if( $('#restaurant-option option:selected').val() != 'NULL') {
			var dat = "restid="+$('#restaurant-option option:selected').prop('id');
			loadDataAjax(dat);
			$('.dash-content-view-order main').removeClass('main-hide').addClass('main-show');
		} else {
			$('.dash-content-view-order main').removeClass('main-show').addClass('main-hide');
		}
	});
</script>