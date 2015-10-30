<?php

/**
 * This is the model class for table "degree".
 *
 * The followings are the available columns in table 'degree':
 * @property string $did
 * @property string $name
 * @property string $university_id
 *
 * The followings are the available model relations:
 * @property Courses $courses
 * @property University $d
 * @property DegreeGroup[] $degreeGroups
 * @property ExamGroup[] $examGroups
 * @property Groups[] $groups
 * @property UsersProfessorsInfo[] $usersProfessorsInfos
 * @property UsersProspectStudentInfo[] $usersProspectStudentInfos
 * @property UsersStudentInfo[] $usersStudentInfos
 */
class Degree extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'degree';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('did, university_id', 'required'),
			array('did, university_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('did, name, university_id', 'safe', 'on'=>'search'),
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
			'courses' => array(self::HAS_ONE, 'Courses', 'cid'),
			'd' => array(self::BELONGS_TO, 'University', 'did'),
			'degreeGroups' => array(self::HAS_MANY, 'DegreeGroup', 'degree_id'),
			'examGroups' => array(self::HAS_MANY, 'ExamGroup', 'degree_id'),
			'groups' => array(self::HAS_MANY, 'Groups', 'degree_id'),
			'usersProfessorsInfos' => array(self::MANY_MANY, 'UsersProfessorsInfo', 'professors_degree(degree_id, professors_id)'),
			'usersProspectStudentInfos' => array(self::HAS_MANY, 'UsersProspectStudentInfo', 'desired_degree_id'),
			'usersStudentInfos' => array(self::HAS_MANY, 'UsersStudentInfo', 'degree_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'did' => 'Did',
			'name' => 'Name',
			'university_id' => 'University',
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

		$criteria->compare('did',$this->did,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('university_id',$this->university_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Degree the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
