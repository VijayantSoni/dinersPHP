<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		// $users=array(
		// 	// username => password
		// 	'demo'=>'demo',
		// 	'admin'=>'admin',
		// );
		$user = User::model()->find(array('condition'=>'email=:email','params'=>array(':email'=>$this->username)));
		if(empty($user))
			$user = User::model()->find(array('condition'=>'mobile_number=:number','params'=>array(':number'=>$this->username)));
			if(empty($user))
				$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($user->password!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else{
			Yii::app()->user->setState('id',$user->id);
			Yii::app()->user->setState('role',$user->role_id);
			Yii::app()->user->setState('email',$this->username);
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
}