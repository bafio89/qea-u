<?php

/**
 * This is the model class for table "fb_files".
 *
 * The followings are the available columns in table 'fb_files':
 * @property string $fbfid
 * @property string $updated_time
 * @property string $download_link
 * @property string $local_path
 * @property string $exam_group_id
 * @property string $author_id
 * @property string $degree_group_id
 *
 * The followings are the available model relations:
 * @property DegreeGroup $degreeGroup
 * @property Users $author
 * @property ExamGroup $examGroup
 * @property FbFilesComment[] $fbFilesComments
 * @property Users[] $users
 */
class FbFiles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fb_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fbfid, author_id', 'required'),
			array('fbfid, download_link, local_path, exam_group_id, author_id, degree_group_id', 'length', 'max'=>200),
			array('updated_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fbfid, updated_time, download_link, local_path, exam_group_id, author_id, degree_group_id', 'safe', 'on'=>'search'),
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
			'degreeGroup' => array(self::BELONGS_TO, 'DegreeGroup', 'degree_group_id'),
			'author' => array(self::BELONGS_TO, 'Users', 'author_id'),
			'examGroup' => array(self::BELONGS_TO, 'ExamGroup', 'exam_group_id'),
			'fbFilesComments' => array(self::HAS_MANY, 'FbFilesComment', 'fb_files_id'),
			'users' => array(self::MANY_MANY, 'Users', 'like_fb_files(files_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fbfid' => 'Fbfid',
			'updated_time' => 'Updated Time',
			'download_link' => 'Download Link',
			'local_path' => 'Local Path',
			'exam_group_id' => 'Exam Group',
			'author_id' => 'Author',
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

		$criteria->compare('fbfid',$this->fbfid,true);
		$criteria->compare('updated_time',$this->updated_time,true);
		$criteria->compare('download_link',$this->download_link,true);
		$criteria->compare('local_path',$this->local_path,true);
		$criteria->compare('exam_group_id',$this->exam_group_id,true);
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('degree_group_id',$this->degree_group_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FbFiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
