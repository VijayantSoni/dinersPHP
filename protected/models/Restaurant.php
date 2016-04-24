<?php

/**
 * This is the model class for table "restaurant".
 *
 * The followings are the available columns in table 'restaurant':
 * @property integer $id
 * @property integer $location_id
 * @property integer $vendor_id
 * @property string $name
 * @property string $street_address
 * @property string $mobile_number
 * @property string $logo
 * @property integer $status
 * @property string $admin_notes
 * @property string $add_date
 * @property string $modify_date
 *
 * The followings are the available model relations:
 * @property Item[] $items
 * @property AvailableInLocation $location
 * @property User $vendor
 */
class Restaurant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'restaurant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id, vendor_id, name', 'required'),
			array('location_id, vendor_id, status', 'numerical', 'integerOnly'=>true),
			array('name, logo', 'length', 'max'=>255),
			array('mobile_number', 'length', 'max'=>25),
			array('street_address, admin_notes, add_date, modify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, location_id, vendor_id, name, street_address, mobile_number, logo, status, admin_notes, add_date, modify_date', 'safe', 'on'=>'search'),
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
			'items' => array(self::HAS_MANY, 'Item', 'restaurant_id'),
			'location' => array(self::BELONGS_TO, 'AvailableInLocation', 'location_id'),
			'vendor' => array(self::BELONGS_TO, 'User', 'vendor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'location_id' => 'Location',
			'vendor_id' => 'Vendor',
			'name' => 'Name',
			'street_address' => 'Street Address',
			'mobile_number' => 'Mobile Number',
			'logo' => 'Logo',
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
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('street_address',$this->street_address,true);
		$criteria->compare('mobile_number',$this->mobile_number,true);
		$criteria->compare('logo',$this->logo,true);
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
	 * @return Restaurant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
