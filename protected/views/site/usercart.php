<header class="container-fluid cart">
	<div class="marginate">
		<div class="row">
			<h4>Cart</h4>
		</div>

		<form class="row">
			<table class="colGLG-12">
				<thead>
					<tr>
						<td>Item</td>
						<td>Shop</td>
						<td>Quantity</td>
						<td>Total Amount</td>
					</tr>
				</thead>
				<tbody id="cart-data">
				<?php $checkOutAmnt = 0; ?>
				<?php foreach($cartItems as $cartItem): ?>
					<tr>
						<td><?php echo $cartItem->item->name; ?></td>
						<td><?php echo $cartItem->item->restaurant->name; ?></td>
						<td><input type="number" value=<?php echo $cartItem->item_quantity;?>></td>
						<td>
							<i class="fa fa-inr"></i>
							&nbsp;<?php echo $cartItem->item->price; ?>&nbsp;&nbsp;&nbsp;&nbsp;
							<a onClick="trash($(this),event)" href="javascript:void(0);" id="<?php echo $cartItem->id; ?>">
								<i class="fa fa-trash"></i>
							</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="#">
								<i class="fa fa-check"></i>
							</a>
						</td>
					</tr>
					<?php $checkOutAmnt += $cartItem->item_cost; ?>
				<?php endforeach; ?>
				</tbody>

				<tfoot id="checkout-data">
					<tr>
						<td>
							Checkout Amount
						</td>
						<td>
							<i class="fa fa-inr"></i>&nbsp;&nbsp;<?php echo $checkOutAmnt; ?>
						</td>
						<td>
							<a href="#">Make&nbsp;Payment</a>
						</td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</header>


<!-- ALL Scripts goes here -->
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<!-- <script src="js/navup.js"></script> -->
<script>
	function appendChild(response) {
		$("#cart-data").empty();
		$("#checkout-data").empty();
		var amount = 0;
		for(var i = 0; i < response.length; i++) {
			$("#cart-data").append('<tr>\
										<td>'+response[i].name+'</td>\
										<td>'+response[i].restaurant+'</td>\
										<td><input type="number" value='+response[i].quantity+'></td>\
										<td>\
											<i class="fa fa-inr"></i>\
											&nbsp;'+response[i].price+'&nbsp;&nbsp;&nbsp;&nbsp;\
											<a onClick="trash($(this),event)" href="javascript:void(0);" id="'+response[i].id+'">\
												<i class="fa fa-trash"></i>\
											</a>\
											&nbsp;&nbsp;&nbsp;&nbsp;\
											<a href="#">\
												<i class="fa fa-check"></i>\
											</a>\
										</td>\
									</tr>'
								);
		amount += parseInt(response[i].price);
		}
		$("#checkout-data").append('<tr>\
										<td>\
											Checkout Amount\
										</td>\
										<td>\
											<i class="fa fa-inr"></i>&nbsp;&nbsp;'+amount+'\
										</td>\
										<td>\
											<a href="#">Make&nbsp;Payment</a>\
										</td>\
									</tr>');
	}
	function reloadAjax() {
		$.ajax({
			type:'POST',
			data:'reload=1',
			url:"<?php echo Yii::app()->createUrl('site/cart');?>",
			success:function(data) {
				var response = $.parseJSON(data);
				appendChild(response);
			},
			error:function() {
				alert("Could not reload data");
			}
		});
	}
	function trash(elem,e) {
		$.ajax({
			type:'POST',
			data:'trashId='+elem.prop("id"),
			url:"<?php echo Yii::app()->createUrl('site/cart');?>",
			success:function(data) {
				var response = $.parseJSON(data);
				alert(response.msg);
				reloadAjax();
			},
			error:function() {
				alert("Could not delete");
			}
		});
	}
	$("document").ready(function(){
	});
</script>