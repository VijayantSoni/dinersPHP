<header class="container-fluid payment">
	<div class="marginate">
		<div class="row">
			<h4>Complete your order</h4>
		</div>
		<form class="row" id="order-confirmation">
			<div class="select row">
				<select id="delivery" name="delivery-type" class="colGLG-12">
					<option value="0">Please select serving type</option>
					<option value="delivery">Delivery</option>
					<option value="pickup">Takeaway</option>
				</select>
			</div>
			<?php $address = CustomerAddressBook::model()->findByAttributes(array('customer_id'=>Yii::app()->user->id)); ?>
			<?php if(isset($address)): ?>
			<div class="addressbook hidden" id="<?php echo $address->id; ?>">
				<div class="row">
					<div class="colGLG-6 colGMD-6 colGSM-12">
						<input value="<?php echo $address->recipient_name; ?>" id="name" type="text" name="recipient_name" disabled="true" placeholder="Enter recipient name">
					</div>
					<div class="colGLG-6  colGMD-6 colGSM-12 push-right">
						<input value="<?php echo $address->recipient_mobile; ?>" id="mobile" type="text" name="recipient_mobile" disabled="true" placeholder="Enter recipient mobile">
					</div>
				</div>
				<div class="row">
					<div class="colGLG-12 colGMD-12 colGSM-12">
						<textarea id="address" name="recipient_address" disabled="true" placeholder="Enter recipient address"><?php echo $address->address; ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="colGLG-6 colGMD-6 colGSM-12 pull-left">
						<input type="radio" id="edit"></input>
						<label for="edit" class="colGLG-5 colGMD-6 colGSM-12">Edit</label>
					</div>
					<div class="colGLG-6 colGMD-6 colGSM-12 push-right">
						<button id="addr-save" name="save" class=" colGLG-5 colGMD-6 colGSM-12 push-right">Save</button>
					</div>
				</div>
			</div>
			<?php else: ?>
				<div class="addressbook hidden" id="<?php echo $address->id; ?>">
				<div class="row">
					<div class="colGLG-6 colGMD-6 colGSM-12">
						<input required id="name" type="text" name="recipient_name" disabled="true" placeholder="Enter recipient name">
					</div>
					<div class="colGLG-6  colGMD-6 colGSM-12 push-right">
						<input required id="mobile" type="text" name="recipient_mobile" disabled="true" placeholder="Enter recipient mobile">
					</div>
				</div>
				<div class="row">
					<div class="colGLG-12 colGMD-12 colGSM-12">
						<textarea required id="address" name="recipient_address" disabled="true" placeholder="Enter recipient address"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="colGLG-6 colGMD-6 colGSM-12 pull-left">
						<input type="radio" id="edit"></input>
						<label for="edit" class="colGLG-5 colGMD-6 colGSM-12">Edit</label>
					</div>
					<div class="colGLG-6 colGMD-6 colGSM-12 push-right">
						<button  id="addr-save" name="save" class=" colGLG-5 colGMD-6 colGSM-12 push-right">Save</button>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="order-details">
				<div class="row">
					<div class="colGLG-6">
						<p>Select time of your order</p>
						<input type="number" min="0" max="23" placeholder="HH" name="hour" required></input>
						<input type="number" min="0" max="59" placeholder="MM" name="min" required></input>
					</div>
					<div class="colGLG-6">
						<input id="amount" type="text" disabled="true" value='<?php echo $cartItem->item_cost." INR" ?>'>
					</div>
				</div>
			</div>
			<div class="select row">
				<select id="payment" name="payment-type" class="colGLG-12">
					<option value="0">Please select payment type</option>
					<option value="cash">Cash</option>
					<option value="credit">Credit Card</option>
				</select>
			</div>
			<div class="card-details hidden">
				<div class="row">
					<div class="colGLG-12">
						<input id="card" type="text" autocomplete="off" placeholder="Valid card number">
					</div>
				</div>
				<div class="row">
					<div class="colGLG-6">
						<input id="mm" type="number" min="1" max="12" placeholder="MM">
						<input id="yy" type="number" min="2010" max="2020" placeholder="YYYY">
					</div>
					<div class="colGLG-6">
						<input id="cvc" type="text" placeholder="CVC">
					</div>
				</div>
				<div class="row">
					<div class="colGLG-6 push-right">
						<button id="make-payment" class="colGLG-5 colGMD-6 colGSM-12 push-right">Make&nbsp;Payment</button>
					</div>
				</div>
			</div>
			<div class="confirm row">
				<div class="colGLG-6 push-right">
					<button id="confirm-order" class="push-right">Confirm&nbsp;Order</button>
				</div>
			</div>
		</form>
	</div>
</header>

<!-- ALL Scripts goes here -->
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
	Stripe.setPublishableKey('pk_test_1l7KXFLduMh42N8iY1b9HnDH');
</script>
<!-- <script src="js/navup.js"></script> -->
<script>
	function stripeResponseHandler(status, response) {
		if (response.error) {
			// re-enable the submit button
			$('#make-payment').html('Make Payment');
			$('#make-payment').removeAttr('disabled');
			// show the errors on the form
			$(".error").html(response.error.message);
			return false;
		}
		else {
			var token = response['id'];
			alert($('#order-confirmation').serialize()+'&token='+token);
			$.ajax({
				headers: {"Accept": "application/json"},
				type: 'POST',
				url: '<?php echo Yii::app()->createUrl("site/transaction");?>',
				data: $('#order-confirmation').serialize()+'&token='+token+'&allCheckout=1&amount='+$('#amount').val()+'&addressId='+$('.addressbook').prop('id'),
				success: function(data) {
					alert("Payment has been done and order has been placed");
					$('#make-payment').html('Make Payment');
					$('#make-payment').removeAttr('disabled');
				},
				error: function(data) {
					alert("Transaction failed");
					var res = $.parseJSON(data);
					alert(res.message);
					alert(res.error);
				}
			});
		}
	}
	$(document).ready(function() {
		$('#edit').click(function() {
			if($(this).is(':checked')) {
				$('#name').prop('disabled',false).addClass('active');
				$('#mobile').prop('disabled',false).addClass('active');
				$('#address').prop('disabled',false).addClass('active');
			}
		});
		$('#delivery').change(function() {
			if($('#delivery option:selected').val() == 'delivery') {
				$('.addressbook').removeClass('hidden');
			} else {
				$('.addressbook').addClass('hidden');
			}
		});
		$('#payment').change(function() {
			if($('#payment option:selected').val() == 'credit') {
				$('.confirm').addClass('hidden');
				$('.card-details').removeClass('hidden');
			} else {
				$('.confirm').removeClass('hidden');
				$('.card-details').addClass('hidden');
			}
		});
		$('#addr-save').click(function() {
			var dat = 'custId='+<?php echo Yii::app()->user->id; ?>+'&name='+$('#name').val()+'&mobile='+$('#mobile').val()+'&address='+$('#address').val();
			dat = dat.split(' ').join('+');
			$.ajax({
				type:'POST',
				url:'<?php echo Yii::app()->createUrl('site/updateAddress'); ?>',
				data:dat,
				success:function(data) {
					var response = $.parseJSON(data);
					alert(response.msg);
				},
				error:function() {
					alert("There have been some errors");
				}
			});
			return false;
		});

		$('#order-confirmation').submit(function(e){
			e.preventDefault();
			if($('#delivery').val() == 0) {
				alert("Please select delivery type");
			} else {
				if($('#payment').val() == 0) {
					alert("Please select a payment mode");
				} else {
					if($('#payment').val() == 'cash') {
						$.ajax({
							type:'POST',
							url:"<?php echo Yii::app()->createUrl('site/makeOrder'); ?>",
							data:$(this).serialize()+'&allCheck=1&addressId='+$('.addressbook').prop('id'),
							success:function(data) {
								alert(data);
							},
							error:function() {
								alert("Error");
							}
						})
					} else if($('#payment').val() == 'credit') {
						$('#make-payment').html('Please wait..');
						$('#make-payment').attr("disabled", "disabled");
						alert("Before");
						var error = false;
						if (!Stripe.card.validateCardNumber($('#card').val())) {
							error = true;
							$('#make-payment').html('Make Payment');
							$('#make-payment').removeAttr('disabled');
							alert('The credit card number appears to be invalid.');
						}

						// Validate the CVC:
						if (!Stripe.card.validateCVC($('#cvc').val())) {
							error = true;
							$('#make-payment').html('Make Payment');
							$('#make-payment').removeAttr('disabled');
							alert('The CVC number appears to be invalid.');
						}

						// Validate the expiration:
						if (!Stripe.card.validateExpiry($('#mm').val(), $('#yy').val())) {
							error = true;
							$('#make-payment').html('Make Payment');
							$('#make-payment').removeAttr('disabled');
							alert('The expiration date appears to be invalid.');
						}
						if(!error) {
							Stripe.createToken({
								number: $('#card').val(),
								cvc: $('#cvc').val(),
								exp_month: $('#mm').val(),
								exp_year: $('#yy').val()
							}, stripeResponseHandler);
						}
						alert(After);
						return false; // submit from callback
					}
				}
			}
			return false;
		})
	});
</script>
