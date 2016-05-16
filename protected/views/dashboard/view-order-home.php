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
	$(document).ready(function() {
		$(document).on('change','#status',function(e) {
			var parent = $(e.target);
			$.ajax({
				type: 'POST',
				url: '<?php echo Yii::app()->createUrl('dashboard/updatestatus'); ?>',
				data: 'orderstatusid='+parent.parent().prop('id')+'&status='+parent.val(),
				success: function(data) {
					alert(data);
				},
				error: function() {
					alert('Error updating status');
				}
			})
		})
	})
	function loadDataAjax(dat,restId=0) {
		var selectdata;
		$.ajax({
			type:'POST',
			url:"<?php echo Yii::app()->createUrl('dashboard/loadOrders');?>",
			data:dat,
			success:function(data){
				var response = $.parseJSON(data);
				if(response[0].status == 1) {
					$("#item-data").empty();
					for (var i = 0; i < response.length; i++) {
						selectdata = '<select id="status">\
														<option value="'+response[i].order_status_text.toLowerCase()+'" selected>'+response[i].order_status_text+'</option>\
														<option value="awaiting_payment">Awaiting Payment</option>\
														<option value="payment_done">Payment done</option>\
														<option value="awaiting_confirmation">Awaiting confirmation</option>\
														<option value="order_placed">Order Placed</option>\
														<option value="order_accepted">Order Accepted</option>\
														<option value="order_preparing">Order Preparing</option>\
														<option value="order_prepared">Order Prepared</option>\
														<option value="ready_for_pickup">Ready to pickup</option>\
														<option value="order_in_transit">Order In transit</option>\
														<option value="order_delivered">Order Delivered</option>\
														<option value="order_cancelled">Order Cancelled</option>\
														<option value="order_completed">Order Completed</option>\
													</select>';
						// 							alert(selectdata);
						// alert(response[i]);
						$("#item-data").append('<tr class="row">\
						                       <td class="colGLG-1">'+response[i].id+'</td>\
						                       <td>'+response[i].customer_name+'</td>\
						                       <td>'+response[i].serving_type+'</td>\
						                       <td>'+response[i].item_name+'</td>\
						                       <td>'+response[i].item_quantity+'</td>\
						                       <td>'+response[i].order_amount+'</td>\
						                       <td id='+response[i].order_status_id+'>'+selectdata+'</td>\
						                       <td>'+response[i].order_time+'</td>\
						                       </tr>');
					}
				} else {
					alert('Sorry no orders yet for this restaurant');
					$('#item-data').empty();
				}
			},
			error:function(){
				alert("No orders for this restaurant");
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