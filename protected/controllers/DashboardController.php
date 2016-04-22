<?php
/**
*
*/
class DashboardController extends Controller
{
	public $layout = 'dashboardlayout';

	public function accessRules() {
		return array(
           array('deny',
       					 'users'=>array('*'),
           ),
   	);
	}

	public function beforeAction ($action){
		if(Yii::app()->user->role == 2) {
			$this->layout = 'indexlayout';
			$this->render('//site/badpage');
			return false;
		} else {
			return true;
		}
	}

	public function actionIndex() {
		$this->render('dashboardindex');
	}

	public function actionAddRestaurant() {
		if (isset($_POST['id'])) {
			$subLoc = AvailableInLocation::model()->findAll(array('condition'=>'parent_location_id=:id','params'=>array(':id'=>$_POST['id'])));
			$response = array();
			$key = 0;
			foreach ($subLoc as $loc) {
				$response[$key]['id'] = $loc->id;
				$response[$key]['name'] = $loc->name;
				$key++;
			}
			echo json_encode($response);

		} else if(isset($_POST['restaurant_name'])) {
			$restaurant = new Restaurant;
			$restaurant->name = $_POST['restaurant_name'];
			$restaurant->location_id = $_POST['sub-location'];
			$restaurant->vendor_id = Yii::app()->user->id;
			$restaurant->mobile_number = $_POST['mobile'];
			$restaurant->street_address = $_POST['street_addr'];
			$restaurant->add_date = new CDbExpression('NOW()');
			$restaurant->modify_date = new CDbExpression('NOW()');
			if($restaurant->validate()) {
				$restaurant->save();
				echo json_encode(array('msg'=>'New restaurant has been added'));
			} else {
				echo json_encode(array('msg'=>'There were some errors'));
			}

		} else {
			$majorLoc = AvailableInLocation::model()->findAllByAttributes(array('parent_location_id'=>NULL));
			$this->render('add-restaurant',array('majorLoc'=>$majorLoc));
		}
	}

	public function actionAddCuisine() {
		if (isset($_POST['cuisine_name'])) {
			echo json_encode(array('msg'=>'YES'));
		} else {
			$restaurant = Restaurant::model()->with('location')->findAllByAttributes(array('vendor_id'=>Yii::app()->user->id));
			$this->render('add-cuisine',array('restaurant'=>$restaurant));
		}
	}

	public function actionEditRestaurant() {
		if(Yii::app()->user->role == 2) {
			$this->layout = 'indexlayout';
			$this->render('//site/badpage');
		} else {
			$this->render('add-restaurant');
		}
	}

	public function actionEditCuisine() {
		if(Yii::app()->user->role == 2) {
			$this->layout = 'indexlayout';
			$this->render('//site/badpage');
		} else {
			$this->render('edit-cuisine-home');
		}
	}

	public function actionUserAccountSettings() {
		if(Yii::app()->user->role == 2) {
			$this->layout = 'indexlayout';
			$this->render('//site/badpage');
		} else {
			$this->render('user-account-setting');
		}
	}
}