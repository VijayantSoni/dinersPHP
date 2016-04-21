<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
 * @property integer $id
 * @property string $name
 * @property string $details
 * @property string $logo
 * @property integer $is_veg
 * @property integer $is_spicy
 * @property double $price
 * @property string $pricing_detail
 * @property integer $serving_time
 * @property integer $delivery_available
 * @property integer $status
 * @property string $admin_notes
 * @property string $add_date
 * @property string $modify_date
 *
 * The followings are the available model relations:
 * @property Category[] $categories
 * @property Restaurant[] $restaurants
 * @property ShoppingCartHasItems[] $shoppingCartHasItems
 */
class Item extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('is_veg, is_spicy, serving_time, delivery_available, status', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('name, logo, pricing_detail', 'length', 'max'=>255),
			array('details, admin_notes, add_date, modify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, details, logo, is_veg, is_spicy, price, pricing_detail, serving_time, delivery_available, status, admin_notes, add_date, modify_date', 'safe', 'on'=>'search'),
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
			'categories' => array(self::MANY_MANY, 'Category', 'item_has_categories(item_id, category_id)'),
			'restaurants' => array(self::MANY_MANY, 'Restaurant', 'restaurant_has_items(item_id, restaurant_id)'),
			'shoppingCartHasItems' => array(self::HAS_MANY, 'ShoppingCartHasItems', 'item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'details' => 'Details',
			'logo' => 'Logo',
			'is_veg' => 'Is Veg',
			'is_spicy' => 'Is Spicy',
			'price' => 'Price',
			'pricing_detail' => 'Pricing Detail',
			'serving_time' => 'Serving Time',
			'delivery_available' => 'Delivery Available',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('details',$this->details,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('is_veg',$this->is_veg);
		$criteria->compare('is_spicy',$this->is_spicy);
		$criteria->compare('price',$this->price);
		$criteria->compare('pricing_detail',$this->pricing_detail,true);
		$criteria->compare('serving_time',$this->serving_time);
		$criteria->compare('delivery_available',$this->delivery_available);
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
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
