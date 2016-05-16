<main class="dash-index container-fluid">
	<header class="row">
		<?php $user = User::model()->findByPk(Yii::app()->user->id); ?>
		<h2>Hello <?php echo $user->first_name? $user->first_name.' '.$user->last_name : 'Vendor'; ?>!</h2>
		<h5>How about peeking a little over some orders today ?</h5>
	</header>
</main>