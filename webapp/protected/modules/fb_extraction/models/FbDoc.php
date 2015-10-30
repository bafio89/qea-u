<?php

/**
 * This is the model class for table "fb_doc".
 *
 * The followings are the available columns in table 'fb_doc':
 * @property string $fbdid
 * @property string $subject
 * @property string $created_time
 * @property string $updated_time
 * @property string $message
 * @property string $author_id
 * @property string $exam_group_id
 * @property string $degree_group_id
 *
 * The followings are the available model relations:
 * @property Users $author
 * @property DegreeGroup $degreeGroup
 * @property ExamGroup $examGroup
 * @property FbDocComment[] $fbDocComments
 * @property Users[] $users
 */
class FbDoc extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fb_doc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fbdid, author_id', 'required'),
			array('fbdid, subject, exam_group_id, degree_group_id', 'length', 'max'=>200),
			array('author_id', 'length', 'max'=>20),
			array('created_time, updated_time, message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fbdid, subject, created_time, updated_time, message, author_id, exam_group_id, degree_group_id', 'safe', 'on'=>'search'),
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
			'author' => array(self::BELONGS_TO, 'Users', 'author_id'),
			'degreeGroup' => array(self::BELONGS_TO, 'DegreeGroup', 'degree_group_id'),
			'examGroup' => array(self::BELONGS_TO, 'ExamGroup', 'exam_group_id'),
			'fbDocComments' => array(self::HAS_MANY, 'FbDocComment', 'ref_entity_id'),
			'users' => array(self::MANY_MANY, 'Users', 'like_fb_doc(ref_entity_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fbdid' => 'Fbdid',
			'subject' => 'Subject',
			'created_time' => 'Created Time',
			'updated_time' => 'Updated Time',
			'message' => 'Message',
			'author_id' => 'Author',
			'exam_group_id' => 'Exam Group',
			'degree_group_id' => 'Degree Group',
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

		$criteria->compare('fbdid',$this->fbdid,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('updated_time',$this->updated_time,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('exam_group_id',$this->exam_group_id,true);
		$criteria->compare('degree_group_id',$this->degree_group_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FbDoc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
