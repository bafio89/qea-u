<?php

/**
 * This is the model class for table "interests".
 *
 * The followings are the available columns in table 'interests':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $website_url
 * @property string $oauth_provider
 * @property string $oauth_id
 * @property string $category_id
 *
 * The followings are the available model relations:
 * @property Categories $category
 * @property UsersInterests[] $usersInterests
 */
class Interest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'interests';
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
			array('name', 'length', 'max'=>100),
			array('oauth_provider', 'length', 'max'=>50),
			array('category_id', 'length', 'max'=>20),
			array('description, website_url, oauth_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, website_url, oauth_provider, oauth_id, category_id', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Categories', 'category_id'),
			'usersInterests' => array(self::HAS_MANY, 'UsersInterests', 'interest_id'),
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
			'description' => 'Description',
			'website_url' => 'Website Url',
			'oauth_provider' => 'Oauth Provider',
			'oauth_id' => 'Oauth',
			'category_id' => 'Category',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('website_url',$this->website_url,true);
		$criteria->compare('oauth_provider',$this->oauth_provider,true);
		$criteria->compare('oauth_id',$this->oauth_id,true);
		$criteria->compare('category_id',$this->category_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Interest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
