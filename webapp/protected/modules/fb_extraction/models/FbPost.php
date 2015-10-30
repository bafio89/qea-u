<?php

/**
 * This is the model class for table "fb_post".
 *
 * The followings are the available columns in table 'fb_post':
 * @property string $fbpid
 * @property string $message
 * @property string $application
 * @property string $caption
 * @property string $link
 * @property string $created_time
 * @property string $updated_time
 * @property string $author_id
 * @property string $type
 * @property string $exam_group_id
 * @property string $degree_group_id
 * @property integer $first_answer_time
 * @property integer $month
 * @property integer $trimester
 * @property integer $n_answer
 * @property string $post_type_l1
 * @property string $post_type_l2
 * @property string $post_type_l3
 *
 * The followings are the available model relations:
 * @property Users $author
 * @property DegreeGroup $degreeGroup
 * @property ExamGroup $examGroup
 * @property Months $month0
 * @property Trimestri $trimester0
 * @property FbPostComment[] $fbPostComments
 * @property FeaturesRappresentation $featuresRappresentation
 * @property Users[] $users
 * @property PhotoFbPost[] $photoFbPosts
 * @property PreProcPost $preProcPost
 */
class FbPost extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fb_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author_id', 'required'),
			array('first_answer_time, month, trimester, n_answer', 'numerical', 'integerOnly'=>true),
			array('application', 'length', 'max'=>300),
			array('caption', 'length', 'max'=>400),
			array('link', 'length', 'max'=>500),
			array('author_id, exam_group_id, degree_group_id', 'length', 'max'=>200),
			array('type', 'length', 'max'=>100),
			array('post_type_l1, post_type_l2, post_type_l3', 'length', 'max'=>45),
			array('message, created_time, updated_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fbpid, message, application, caption, link, created_time, updated_time, author_id, type, exam_group_id, degree_group_id, first_answer_time, month, trimester, n_answer, post_type_l1, post_type_l2, post_type_l3', 'safe', 'on'=>'search'),
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
			'month0' => array(self::BELONGS_TO, 'Months', 'month'),
			'trimester0' => array(self::BELONGS_TO, 'Trimestri', 'trimester'),
			'fbPostComments' => array(self::HAS_MANY, 'FbPostComment', 'ref_entity_id'),
			'featuresRappresentation' => array(self::HAS_ONE, 'FeaturesRappresentation', 'pid'),
			'users' => array(self::MANY_MANY, 'Users', 'like_fb_post(ref_entity_id, user_id)'),
			'photoFbPosts' => array(self::HAS_MANY, 'PhotoFbPost', 'element_id'),
			'preProcPost' => array(self::HAS_ONE, 'PreProcPost', 'fbpid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fbpid' => 'Fbpid',
			'message' => 'Message',
			'application' => 'Application',
			'caption' => 'Caption',
			'link' => 'Link',
			'created_time' => 'Created Time',
			'updated_time' => 'Updated Time',
			'author_id' => 'Author',
			'type' => 'Type',
			'exam_group_id' => 'Exam Group',
			'degree_group_id' => 'Degree Group',
			'first_answer_time' => 'First Answer Time',
			'month' => 'Month',
			'trimester' => 'Trimester',
			'n_answer' => 'N Answer',
			'post_type_l1' => 'Post Type L1',
			'post_type_l2' => 'Post Type L2',
			'post_type_l3' => 'Post Type L3',
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

		$criteria->compare('fbpid',$this->fbpid,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('application',$this->application,true);
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('updated_time',$this->updated_time,true);
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('exam_group_id',$this->exam_group_id,true);
		$criteria->compare('degree_group_id',$this->degree_group_id,true);
		$criteria->compare('first_answer_time',$this->first_answer_time);
		$criteria->compare('month',$this->month);
		$criteria->compare('trimester',$this->trimester);
		$criteria->compare('n_answer',$this->n_answer);
		$criteria->compare('post_type_l1',$this->post_type_l1,true);
		$criteria->compare('post_type_l2',$this->post_type_l2,true);
		$criteria->compare('post_type_l3',$this->post_type_l3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FbPost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
