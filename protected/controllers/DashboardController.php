<?php
/**
*
*/
class DashboardController extends Controller {
	public $layout = 'dashboardlayout';

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
			$item = new Item;
			$item->restaurant_id = $_POST['restaurant-option'];
			$item->name = $_POST['cuisine_name'];
			$item->price = $_POST['cuisine_price'];
			$item->serving_time = $_POST['cuisine_time'];
			$item->details = $_POST['cuisine_details'];
			$item->pricing_detail = $_POST['cuisine_price_details'];
			$item->is_veg = $_POST['veg'];
			$item->is_spicy = $_POST['spicy'];
			$item->delivery_available = $_POST['delivery'];
			$item->add_date = new CDbExpression('NOW()');
			$item->modify_date = new CDbExpression('NOW()');

			if($item->validate()) {
				$item->save();
					echo json_encode(array('status'=>1,'msg'=>'Added a new item.'));
			} else {
				echo json_encode(array('status'=>2,'msg'=>'Data is incorrect.'));
			}
		} else {
			$restaurant = Restaurant::model()->with('location')->findAllByAttributes(array('vendor_id'=>Yii::app()->user->id));
			$this->render('add-cuisine',array('restaurant'=>$restaurant));
		}
	}

	public function actionEditRestaurant() {
		$this->render('add-restaurant');
	}

	public function actionEditCuisine() {
		if(isset($_POST['restid'])) {
			$items = Item::model()->findAllByAttributes(array('restaurant_id'=>$_POST['restid']));
			$response = array();
			$key = 0;
			foreach ($items as $item) {
				$response[$key]['id'] = $item->id;
				$response[$key]['name'] = $item->name;
				$key++;
			}
			echo json_encode($response);
		} else if(isset($_POST['itemTrashId'])) {
			$item = Item::model()->findByPk($_POST['itemTrashId']);
			$item->status = 0;
			$item->update();
			echo json_encode(array('status'=>2,'msg'=>'Item deleted successfully'));
		} else if(isset($_POST['itemEditId'])) {
			$item = Item::model()->findByPk($_POST['itemEditId']);
			$this->render('edit-cuisine',array('item'=>$item));
		} else {
			$restaurant = Restaurant::model()->findAll(array('condition'=>'vendor_id=:id','params'=>array(':id'=>Yii::app()->user->id)));
			$this->render('edit-cuisine-home',array('restaurant'=>$restaurant));
		}
	}

	public function actionUserAccountSettings() {
		$this->render('user-account-setting');
	}
}