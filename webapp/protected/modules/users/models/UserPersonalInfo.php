<?php

/**
 * This is the model class for table "users_personal_info".
 *
 * The followings are the available columns in table 'users_personal_info':
 * @property string $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $birthdate
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserPersonalInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_personal_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'length', 'max'=>20),
			array('first_name, last_name', 'length', 'min'=>2, 'max'=>100),
			array('gender', 'length', 'max'=>1),
			array('first_name,last_name', 'match', 'not'=>true,'pattern'=>'/[0-9]/'),
			array('birthdate', 'safe'),
			//array('birthdate', 'date','format'=>'Y-m-d'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, first_name, last_name, gender, birthdate', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', array('user_id'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'first_name' => Yii::t('UsersModule.user','user.personalInfo.firstName'),
			'last_name' => Yii::t('UsersModule.user','user.personalInfo.lastName'),
			'gender' => Yii::t('UsersModule.user','user.personalInfo.gender'),
			'birthdate' => Yii::t('UsersModule.user','user.personalInfo.birthDate'),
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('birthdate',$this->birthdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserPersonalInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
