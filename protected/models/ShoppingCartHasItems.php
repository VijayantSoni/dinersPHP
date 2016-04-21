<?php

/**
 * This is the model class for table "shopping_cart_has_items".
 *
 * The followings are the available columns in table 'shopping_cart_has_items':
 * @property integer $id
 * @property integer $item_id
 * @property integer $shopping_cart_id
 * @property integer $item_quantity
 * @property double $item_cost
 * @property string $admin_notes
 * @property integer $status
 * @property string $add_date
 * @property string $modify_date
 *
 * The followings are the available model relations:
 * @property Orders[] $orders
 * @property Item $item
 * @property ShoppingCart $shoppingCart
 */
class ShoppingCartHasItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shopping_cart_has_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, shopping_cart_id', 'required'),
			array('item_id, shopping_cart_id, item_quantity, status', 'numerical', 'integerOnly'=>true),
			array('item_cost', 'numerical'),
			array('admin_notes, add_date, modify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, item_id, shopping_cart_id, item_quantity, item_cost, admin_notes, status, add_date, modify_date', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY, 'Orders', 'package_id'),
			'item' => array(self::BELONGS_TO, 'Item', 'item_id'),
			'shoppingCart' => array(self::BELONGS_TO, 'ShoppingCart', 'shopping_cart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'item_id' => 'Item',
			'shopping_cart_id' => 'Shopping Cart',
			'item_quantity' => 'Item Quantity',
			'item_cost' => 'Item Cost',
			'admin_notes' => 'Admin Notes',
			'status' => 'Status',
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
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('shopping_cart_id',$this->shopping_cart_id);
		$criteria->compare('item_quantity',$this->item_quantity);
		$criteria->compare('item_cost',$this->item_cost);
		$criteria->compare('admin_notes',$this->admin_notes,true);
		$criteria->compare('status',$this->status);
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
	 * @return ShoppingCartHasItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
