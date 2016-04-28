<?php
class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
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

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$locations = AvailableInLocation::model()->findAllByAttributes(array('status'=>1,'parent_location_id'=>NULL));
		$this->render('index',array('locations'=>$locations));
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
					array_unshift($newItems, $item);
				}
			}
			CVarDumper::dump($newItems,10,1); die;
			$this->render('searchresults',array('items'=>$items,'query'=>$_GET['query'],'location'=>$_GET['location']));
		} else if(isset($_GET['restaurant'])) {
			$restaurant = Restaurant::model()->with('location')->findAllByAttributes(array('status'=>1));
			$key = 0;
			foreach($restaurant as $rest) {
				$resArray[$key]['id'] = $rest->id;
				$resArray[$key]['name'] = $rest->name;
				$resArray[$key]['location'] = $rest->location->name;
				$resArray[$key]['address'] = $rest->street_address;
				$resArray[$key]['contact'] = $rest->mobile_number;
				$key++;
			}
			echo json_encode($resArray);
		} else if (isset($_GET['cuisine'])) {

		}
	}

	public function actionError() {
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact() {
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
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
			$prevItem = ShoppingCartHasItems::model()->findByAttributes(array('item_id'=>$item->id,'shopping_cart_id'=>$user->shoppingCarts[0]->id));
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
		} else {
			$user = User::model()->with('shoppingCarts')->findByPk(Yii::app()->user->id);
			$cartItems = ShoppingCartHasItems::model()->with('item')->findAllByAttributes(array('shopping_cart_id'=>$user->shoppingCarts[0]->id,'status'=>1));
			$this->render('usercart',array('user'=>$user,'cartItems'=>$cartItems));
		}
	}


	public function actionProfile() {
		if(isset($_POST['first_name'])) {
			$user = User::model()->findByPk(Yii::app()->user->id);
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
		if(isset($_POST["itemId"])) {
			$prevItem = ShoppingCartHasItems::model()->findByAttributes(array('item_id'=>$_POST['itemId']));
			if(empty($prevItem)) {
				$cart = ShoppingCart::model()->findByAttributes(array('customer_id'=>Yii::app()->user->id));
				$item = Item::model()->findByPk($_POST["itemId"]);
				$cartItem = new ShoppingCartHasItems;
				$cartItem->item_id = $_POST["itemId"];
				$cartItem->shopping_cart_id = $cart->id;
				$cartItem->item_quantity = 1;
				$cartItem->item_cost = $cartItem->item_quantity * $item->price;
				$cartItem->status = 1;
				$cartItem->add_date = new CDbExpression('NOW()');
				$cartItem->modify_date = new CDbExpression('NOW()');
				if($cartItem->validate()) {
					$cartItem->save();
					echo json_encode(array('status'=>1,'url'=>Yii::app()->createUrl('site/cart')));
				} else {
					echo json_encode(array('status'=>2,'msg'=>'Sorry cannot process checkout'));
				}
			} else {
				echo json_encode(array('status'=>3,'url'=>Yii::app()->createUrl('site/cart')));
			}
		}
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}