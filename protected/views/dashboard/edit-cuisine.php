<main class="dash-content-edit-cus container-fluid">
	<header class="row">
		<form class="row" method="post">
			<header class="colGLG-12 colGSM-12">
				<select id="restaurant-option" name="restaurant-option[opt]">
					<option value="NULL">Select Restaurant</option>
					<option value="SHB">Sahib Jee BAkers</option>
				</select>
			</header>
			<main class="main-show row">
				<figure class="colGLG-3 colGSM-12">
					<!-- <img src="#"> -->
					<i class="fa fa-user fa-5x"></i>
				</figure>
				<div class="colGLG-9 colGSM-12">
					<div class="row">
						<i class="fa fa-bookmark"></i>
						<input type="text" name="cuisine_name" placeholder="Cuisine's Name">
					</div>
					<div class="row">
						<i class="fa fa-inr"></i>
						<input type="text" name="cuisine_price" placeholder="Cuisine's Price">
					</div>
					<div class="row">
						<i class="fa fa-clock-o"></i>
						<input type="text" name="cuisine_time" placeholder="Serving Time">
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_details" placeholder="Cuisine Details | Example: 12 Piece/Plate"></textarea>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12">
					<div class="row">
						<i class="fa fa-bars"></i>
						<textarea name="cuisine_price_details" placeholder="Pricing Details | Example: 35 Small Plate, 40 Medium Plate"></textarea>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="veg" value="Veg" id="veg">
						<label for="veg" class="colGLG-6">Veg</label>
						<input type="radio" name="veg" value="Non-Veg" id="non-veg">
						<label for="non-veg" class="colGLG-6">Non-Veg</label>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="spicy" value="Spicy" id="spicy">
						<label for="spicy" class="colGLG-6">Spicy</label>
						<input type="radio" name="spicy" value="Non-Spicy" id="non-spicy">
						<label for="non-spicy" class="colGLG-6">Non-Spicy</label>
					</div>
				</div>
				<div class="colGLG-12 colGSM-12 radios">
					<div class="row">
						<input type="radio" name="delivery" id="yes_delivery" value="delivery">
						<label for="yes_delivery" class="colGLG-6">Deliverable</label>
						<input type="radio" name="delivery" id="no_delivery" value="takeaway">
						<label for="no_delivery" class="colGLG-6">Takeaway</label>
					</div>
				</div>
				<div class="colGLG-6 colGSM-12 push-right">
					<div class="row">
						<input type="submit" value="Save" name="add-cuisine">
					</div>
				</div>
			</main>
		</form>
	</header>
</main>