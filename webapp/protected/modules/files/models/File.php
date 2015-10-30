<?php

/**
 * This is the model class for table "files".
 *
 * The followings are the available columns in table 'files':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $mime_type
 * @property string $created_time
 * @property string $local_file_path
 * @property string $local_file_name
 * @property string $local_file_extension
 * @property string $local_file_size
 * @property string $remote_file_path
 * @property string $remote_file_name
 * @property string $remote_file_extension
 * @property string $remote_file_size
 * @property string $url
 * @property integer $visibility
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property UsersAvatars[] $usersAvatars
 */
class File extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mime_type, created_time', 'required'),
			array('visibility, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>250),
			array('mime_type', 'length', 'max'=>25),
			array('local_file_size, remote_file_size', 'length', 'max'=>10),
			array('description, local_file_path, local_file_name, local_file_extension, remote_file_path, remote_file_name, remote_file_extension, url', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, mime_type, created_time, local_file_path, local_file_name, local_file_extension, local_file_size, remote_file_path, remote_file_name, remote_file_extension, remote_file_size, url, visibility, status', 'safe', 'on'=>'search'),
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
			'usersAvatars' => array(self::HAS_MANY, 'UsersAvatars', 'file_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'mime_type' => 'Mime Type',
			'created_time' => 'Created Time',
			'local_file_path' => 'Local File Path',
			'local_file_name' => 'Local File Name',
			'local_file_extension' => 'Local File Extension',
			'local_file_size' => 'Local File Size',
			'remote_file_path' => 'Remote File Path',
			'remote_file_name' => 'Remote File Name',
			'remote_file_extension' => 'Remote File Extension',
			'remote_file_size' => 'Remote File Size',
			'url' => 'Url',
			'visibility' => 'Visibility',
			'status' => 'Status',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('mime_type',$this->mime_type,true);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('local_file_path',$this->local_file_path,true);
		$criteria->compare('local_file_name',$this->local_file_name,true);
		$criteria->compare('local_file_extension',$this->local_file_extension,true);
		$criteria->compare('local_file_size',$this->local_file_size,true);
		$criteria->compare('remote_file_path',$this->remote_file_path,true);
		$criteria->compare('remote_file_name',$this->remote_file_name,true);
		$criteria->compare('remote_file_extension',$this->remote_file_extension,true);
		$criteria->compare('remote_file_size',$this->remote_file_size,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('visibility',$this->visibility);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return File the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
