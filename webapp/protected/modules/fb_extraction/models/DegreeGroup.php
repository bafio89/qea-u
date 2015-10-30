<?php

/**
 * This is the model class for table "degree_group".
 *
 * The followings are the available columns in table 'degree_group':
 * @property string $cid
 * @property string $email
 * @property string $link
 * @property string $name
 * @property string $degree_id
 * @property string $description
 * @property string $owner_id
 * @property string $update_time
 * @property string $user_count
 * @property string $mapping
 * @property string $city
 *
 * The followings are the available model relations:
 * @property Degree $degree
 * @property Users $owner
 * @property ExamGroup[] $examGroups
 * @property FbDoc[] $fbDocs
 * @property FbFiles[] $fbFiles
 * @property FbPost[] $fbPosts
 */
class DegreeGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'degree_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid', 'required'),
			array('cid, email, degree_id, owner_id', 'length', 'max'=>200),
			array('link, name', 'length', 'max'=>500),
			array('user_count', 'length', 'max'=>10),
			array('mapping, city', 'length', 'max'=>45),
			array('description, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cid, email, link, name, degree_id, description, owner_id, update_time, user_count, mapping, city', 'safe', 'on'=>'search'),
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
			'degree' => array(self::BELONGS_TO, 'Degree', 'degree_id'),
			'owner' => array(self::BELONGS_TO, 'Users', 'owner_id'),
			'examGroups' => array(self::HAS_MANY, 'ExamGroup', 'degree_group_id'),
			'fbDocs' => array(self::HAS_MANY, 'FbDoc', 'degree_group_id'),
			'fbFiles' => array(self::HAS_MANY, 'FbFiles', 'degree_group_id'),
			'fbPosts' => array(self::HAS_MANY, 'FbPost', 'degree_group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cid' => 'Cid',
			'email' => 'Email',
			'link' => 'Link',
			'name' => 'Name',
			'degree_id' => 'Degree',
			'description' => 'Description',
			'owner_id' => 'Owner',
			'update_time' => 'Update Time',
			'user_count' => 'User Count',
			'mapping' => 'Mapping',
			'city' => 'City',
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

		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('degree_id',$this->degree_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('user_count',$this->user_count,true);
		$criteria->compare('mapping',$this->mapping,true);
		$criteria->compare('city',$this->city,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DegreeGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
