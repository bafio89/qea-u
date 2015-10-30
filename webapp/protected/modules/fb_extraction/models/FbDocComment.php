<?php

/**
 * This is the model class for table "fb_doc_comment".
 *
 * The followings are the available columns in table 'fb_doc_comment':
 * @property string $cid
 * @property string $message
 * @property string $created_time
 * @property string $like_count
 * @property string $author_id
 * @property string $ref_entity_id
 *
 * The followings are the available model relations:
 * @property Users $author
 * @property FbDoc $refEntity
 * @property Users[] $users
 */
class FbDocComment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fb_doc_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, author_id', 'required'),
			array('cid, ref_entity_id', 'length', 'max'=>200),
			array('like_count', 'length', 'max'=>10),
			array('author_id', 'length', 'max'=>20),
			array('message, created_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cid, message, created_time, like_count, author_id, ref_entity_id', 'safe', 'on'=>'search'),
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
			'refEntity' => array(self::BELONGS_TO, 'FbDoc', 'ref_entity_id'),
			'users' => array(self::MANY_MANY, 'Users', 'like_fb_comment_doc(ref_entity_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cid' => 'Cid',
			'message' => 'Message',
			'created_time' => 'Created Time',
			'like_count' => 'Like Count',
			'author_id' => 'Author',
			'ref_entity_id' => 'Ref Entity',
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
		$criteria->compare('message',$this->message,true);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('like_count',$this->like_count,true);
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('ref_entity_id',$this->ref_entity_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FbDocComment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
