
<header class="container-fluid payment">
	<div class="marginate">
		<div class="row">
			<h4>Complete your order</h4>
		</div>
		<form class="row" id="order-confirmation">
			<div class="select row">
				<select name="delivery-type" class="colGLG-12">
					<option>Please select serving type</option>
					<?php if($cartItem->item->delivery_available):?>
						<option value="delivery">Delivery</option>
						<option value="pickup">Takeaway</option>
					<?php else: ?>
						<option value="pickup">Takeaway</option>
					<?php endif;?>
				</select>
			</div>
			<div class="addressbook">
				<div class="row">
					<div class="colGLG-6">
						<input type="text" name="recipient_name">
					</div>
					<div class="colGLG-6 push-right">
						<input type="text" name="recipient_mobile">
					</div>
				</div>
				<div class="row">
					<div class="colGLG-12">
						<textarea name="recipient_address"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="colGLG-6 push-right">
						<button name="save" class="push-right">Save</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</header>