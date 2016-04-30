<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $package_id
 * @property integer $restaurant_id
 * @property integer $delivery_address_id
 * @property integer $transaction_id
 * @property string $payment_type
 * @property double $amount
 * @property string $serving_type
 * @property string $time_for_pickup
 * @property string $time_for_delivery
 * @property integer $status
 * @property string $admin_notes
 * @property string $add_date
 * @property string $modify_date
 *
 * The followings are the available model relations:
 * @property OrderStatus[] $orderStatuses
 * @property User $customer
 * @property CustomerAddressBook $deliveryAddress
 * @property ShoppingCartHasItems $package
 * @property Restaurant $restaurant
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, package_id, restaurant_id', 'required'),
			array('customer_id, package_id, restaurant_id, delivery_address_id, transaction_id, status', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('payment_type', 'length', 'max'=>6),
			array('serving_type', 'length', 'max'=>8),
			array('time_for_pickup, time_for_delivery', 'length', 'max'=>10),
			array('admin_notes, add_date, modify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, package_id, restaurant_id, delivery_address_id, transaction_id, payment_type, amount, serving_type, time_for_pickup, time_for_delivery, status, admin_notes, add_date, modify_date', 'safe', 'on'=>'search'),
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
			'orderStatuses' => array(self::HAS_MANY, 'OrderStatus', 'order_id'),
			'customer' => array(self::BELONGS_TO, 'User', 'customer_id'),
			'deliveryAddress' => array(self::BELONGS_TO, 'CustomerAddressBook', 'delivery_address_id'),
			'package' => array(self::BELONGS_TO, 'ShoppingCartHasItems', 'package_id'),
			'restaurant' => array(self::BELONGS_TO, 'Restaurant', 'restaurant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'package_id' => 'Package',
			'restaurant_id' => 'Restaurant',
			'delivery_address_id' => 'Delivery Address',
			'transaction_id' => 'Transaction',
			'payment_type' => 'Payment Type',
			'amount' => 'Amount',
			'serving_type' => 'Serving Type',
			'time_for_pickup' => 'Time For Pickup',
			'time_for_delivery' => 'Time For Delivery',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('package_id',$this->package_id);
		$criteria->compare('restaurant_id',$this->restaurant_id);
		$criteria->compare('delivery_address_id',$this->delivery_address_id);
		$criteria->compare('transaction_id',$this->transaction_id);
		$criteria->compare('payment_type',$this->payment_type,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('serving_type',$this->serving_type,true);
		$criteria->compare('time_for_pickup',$this->time_for_pickup,true);
		$criteria->compare('time_for_delivery',$this->time_for_delivery,true);
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
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
