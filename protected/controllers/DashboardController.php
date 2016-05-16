<?php
/**
*
*/
class DashboardController extends Controller {
	public $layout = 'dashboardlayout';

	public function filters() {
		return array('accessControl');
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

	public function actionGetImage() {
		if(isset($_POST['rest'])) {
			$id = $_POST['id'];
			$rest = Restaurant::model()->findByAttributes(array('id'=>$id));
			echo  $rest->logo ? json_encode(array('url'=>$rest->logo)) : json_encode(array('url'=>null));
			// echo json_encode(array('url'=>$id));
		}
		else if(isset($_POST['cus'])) {

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
			$restaurant->logo = $_POST['logo'];
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
			$item->logo = $_POST['logo'];
			$item->restaurant_id = $_POST['restaurant-option'];
			$item->category_id = $_POST['category'];
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
			$categories = Category::model()->findAllByAttributes(array('status'=>1));
			$this->render('add-cuisine',array('restaurant'=>$restaurant,'categories'=>$categories));
		}
	}

	public function actionEditRestaurant() {
		if(isset($_POST['restId'])) {
			$restaurant = Restaurant::model()->with('location','location.parentLocation')->findByAttributes(array('id'=>$_POST['restId']));
			echo json_encode(array('logo'=>$restaurant->logo, 'id'=>$restaurant->id,'name'=>$restaurant->name,'parent_location_id'=>$restaurant->location->parentLocation->id,'parent_location_name'=>$restaurant->location->parentLocation->name,'sub_location_id'=>$restaurant->location->id,'sub_location_name'=>$restaurant->location->name,'contact'=>$restaurant->mobile_number,'address'=>$restaurant->street_address));

		} else if(isset($_POST['restaurant-option'])) {
			$res = Restaurant::model()->findByAttributes(array('id'=>$_POST['restaurant-option']));
			$res->logo = $_POST['logo'];
			$res->name = $_POST['restaurant_name'];
			$res->mobile_number = $_POST['mobile'];
			$res->street_address = $_POST['street_addr'];
			$res->modify_date = new CDbExpression('NOW()');
			if($res->validate()) {
				$res->save();
				echo json_encode(array('status'=>1,'msg'=>'Restaurant details updated'));
			}

		} else {
			$restaurant = Restaurant::model()->with('location')->findAllByAttributes(array('vendor_id'=>Yii::app()->user->id,'status'=>1));
			$this->render('edit-restaurant-home',array('restaurant'=>$restaurant));
		}
	}

	public function actionEditCuisine() {
		if(isset($_POST['restid'])) {
			$items = Item::model()->findAllByAttributes(array('restaurant_id'=>$_POST['restid'],'status'=>1));
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
			echo json_encode(array('url'=>Yii::app()->createUrl('dashboard/editCuisineForm',array('id'=>$_POST['itemEditId']))));
		} else {
			$restaurant = Restaurant::model()->findAll(array('condition'=>'vendor_id=:id','params'=>array(':id'=>Yii::app()->user->id)));
			$this->render('edit-cuisine-home',array('restaurant'=>$restaurant));
		}
	}

	public function actionEditCuisineForm($id=0) {
		if(isset($_POST['cuisine_name']) && $id != 0) {
			$item = Item::model()->findByPk($id);
			if(!empty($item)) {
				$item->logo = $_POST['logo'];
				$item->name = $_POST['cuisine_name'];
				$item->category_id = $_POST['category'];
				$item->price = $_POST['cuisine_price'];
				$item->serving_time = $_POST['cuisine_time'];
				$item->details = $_POST['cuisine_details'];
				$item->pricing_detail = $_POST['cuisine_price_details'];
				$item->is_veg = $_POST['veg'];
				$item->is_spicy = $_POST['spicy'];
				$item->delivery_available = $_POST['delivery'];
				$item->modify_date = new CDbExpression('NOW()');
				if($item->validate()) {
					$item->update();
					echo json_encode(array('status'=>1,'msg'=>'Item updated'));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Form filled incorrectly'));
				}
			} else {
				json_encode(array('status'=>2,'msg'=>'Item not found'));
			}
		} else if ($id != 0) {
			$item = Item::model()->findByPk($_GET['id']);
			$this->render('edit-cuisine',array('item'=>$item));
		}
	}

	public function actionUserAccountSettings() {
		if(isset($_POST['user_email'])) {
			$user = User::model()->findByPk(Yii::app()->user->id);
			$user->first_name = $_POST['first_name'];
			$user->last_name = $_POST['last_name'];
			$user->email = $_POST['user_email'];
			$user->profile_image = $_POST['profile_image'];
			$user->mobile_number = $_POST['user_mobile'];
			if(isset($_POST['user_pass']) && isset($_POST['user_confirm_pass'])) {
				$user->password = base64_encode($_POST['user_confirm_pass']);
			}
			if($user->validate()) {
				$user->update();
				echo json_encode(array('status'=>1,'msg'=>'Profile updated'));
			} else {
				echo json_encode(array('status'=>2,'msg'=>'Incorrect data'));
			}

		} else {
			$user = User::model()->findByPk(Yii::app()->user->id);
			$this->render('user-account-setting',array('user'=>$user));
		}
	}

	public function actionViewOrders() {
		// $orders = Orders::model()->with('package.item','orderStatuses','restaurant')->findAllByAttributes(array('customer_id'=>Yii::app()->user->id,'status'=>1));
		$restaurants = Restaurant::model()->findAllByAttributes(array('vendor_id'=>Yii::app()->user->id,'status'=>1));
		// CVarDumper::dump($orders,10,1); die;
		$this->render('view-order-home',array('restaurants'=>$restaurants));
	}

	public function actionLoadOrders() {
		if(isset($_POST['restid'])) {
			$orders = Orders::model()->with('package.item','orderStatuses','restaurant','customer')->findAllByAttributes(array('restaurant_id'=>$_POST['restid'],'status'=>1));
			$key = 0;
			foreach($orders as $order) {
				$response[$key]['id'] = $order->id;
				$response[$key]['customer_name'] = $order->customer->first_name." ".$order->customer->last_name;
				$response[$key]['serving_type'] = $order->serving_type;
				$response[$key]['item_name'] = $order->package->item->name;
				$response[$key]['item_quantity'] = $order->package->item_quantity;
				$response[$key]['order_amount'] = $order->amount;
				$response[$key]['order_status'] = strtoupper($order->orderStatuses[0]->order_status);
				$response[$key]['order_time'] = $order->time_for_pickup?$order->time_for_pickup:$order->time_for_delivery;
				$key++;
			}
			echo json_encode($response);
		}
	}
}