<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property integer $role_id
 * @property string $first_name
 * @property string $last_name
 * @property string $profile_image
 * @property string $email
 * @property string $mobile_number
 * @property string $password
 * @property integer $is_verified
 * @property string $email_verification_hash
 * @property string $otp_verification
 * @property integer $status
 * @property string $admin_notes
 * @property string $add_date
 * @property string $modify_date
 *
 * The followings are the available model relations:
 * @property CustomerAddressBook[] $customerAddressBooks
 * @property CustomerWallet[] $customerWallets
 * @property Orders[] $orders
 * @property Restaurant[] $restaurants
 * @property ShoppingCart[] $shoppingCarts
 * @property Role $role
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id, email, mobile_number, password', 'required'),
			array('role_id, is_verified, status', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, profile_image, email, password, email_verification_hash, otp_verification', 'length', 'max'=>255),
			array('mobile_number', 'length', 'max'=>25),
			array('admin_notes, add_date, modify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, role_id, first_name, last_name, profile_image, email, mobile_number, password, is_verified, email_verification_hash, otp_verification, status, admin_notes, add_date, modify_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'customerAddressBooks' => array(self::HAS_MANY, 'CustomerAddressBook', 'customer_id'),
			'customerWallets' => array(self::HAS_MANY, 'CustomerWallet', 'customer_id'),
			'orders' => array(self::HAS_MANY, 'Orders', 'customer_id'),
			'restaurants' => array(self::HAS_MANY, 'Restaurant', 'vendor_id'),
			'shoppingCarts' => array(self::HAS_MANY, 'ShoppingCart', 'customer_id'),
			'role' => array(self::BELONGS_TO, 'Role', 'role_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role_id' => 'Role',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'profile_image' => 'Profile Image',
			'email' => 'Email',
			'mobile_number' => 'Mobile Number',
			'password' => 'Password',
			'is_verified' => 'Is Verified',
			'email_verification_hash' => 'Email Verification Hash',
			'otp_verification' => 'Otp Verification',
			'status' => 'Status',
			'admin_notes' => 'Admin Notes',
			'add_date' => 'Add Date',
			'modify_date' => 'Modify Date',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('profile_image',$this->profile_image,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile_number',$this->mobile_number,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('is_verified',$this->is_verified);
		$criteria->compare('email_verification_hash',$this->email_verification_hash,true);
		$criteria->compare('otp_verification',$this->otp_verification,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('admin_notes',$this->admin_notes,true);
		$criteria->compare('add_date',$this->add_date,true);
		$criteria->compare('modify_date',$this->modify_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
