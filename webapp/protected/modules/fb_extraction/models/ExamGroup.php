<?php

/**
 * This is the model class for table "exam_group".
 *
 * The followings are the available columns in table 'exam_group':
 * @property string $eid
 * @property string $description
 * @property string $email
 * @property string $link
 * @property string $name
 * @property string $courses_id
 * @property string $degree_group_id
 * @property string $update_time
 * @property string $owner_id
 * @property string $user_count
 * @property string $degree_id
 * @property string $mapping
 * @property string $city
 *
 * The followings are the available model relations:
 * @property Courses $courses
 * @property DegreeGroup $degreeGroup
 * @property Degree $degree
 * @property Users $owner
 * @property FbDoc[] $fbDocs
 * @property FbFiles[] $fbFiles
 * @property FbPost[] $fbPosts
 */
class ExamGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('eid', 'required'),
			array('eid', 'length', 'max'=>20),
			array('email, courses_id, degree_group_id, owner_id, degree_id', 'length', 'max'=>200),
			array('link, name', 'length', 'max'=>500),
			array('user_count', 'length', 'max'=>10),
			array('mapping, city', 'length', 'max'=>45),
			array('description, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eid, description, email, link, name, courses_id, degree_group_id, update_time, owner_id, user_count, degree_id, mapping, city', 'safe', 'on'=>'search'),
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
			'courses' => array(self::BELONGS_TO, 'Courses', 'courses_id'),
			'degreeGroup' => array(self::BELONGS_TO, 'DegreeGroup', 'degree_group_id'),
			'degree' => array(self::BELONGS_TO, 'Degree', 'degree_id'),
			'owner' => array(self::BELONGS_TO, 'Users', 'owner_id'),
			'fbDocs' => array(self::HAS_MANY, 'FbDoc', 'exam_group_id'),
			'fbFiles' => array(self::HAS_MANY, 'FbFiles', 'exam_group_id'),
			'fbPosts' => array(self::HAS_MANY, 'FbPost', 'exam_group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'eid' => 'Eid',
			'description' => 'Description',
			'email' => 'Email',
			'link' => 'Link',
			'name' => 'Name',
			'courses_id' => 'Courses',
			'degree_group_id' => 'Degree Group',
			'update_time' => 'Update Time',
			'owner_id' => 'Owner',
			'user_count' => 'User Count',
			'degree_id' => 'Degree',
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

		$criteria->compare('eid',$this->eid,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('courses_id',$this->courses_id,true);
		$criteria->compare('degree_group_id',$this->degree_group_id,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('user_count',$this->user_count,true);
		$criteria->compare('degree_id',$this->degree_id,true);
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
	 * @return ExamGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
