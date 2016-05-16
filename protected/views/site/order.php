<header class="container-fluid orders">
	<div class="marginate">
		<div class="row">
			<h4>Orders</h4>
		</div>
		<div class="row">
			<?php foreach($orders as $order): ?>
			<div class="row order-box" style="margin-bottom: 1em;">
				<header class="row">
					<div class="colGLG-6">
						<h4>Order ID : <span><?php echo "OD2016".$order->id; ?></span></h4>
					</div>
					<div class="colGLG-6">
						<h4>Date : <span><?php echo split(" ", $order->add_date)[0]; ?></span></h4>
					</div>
				</header>
				<section class="row">
					<figure class="colGLG-3 colGSM-12">
						<img src="<?php echo $order->package->item->logo ? $order->package->item->logo : Yii::app()->request->baseUrl."/img/sampleFood.jpg"; ?>" style="max-width: 100%;">
					</figure>
					<div class="colGLG-4 colGSM-12">
						<p><?php echo $order->package->item->name; ?></p>
						<h6>Qty - <span><?php echo $order->package->item_quantity; ?></span></h6>
					</div>
					<div class="colGLG-5 colGSM-12">
						<p>Status : <span><?php echo strtoupper($order->orderStatuses[0]->order_status); ?></span></p>
						<p>Pickup Time : <span><?php echo $order->time_for_pickup?$order->time_for_pickup:$order->time_for_delivery; ?></span></p>
					</div>
				</section>
				<footer class="row">
					<div class="colGLG-6">
						<p>Seller : <span><?php echo $order->restaurant->name; ?></span></p>
					</div>
					<div class="colGLG-6">
						<p>Amount : <span><i class="fa fa-inr"></i>&nbsp;<?php echo $order->amount; ?></span></p>
					</div>
				</footer>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</header>


<!-- ALL Scripts goes here -->
<script src="js/jquery-2.1.1.js"></script>
<!-- <script src="js/navup.js"></script> -->
<script>
	$("document").ready(function(){
		if($("#edit").is(":checked")) {
			$("#edit").prop("checked",false);
		}
		if($("#save").is(":checked")) {
			$("#save").prop("checked",false);
		}
		if($("#delete").is(":checked")) {
			$("#delete").prop("checked",false);
		}
		$("#recipient_name").prop("disabled",true);
		$("#recipient_mobile").prop("disabled",true);
		$("#recipient_addr").prop("disabled",true);
	});

	$("#edit").click(function(){
		if ($("#edit").is(":checked")) {
			$("#recipient_name").addClass("enable");
			$("#recipient_name").prop("disabled",false);

			$("#recipient_mobile").addClass("enable");
			$("#recipient_mobile").prop("disabled",false);

			$("#recipient_addr").addClass("enable");
			$("#recipient_addr").prop("disabled",false);
		}
	});
</script>