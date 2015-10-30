<?php

/**
 * This is the model class for table "users_oauth_accounts".
 *
 * The followings are the available columns in table 'users_oauth_accounts':
 * @property string $user_id
 * @property string $oauth_provider
 * @property string $oauth_token
 * @property string $oauth_token_type
 * @property string $oauth_token_expires
 * @property string $oauth_id
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserOauthAccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_oauth_accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, oauth_provider', 'required'),
			array('user_id', 'length', 'max'=>20),
			array('oauth_provider, oauth_token_type', 'length', 'max'=>50),
			array('oauth_token, oauth_token_expires, oauth_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, oauth_provider, oauth_token, oauth_token_type, oauth_token_expires, oauth_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'oauth_provider' => 'Oauth Provider',
			'oauth_token' => 'Oauth Token',
			'oauth_token_type' => 'Oauth Token Type',
			'oauth_token_expires' => 'Oauth Token Expires',
			'oauth_id' => 'Oauth',
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
		$criteria->compare('oauth_provider',$this->oauth_provider,true);
		$criteria->compare('oauth_token',$this->oauth_token,true);
		$criteria->compare('oauth_token_type',$this->oauth_token_type,true);
		$criteria->compare('oauth_token_expires',$this->oauth_token_expires,true);
		$criteria->compare('oauth_id',$this->oauth_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserOauthAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
