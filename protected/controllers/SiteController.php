<?php
Yii::import('application.vendors.*');
require_once('stripe/init.php');
class SiteController extends Controller
{
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	protected function beforeAction($action) {
		if(Yii::app()->user->isGuest) return true;
		else if(Yii::app()->user->role == 3){
			if($action->id == 'logout') {
				return true;
			} else {
				$this->layout = 'dashboardlayout';
				$this->render('//dashboard/badpage');
				return false;
			}
		}
		return parent::beforeAction($action);
	}

	public function actionContact() {
		if(isset($_POST['name'])) {
			$this->sendContactEmail(array('name'=>$_POST['name'],
			                              'mobile'=>$_POST['mobile'],
			                              'subject'=>$_POST['subject'],
			                              'matter'=>$_POST['matter']));
			$this->render('contactSuccess');
		} else {
			$this->render('badpage');
		}
	}

	protected function sendContactEmail($attributes) {
		$to='nishaakashona@live.com';
		$from="teamdiners@gmail.com";
		$from_name=$attributes['name'];
		$subject=$attributes['subject'];
		$message = $attributes['matter'];
		$this->mailsend($to,$from,$from_name,$subject,$message);
	}

	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$locations = AvailableInLocation::model()->findAllByAttributes(array('status'=>1,'parent_location_id'=>NULL));
		$this->render('index',array('locations'=>$locations));
	}

	public function actionViewPage() {
		if(isset($_GET['about'])) $this->render('aboutus');
		else if(isset($_GET['partners'])) $this->render('partners');
		else if(isset($_GET['contact'])) $this->render('contact');
		else $this->render('badpage');
	}

	public function actionSetLocation() {
		if(isset($_POST['locId'])) {
			Yii::app()->user->setState('location_id',$_POST['locId']);
			Yii::app()->user->setState('location_name',$_POST['locName']);
			echo json_encode(array('msg'=>'Location set successfully'));
		}
	}

	protected function getLocation() {
		return array('id'=>Yii::app()->user->location_id,'name'=>Yii::app()->user->location_name);
	}

	public function actionSearch() {
		if(isset($_POST['location-id'])) {
			echo json_encode(array('url'=>Yii::app()->createUrl('site/search').'?location='.$_POST['location-id'].'&query='.$_POST['query']));

		} else if(isset($_GET['query'])) {
			$criteria = new CDbCriteria;
			$criteria->with = array('restaurant.location.parentLocation');
			$criteria->condition = "t.name like '%".$_GET["query"]."%' or t.details like '%".$_GET["query"]."%' AND t.status=1";
			$items = Item::model()->with('restaurant')->findAll($criteria);
			$newItems = array();
			foreach ($items as $item) {
				if($item->restaurant->location->parent_location_id == $_GET['location']) {
					array_push($newItems, $item);
				}
			}
			$crit = new CDbCriteria;
			$crit->condition = "t.name like '%".$_GET["query"]."%' AND t.status=1";
			$res = Restaurant::model()->with('location')->findAll($crit);
			foreach ($res as $r) {
				if($r->location->parent_location_id == $_GET['location']) {
					array_push($newItems, $r);
				}
			}
			$itemarray = array();
			foreach($newItems as $item) {
				if($item->hasRelated('restaurant')) {
					array_push($itemarray, array('item'=>1,'logo'=>$item->logo,'id'=>$item->id,'name'=>$item->name,'time'=>$item->serving_time,'deliverable'=>$item->delivery_available,'price'=>$item->price,'rest_name'=>$item->restaurant->name));
				} else if($item->hasRelated('location')){
					array_push($itemarray,array('restaurant'=>1,'logo'=>$item->logo,'id'=>$item->id,'name'=>$item->name,'adddr'=>$item->street_address,'mainlocation'=>$item->location->parentLocation->name,'sublocation'=>$item->location->name));
				}
			}
			$tempLoc = AvailableInLocation::model()->findByPk($_GET['location']);
			$location = array('id'=>$_GET['location'],'name'=>$tempLoc->name);
			$this->render('searchresults',array('items'=>$itemarray,'query'=>$_GET['query'],'location'=>$location));

		} else if(isset($_GET['restaurant'])) {
			$restaurant = Restaurant::model()->with('location')->findAllByAttributes(array('status'=>1));
			$resArray = array();
			foreach($restaurant as $rest) {
				if($rest->location->parent_location_id == $_GET['locId'])
					array_push($resArray,array('restaurant'=>1,'logo'=>$rest->logo,'id'=>$rest->id,'name'=>$rest->name,'adddr'=>$rest->street_address,'mainlocation'=>$rest->location->parentLocation->name,'sublocation'=>$rest->location->name));
			}
			$this->render('searchresults',array('items'=>$resArray,'query'=>0,'location'=>$this->getLocation()));

		} else if (isset($_GET['item'])) {
			$items = Item::model()->with('restaurant')->findAllByAttributes(array('status'=>1));
			$itemarray = array();
			foreach ($items as $item) {
				if($item->restaurant->location->parent_location_id == $_GET['locId']) {
					array_push($itemarray, array('item'=>1,'logo'=>$item->logo,'id'=>$item->id,'name'=>$item->name,'time'=>$item->serving_time,'deliverable'=>$item->delivery_available,'price'=>$item->price,'rest_name'=>$item->restaurant->name));
				}
			}
			$this->render('searchresults',array('items'=>$itemarray,'query'=>0,'location'=>$this->getLocation()));
		}
	}

	public function actionFilter() {
		if(isset($_POST['filter']) && $_POST['categoryIds'] != 0) {
			$catIdArray = $_POST['categoryIds'];
			$criteria = new CDbCriteria;
			$criteria->with = array('restaurant.location.parentLocation');
			$criteria->condition = "t.name like '%".$_POST["query"]."%' or t.details like '%".$_POST["query"]."%' AND t.status=1";
			if($_POST['price'] == 1) {
				$criteria->order = "price ASC";
			}
			if($_POST['time'] == 1) {
				$criteria->order = "serving_time ASC";
			}
			$items = Item::model()->with('restaurant')->findAll($criteria);
			$newItems = array();
			foreach ($items as $item) {
				if($item->restaurant->location->parent_location_id == $_POST['locId']) {
					foreach ($_POST['categoryIds'] as $id) {
						if($id == $item->category_id) {
							array_push($newItems, array('id'=>$item->id,
					                            'name'=>$item->name,
					                            'price'=>$item->price,
					                            'servingTime'=>$item->serving_time,
					                            'deliverable'=>$item->delivery_available,
					                            'restName'=>$item->restaurant->name));
						}
					}
				}
			}
			echo json_encode($newItems);
		} else if(isset($_POST['filter'])) {
			$catIdArray = $_POST['categoryIds'];
			$criteria = new CDbCriteria;
			$criteria->with = array('restaurant.location.parentLocation');
			$criteria->condition = "t.name like '%".$_POST["query"]."%' or t.details like '%".$_POST["query"]."%' AND t.status=1";
			if($_POST['price'] == 1) {
				$criteria->order = "price ASC";
			}
			if($_POST['time'] == 1) {
				$criteria->order = "serving_time ASC";
			}
			$items = Item::model()->with('restaurant')->findAll($criteria);
			$newItems = array();
			foreach ($items as $item) {
				if($item->restaurant->location->parent_location_id == $_POST['locId']) {
					array_push($newItems, array('id'=>$item->id,
			                            'name'=>$item->name,
			                            'price'=>$item->price,
			                            'servingTime'=>$item->serving_time,
			                            'deliverable'=>$item->delivery_available,
			                            'restName'=>$item->restaurant->name));
				}
			}
			echo json_encode($newItems);
		}
	}

	public function actionLogin() {
		$model=new LoginForm;
		// collect user input data
		if(isset($_POST['username']) && isset($_POST['password']))
		{
			$model->username = $_POST['username'];
			$model->password = base64_encode($_POST['password']);
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				echo json_encode(array('status'=>1,'role'=>Yii::app()->user->role,'msg'=>'Logged in successfully'));
			else
				echo json_encode(array('status'=>2,'msg'=>'Unsuccessfull attempt'));
		}
	}

	public function actionSignup() {
		$newUser = new User;
		if(isset($_POST['mobile'])) {
			$newUser->mobile_number = $_POST['mobile'];
			$newUser->email = $_POST['email'];
			$newUser->role_id = $_POST['role'];
			$newUser->password = base64_encode($_POST['password']);
			$newUser->is_verified = 0;
			$newUser->email_verification_hash=md5(uniqid(rand(1,1000)));
			$newUser->otp_verification = mt_rand(111111,999999);
			$newUser->add_date = new CDbExpression('NOW()');
			$newUser->modify_date = new CDbExpression('NOW()');

			$email_exist = User::model()->find(array('condition'=>'email=:email','params'=>array(':email'=>$newUser->email)));
			if(empty($email_exist)) {
				$mobile_exist = User::model()->find(array('condition'=>'mobile_number=:mobile','params'=>array(':mobile'=>$newUser->mobile_number)));
				if (empty($mobile_exist)) {
					if($newUser->validate() && $newUser->save()) {
						if($newUser->role_id == 2) {
							$userCart = new ShoppingCart;
							$userCart->customer_id = $newUser->id;
							$userCart->status = 1;
							$userCart->add_date = new CDbExpression('NOW()');
							$userCart->modify_date = new CDbExpression('NOW()');
							$userCart->save();
						}
						$this->sendVerificationSMSOnSignUp($newUser);
						$this->sendVerificationEmailOnSignUP($newUser);
						$response['status'] = 1;
						$response['msg'] = "User successfully registered. Please verify your email.";
						$response['id'] = $newUser->id;
						echo json_encode($response);
					} else {
						$response['status'] = 2;
						$response['msg'] = "Something went wrong. Please try again.";
						echo json_encode($response);
					}
				} else {
					$response['status'] = 3;
					$response['msg'] = "Mobile number already registered.";
					echo json_encode($response);
				}
			} else {
				$response['status'] = 3;
				$response['msg'] = "Email already registered.";
				echo json_encode($response);
			}
		}
	}

	protected function sendVerificationEmailOnSignUP($newUser) {
		$to=$newUser->email;
		$from="himanshu.singh@venturepact.com";
		$from_name="Admin DinersMeet";
		$subject="Verify your Email";

		$url = Yii::app()->createUrl('site/verify',array('email'=>$newUser->email,'hash'=>$newUser->email_verification_hash));
		$url ="localhost".$url;
		$message="Hey !<br><br><br>";
		$message.="Thank you for trusting us.<br><br>";
		$message .="<a href=".$url."><button style='background:#f07762;color:white;width:200px;height:30px'>Verify Your Email</button></a><br><br><br>";
		$message.="Regards,<br>";
		$message.="DinersMeet Support Team.";
		$this->mailsend($to,$from,$from_name,$subject,$message);
	}

	protected function sendVerificationSMSOnSignUp($newUser) {
		$to = '+91'.$newUser->mobile_number;
		$text = "Please use ".$newUser->otp_verification." to verify your account. Regards Team DinersMeet";
		$this->sendSMS($to,$text);
	}

	public function actionVerify() {
		$user=new User;
		if(isset($_GET['email'])&&isset($_GET['hash'])) {
			$email=$_GET['email'];
			$hash=$_GET['hash'];
			$user=User::model()->find(array('condition'=>"email='$email' AND email_verification_hash='$hash'"));
			if(!empty($user)) {
				$user->is_verified=1;
				$user->email_verification_hash=null;
				$user->otp_verification = null;
				$user->modify_date = new CDbExpression('NOW()');
				if($user->update()) {
					$this->render('verifications',array('status'=>1));
				}
			} else {
				$this->render('verifications',array('status'=>2));
			}

		} else if(isset($_GET['verifyid'])) {
			echo json_encode(array('url'=>Yii::app()->createUrl('site/verify',array('sms'=>$_GET['verifyid']))));

		} else if(isset($_GET['sms'])) {
			$this->render('smsverify',array('id'=>$_GET['sms']));

		} else if(isset($_POST['otp'])) {
			$user = User::model()->findByPk($_POST['id']);
			if(!empty($user)) {
				if($user->otp_verification == $_POST['otp']) {
					$user->is_verified=1;
					$user->email_verification_hash=null;
					$user->otp_verification = null;
					$user->modify_date = new CDbExpression('NOW()');
					if($user->update()) {
						echo json_encode(array('status'=>1,'msg'=>'Verified','url'=>Yii::app()->createUrl('site/index')));
					}
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Wrong Otp Please try again'));
				}
			} else {
				echo json_encode(array('status'=>2,'msg'=>'No such user found'));
			}

		} else {
			$this->render('badpage');
		}
	}

	public function actionForgotPassword() {
		if(isset($_POST['email'])) {
			$forgot = new ForgotpasswordForm;
			$forgot->email = $_POST['email'];
			if($forgot->validate()) {
				$user = User::model()->find(array('condition'=>'email=:email','params'=>array(':email'=>$forgot->email)));
				if(!empty($user)) {
					$user->email_verification_hash = md5(uniqid(rand(1,1000)));
					$user->modify_date = new CDbExpression('NOW()');
					$user->update();
					try{
						$this->sendVerificationEmailOnReset($user);
					} catch(Exception $e) {
						echo json_encode(array('status'=>4,'msg'=>$e->getMessage()));
					}
					echo json_encode(array('status'=>1,'msg'=>'A mail has been sent to your email with further steps.'));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Sorry there is no user present with that email.'));
				}
			} else {
				echo json_encode(array('status'=>3,'msg'=>'Email seems to be incorrect.'));
			}
		}
		else
			$this->render('forgotpassword');
	}

	public function sendVerificationEmailOnReset($user) {
		$to=$user->email;
		$from="himanshu.singh@venturepact.com";
		$from_name="Admin DinersMeet";
		$subject="Reset you password";

		$url = Yii::app()->createUrl('site/ResetPassword',array('email'=>$user->email,'hash'=>$user->email_verification_hash));
		$url ="localhost".$url;
		$message="Hey !<br><br><br>";
		$message.="Please click on the link below to reset your password.<br><br>";
		$message .="<a href=".$url."><button style='background:#f07762;color:white;width:200px;height:30px'>Reset Password</button></a><br><br><br>";
		$message.="Regards,<br>";
		$message.="DinersMeet Support Team.";
		$this->mailsend($to,$from,$from_name,$subject,$message);
	}

	public function actionResetPassword() {
		if(isset($_GET['email']) && isset($_GET['hash'])) {
			$user = User::model()->find(array('condition'=>'email=:email','params'=>array(':email'=>$_GET['email'])));
			if($user->email_verification_hash == $_GET['hash']) {
				$this->render('resetpassword');
			} else {
				$this->render('resetpassworderror');
			}
		} else if(isset($_POST['password']) && isset($_POST['confirm-password']) && isset($_GET['email'])) {
			$user = User::model()->findByAttributes(array('email'=>$_GET["email"]));
			$user->password = base64_encode($_POST['password']);
			$user->email_verification_hash = NULL;
			$user->update();
			echo json_encode(array('status'=>1,'msg'=>'Your password has been updated.'));
		}
	}

	public function actionAddToCart() {
		if(isset($_POST['itemId'])) {
			$item = Item::model()->with('restaurant')->findByPk($_POST['itemId']);
			$user = User::model()->with('shoppingCarts')->findByPk(Yii::app()->user->id);
			$prevItem = ShoppingCartHasItems::model()->findByAttributes(array('item_id'=>$item->id,'shopping_cart_id'=>$user->shoppingCarts[0]->id,'status'=>1));
			if(empty($prevItem)) {
				$cartItem = new ShoppingCartHasItems;
				$cartItem->item_id = $item->id;
				$cartItem->shopping_cart_id = $user->shoppingCarts[0]->id;
				$cartItem->item_quantity = 1;
				$cartItem->item_cost = $item->price;
				$cartItem->add_date = new CDbExpression('NOW()');
				$cartItem->modify_date = new CDbExpression('NOW()');
				if($cartItem->validate()) {
					$cartItem->save();
					echo json_encode(array('status'=>1,'msg'=>'Added to cart'));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Could not add'));
				}
			} else {
				$prevItem->item_quantity += 1;
				$prevItem->item_cost = $item->price * $prevItem->item_quantity;
				$prevItem->update();
				echo json_encode(array('status'=>1,'msg'=>'Cart updated'));
			}
		}
	}

	public function actionCart() {
		if(isset($_POST['trashId'])) {
			$cartItem = ShoppingCartHasItems::model()->findByPk($_POST['trashId']);
			if(!empty($cartItem)) {
				$cartItem->status = 0;
				$cartItem->update();
				echo json_encode(array('status'=>1,'msg'=>'Deleted successfully'));
			} else {
				echo json_encode(array('status'=>2,'msg'=>'No such item found'));
			}
		} else if(isset($_POST['reload'])) {
			$user = User::model()->with('shoppingCarts')->findByPk(Yii::app()->user->id);
			$cartItems = ShoppingCartHasItems::model()->with('item')->findAllByAttributes(array('shopping_cart_id'=>$user->shoppingCarts[0]->id,'status'=>1));
			$i=0;
			if(!empty($cartItems)) {
				foreach ($cartItems as $cartItem) {
					$response[$i]['id'] = $cartItem->id;
					$response[$i]['url'] = Yii::app()->createUrl('site/makepayment',array('cartItemId'=>$cartItem->id))."" ;
					$response[$i]['name'] = $cartItem->item->name;
					$response[$i]['price'] = $cartItem->item_cost;
					$response[$i]['quantity'] = $cartItem->item_quantity;
					$response[$i]['restaurant'] = $cartItem->item->restaurant->name;
					$i++;
				}
				echo json_encode($response);
			} else {
				echo json_encode(array('msg'=>'Cart is empty'));
			}
		} else if(isset($_POST['cartItemId'])) {
				$cartItem = ShoppingCartHasItems::model()->with('item')->findByPk($_POST['cartItemId']);
				$cartItem->item_quantity = $_POST['quantity'];
				$cartItem->item_cost = $cartItem->item_quantity * $cartItem->item->price;
				$cartItem->modify_date = new CDbExpression('NOW()');
				if($cartItem->update()) {
					echo json_encode(array('status'=>1,'msg'=>'Updated'));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Error while updating'));
				}
		} else {
			$user = User::model()->with('shoppingCarts')->findByPk(Yii::app()->user->id);
			$cartItems = ShoppingCartHasItems::model()->with('item')->findAllByAttributes(array('shopping_cart_id'=>$user->shoppingCarts[0]->id,'status'=>1));
			$this->render('usercart',array('user'=>$user,'cartItems'=>$cartItems));
		}
	}

	public function actionProfile() {
		if(isset($_POST['first_name'])) {
			$user = User::model()->findByPk(Yii::app()->user->id);
			$user->profile_image = $_POST['profile_image'];
			$user->first_name = $_POST['first_name'];
			$user->last_name = $_POST['last_name'];
			$user->email = $_POST['email'];
			$user->mobile_number = $_POST['mobile'];
			$user->password = base64_encode($_POST['confirm_password']);

			if($user->validate()) {
				$user->update();
				if(isset($_POST['recipient_name']) && isset($_POST['recipient_mobile']) && isset($_POST['recipient_addr'])) {
					$prevAddress = CustomerAddressBook::model()->findByAttributes(array('customer_id'=>Yii::app()->user->id));
					if(empty($prevAddress)) {
						$addressBook = new CustomerAddressBook;
						$addressBook->customer_id = Yii::app()->user->id;
						$addressBook->recipient_name = $_POST['recipient_name'];
						$addressBook->recipient_mobile = $_POST['recipient_mobile'];
						$addressBook->address = $_POST['recipient_addr'];
						$addressBook->status = 1;
						$addressBook->add_date = new CDbExpression('NOW()');
						$addressBook->modify_date = new CDbExpression('NOW()');
						if($addressBook->validate()) {
							$addressBook->save();
							echo json_encode(array('status'=>1,'msg'=>'Profile updated'));
						} else {
							echo json_encode(array('status'=>2,'msg'=>'Sorry form has errors'));
						}
					} else {
						$prevAddress->recipient_name = $_POST['recipient_name'];
						$prevAddress->recipient_mobile = $_POST['recipient_mobile'];
						$prevAddress->address = $_POST['recipient_addr'];
						$prevAddress->status = 1;
						$prevAddress->modify_date = new CDbExpression('NOW()');
						if($prevAddress->validate()) {
							$prevAddress->save();
							echo json_encode(array('status'=>1,'msg'=>'Profile updated'));
						} else {
							echo json_encode(array('status'=>2,'msg'=>'Sorry form has errors'));
						}
					}
				} else {
					echo json_encode(array('status'=>3,'msg'=>'User updated without address'));
				}
			} else {
				echo json_encode(array('status'=>2,'msg'=>'Sorry form has errors.'));
			}
		} else {
			$user = User::model()->with('customerAddressBooks')->findByPk(Yii::app()->user->id);
			$this->render('userprofile',array('user'=>$user));
		}
	}

	public function actionCheckout() {
		if(isset($_GET['cartItemId'])) {
			$cartItem = ShoppingCartHasItems::model()->findByPk($_GET['cartItemId']);
			$this->render('makepayment',array('cartItem'=>$cartItem));
		} else if(isset($_POST['itemId'])) {
			$prevItem = ShoppingCartHasItems::model()->findByAttributes(array('item_id'=>$_POST['itemId'],'status'=>1));
			$cart = ShoppingCart::model()->findByAttributes(array('customer_id'=>Yii::app()->user->id,'status'=>1));
			$item = Item::model()->findByPk($_POST['itemId']);
			if(empty($prevItem)) {
				$cartItem = new ShoppingCartHasItems;
				$cartItem->item_id = $_POST['itemId'];
				$cartItem->shopping_cart_id = $cart->id;
				$cartItem->item_quantity = 1;
				$cartItem->item_cost = $item->price;
				$cartItem->add_date = new CDbExpression('NOW()');
				$cartItem->modify_date = new CDbExpression('NOW()');
				if($cartItem->validate()) {
					$cartItem->save();
					echo json_encode(array('status'=>1,'msg'=>'Added to cart'));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Could not add'));
				}
			} else {
				$prevItem->item_quantity += 1;
				$prevItem->item_cost = $item->price * $prevItem->item_quantity;
				$prevItem->update();
				echo json_encode(array('status'=>1,'msg'=>'Cart updated'));
			}
		}
	}

	public function actionMakePayment() {
		if(isset($_GET['cartItemId'])) {
			$cartItem = ShoppingCartHasItems::model()->with('item')->findByPk($_GET['cartItemId']);
			$this->render('makepayment',array('cartItem'=>$cartItem));
		} else if(isset($_POST['allCheckout'])) {
			echo json_encode(array('url'=>Yii::app()->createUrl('site/makepayment',array('allCheckout'=>1))));
		} else if(isset($_GET['allCheckout'])) {
			$this->render('allcheckout',array('cartItem'=>0));
		}
	}

	public function actionMakeOrder() {
		if(isset($_POST['delivery-type']) && isset($_POST['payment-type']) && isset($_POST['allCheck'])) {
			$cart = ShoppingCart::model()->findByAttributes(array('customer_id'=>Yii::app()->user->id));
			$cartItems = ShoppingCartHasItems::model()->with('item','item.restaurant')->findAllByAttributes(array('shopping_cart_id'=>$cart->id,'status'=>1));
			foreach($cartItems as $cartItem) {
				$order = new Orders;
				$order->customer_id = Yii::app()->user->id;
				$order->package_id = $cartItem->id;
				$order->restaurant_id = $cartItem->item->restaurant->id;
				$order->delivery_address_id = $_POST['delivery-type'] == 'delivery'?$_POST['addressId']:NULL;
				$order->amount = $cartItem->item_cost;
				if($_POST['delivery-type'] == 'pickup') {
					$order->serving_type = 'pickup';
				} else {
					if($_POST['delivery-type'] == 'delivery' && $cartItem->item->delivery_available) {
						$order->serving_type = 'delivery';
					} else {
						$order->serving_type = 'pickup';
					}
				}

				if($_POST['delivery-type'] == 'pickup') {
					$order->time_for_pickup = $_POST['hour'].":".$_POST['min'];
				} else {
					if($_POST['delivery-type'] == 'delivery' && $cartItem->item->delivery_available) {
						$order->time_for_delivery = $_POST['hour'].":".$_POST['min'];
					}
					else {
						$order->time_for_pickup = $_POST['hour'].":".$_POST['min'];
					}
				}
				$order->add_date = new CDbExpression('NOW()');
				$order->modify_date = new CDbExpression('NOW()');
				if($order->validate()) {
					$order->save();
					$cartItem->status=0;
					$cartItem->update();
					$orderStatus = new OrderStatus;
					$orderStatus->order_id = $order->id;
					$orderStatus->order_status = 'awaiting_confirmation';
					$orderStatus->add_date = new CDbExpression('NOW()');
					$orderStatus->modify_date = new CDbExpression('NOW()');
					$orderStatus->save();
					echo json_encode(array('status'=>1,'msg'=>'Order has been placed'));
					// $this->sendOrderSMSToVendor($order);
					// $this->sendOrderSMSToUser($order);
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Incorrect data'));
				}
			}
		} else {
				echo json_encode(array('status'=>1));
			}
	}

	protected function sendOrderSMSToVendor($order) {
		$to = '+91'.$order->restaurant->mobile_number;
		$text = "Recieved an order for ".$order->package->item->name." with quantity".$order->package->quantity." amounting to INR ".$order->amount.". Regards Team DinersMeet";
		$this->sendSMS($to,$text);
	}

	protected function sendOrderSMSToUser($order) {
		$to = '+91'.$order->customer->mobile_number;
		$text = "Recieved an order for ".$order->package->item->name." with quantity".$order->package->quantity." amounting to INR ".$order->amount.". Regards Team DinersMeet";
		$this->sendSMS($to,$text);
	}

	public function actionTransaction() {
		if(isset($_POST['token']) &&  isset($_POST['allCheckout'])) {
			$cart = ShoppingCart::model()->findByAttributes(array('customer_id'=>Yii::app()->user->id));
			$cartItems = ShoppingCartHasItems::model()->with('item','item.restaurant')->findAllByAttributes(array('shopping_cart_id'=>$cart->id,'status'=>1));
			$token = $_POST['token'];
			$amnt = explode(' ', $_POST['amount']);
			try{
				$secretkey=Controller::getSecretKey();
				\Stripe\Stripe::setApiKey($secretkey);

				$charge = \Stripe\Charge::create(
					array(
						'amount' => ($amnt[0]*100),
						'currency' => 'usd',
						'source' => $token
					)
				);

				if($charge->paid) {
					$transaction = new Transaction;
					$transaction->transaction_number = $token;
					$transaction->amount = $amnt[0];
					$transaction->transaction_status = 'done';
					$transaction->add_date = new CDbExpression('NOW()');
					$transaction->modify_date = new CDbExpression('NOW()');
					$transaction->save();

					foreach ($cartItems as $cartItem) {
						$order = new Orders;
						$order->customer_id = Yii::app()->user->id;
						$order->package_id = $cartItem->id;
						$order->restaurant_id = $cartItem->item->restaurant->id;
						$order->delivery_address_id = $_POST['delivery-type'] == 'delivery'?$_POST['addressId']:NULL;
						$order->amount = $cartItem->item_cost;
						$order->transaction_id = $transaction->id;
						$order->payment_type = 'credit';
						if($_POST['delivery-type'] == 'pickup') {
							$order->serving_type = 'pickup';
						} else {
							if($_POST['delivery-type'] == 'delivery' && $cartItem->item->delivery_available) {
								$order->serving_type = 'delivery';
							} else {
								$order->serving_type = 'pickup';
							}
						}

						if($_POST['delivery-type'] == 'pickup') {
							$order->time_for_pickup = $_POST['hour'].":".$_POST['min'];
						} else {
							if($_POST['delivery-type'] == 'delivery' && $cartItem->item->delivery_available) {
								$order->time_for_delivery = $_POST['hour'].":".$_POST['min'];
							}
							else {
								$order->time_for_pickup = $_POST['hour'].":".$_POST['min'];
							}
						}
						$order->add_date = new CDbExpression('NOW()');
						$order->modify_date = new CDbExpression('NOW()');
						if($order->validate()) {
							$order->save();
							$cartItem->status=0;
							$cartItem->update();
							$orderStatus = new OrderStatus;
							$orderStatus->order_id = $order->id;
							$orderStatus->order_status = 'order_placed';
							$orderStatus->add_date = new CDbExpression('NOW()');
							$orderStatus->modify_date = new CDbExpression('NOW()');
							$orderStatus->save();
							echo json_encode(array('status'=>1,'msg'=>'Order has been placed with status'));
						}	else {
							echo json_encode(array('status'=>2,'msg'=>'Incorrect data'));
						}
					}
					echo json_encode(array('status'=>1,'msg'=>'Done with transaction'));
				} else {
					$transaction = new Transaction;
					$transaction->transaction_number = $token;
					$transaction->amount = $amnt[0];
					$transaction->transaction_status = 'fail';
					$transaction->add_date = new CDbExpression('NOW()');
					$transaction->modify_date = new CDbExpression('NOW()');
					$transaction->save();
					echo json_encode(array('status'=>2, 'msg'=>'Trans not possible'));
				}
			}
			catch(\Stripe\Error\InvalidRequest $e)
			{
				echo "Hello1";
				$e_json = $e->getJsonBody();
				$error = $e_json['error'];
				$response['error']=$error['message'];
				$response['status']="2";
				echo json_encode($response);
			}
			catch(\Stripe\Error\ApiConnection $e)
			{
				echo "Hello2";
				$e_json = $e->getJsonBody();
				$error = $e_json['error'];
				$response['error']=$error['message'];
				$response['status']="3";
				echo json_encode($response);
			}
			catch (\Stripe\Error\Base $e)
			{
				echo "Hello3";
				$e_json = $e->getJsonBody();
				$error = $e_json['error'];
				$response['error']=$error['message'];
				$response['message']="Something gone wrong.Please try later.And send us screenshot of this error.";
				$response['status']="4";
				echo json_encode($response);
			}
			catch(Exception $e)
			{
				echo "Hello4";
				$e_json = $e->getJsonBody();
				$error = $e_json['error'];
				$response['error']=$error['message'];
				$response['message']="Internal Server Error.";
				$response['success']="5";
				echo json_encode($response);
			}
		}
	}

	public function actionUpdateAddress() {
		if(isset($_POST['custId'])) {
			$address = CustomerAddressBook::model()->findByAttributes(array('customer_id'=>$_POST['custId']));
			if(!empty($address)) {
				$address->recipient_name = $_POST['name'];
				$address->recipient_mobile = $_POST['mobile'];
				$address->address = $_POST['address'];
				$address->modify_date = new CDbExpression('NOW()');
				$address->update();
				echo json_encode(array('status'=>1,'msg'=>'Updated address'));
			} else {
				$address = new CustomerAddressBook;
				$address->customer_id = $_POST['custId'];
				$address->recipient_name = $_POST['name'];
				$address->recipient_mobile = $_POST['mobile'];
				$address->address = $_POST['address'];
				$address->add_date = new CDbExpression('NOW()');
				$address->modify_date = new CDbExpression('NOW()');
				if($address->validate()) {
					$address->save();
					echo json_encode(array('status'=>1,'msg'=>'Address added'));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Wrong data entered'));
				}
			}
		}
	}

	public function actionViewOrders() {
		$orders = Orders::model()->with('package.item','orderStatuses','restaurant')->findAllByAttributes(array('customer_id'=>Yii::app()->user->id,'status'=>1));
		// CVarDumper::dump($orders,10,1); die;
		$this->render('order',array('orders'=>$orders));
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}