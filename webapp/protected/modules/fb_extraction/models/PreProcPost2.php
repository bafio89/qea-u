<?php

/**
 * This is the model class for table "pre_proc_post2".
 *
 * The followings are the available columns in table 'pre_proc_post2':
 * @property string $fbpid
 * @property string $message
 * @property string $post_type_l1
 * @property string $post_type_l2
 * @property string $post_type_l3
 *
 * The followings are the available model relations:
 * @property FbPost $fbp
 */
class PreProcPost2 extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pre_proc_post2';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_type_l1, post_type_l2, post_type_l3', 'length', 'max'=>45),
			array('message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fbpid, message, post_type_l1, post_type_l2, post_type_l3', 'safe', 'on'=>'search'),
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
			'fbp' => array(self::BELONGS_TO, 'FbPost', 'fbpid'),
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
	 * @return PreProcPost2 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
