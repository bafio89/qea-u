<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $email
 * @property string $nickname
 * @property string $password
 * @property string $slug
 * @property string $n_post
 * @property string $n_comment
 * @property string $activity
 * @property string $user_type
 * @property integer $first_semester
 * @property integer $second_semester
 * @property integer $third_semester
 * @property integer $fourth_semester
 * @property integer $fifth_semester
 * @property integer $sixth_semester
 * @property integer $seventh_semester
 *
 * The followings are the available model relations:
 * @property DegreeGroup[] $degreeGroups
 * @property ExamGroup[] $examGroups
 * @property FbDoc[] $fbDocs
 * @property FbDocComment[] $fbDocComments
 * @property FbFiles[] $fbFiles
 * @property FbFilesComment[] $fbFilesComments
 * @property FbPost[] $fbPosts
 * @property FbPostComment[] $fbPostComments
 * @property PhotoFbCommentPost[] $photoFbCommentPosts
 * @property PhotoFbPost[] $photoFbPosts
 * @property UsersAdditionalInfo $usersAdditionalInfo
 * @property Addresses[] $addresses
 * @property UsersAssociationInfo $usersAssociationInfo
 * @property Groups[] $groups
 * @property UsersMetadata[] $usersMetadatas
 * @property UsersOauthAccounts $usersOauthAccounts
 * @property UsersPersonalInfo $usersPersonalInfo
 * @property UsersProspectStudentInfo $usersProspectStudentInfo
 * @property UsersSettings $usersSettings
 * @property UsersStudentInfo $usersStudentInfo
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_semester, second_semester, third_semester, fourth_semester, fifth_semester, sixth_semester, seventh_semester', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>200),
			array('nickname, slug, activity, user_type', 'length', 'max'=>45),
			array('password', 'length', 'max'=>64),
			array('n_post, n_comment', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, email, nickname, password, slug, n_post, n_comment, activity, user_type, first_semester, second_semester, third_semester, fourth_semester, fifth_semester, sixth_semester, seventh_semester', 'safe', 'on'=>'search'),
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
			'degreeGroups' => array(self::HAS_MANY, 'DegreeGroup', 'owner_id'),
			'examGroups' => array(self::HAS_MANY, 'ExamGroup', 'owner_id'),
			'fbDocs' => array(self::MANY_MANY, 'FbDoc', 'like_fb_doc(user_id, ref_entity_id)'),
			'fbDocComments' => array(self::MANY_MANY, 'FbDocComment', 'like_fb_comment_doc(user_id, ref_entity_id)'),
			'fbFiles' => array(self::MANY_MANY, 'FbFiles', 'like_fb_files(user_id, ref_entity_id)'),
			'fbFilesComments' => array(self::MANY_MANY, 'FbFilesComment', 'like_fb_comment_file(user_id, ref_entity_id)'),
			'fbPosts' => array(self::MANY_MANY, 'FbPost', 'like_fb_post(user_id, ref_entity_id)'),
			'fbPostComments' => array(self::MANY_MANY, 'FbPostComment', 'like_fb_comment_post(user_id, ref_entity_id)'),
			'photoFbCommentPosts' => array(self::HAS_MANY, 'PhotoFbCommentPost', 'author_id'),
			'photoFbPosts' => array(self::HAS_MANY, 'PhotoFbPost', 'author_id'),
			'usersAdditionalInfo' => array(self::HAS_ONE, 'UsersAdditionalInfo', 'user_id'),
			'addresses' => array(self::MANY_MANY, 'Addresses', 'users_addresses(user_id, address_id)'),
			'usersAssociationInfo' => array(self::HAS_ONE, 'UsersAssociationInfo', 'user_id'),
			'groups' => array(self::MANY_MANY, 'Groups', 'users_groups(user_id, group_id)'),
			'usersMetadatas' => array(self::HAS_MANY, 'UsersMetadata', 'user_id'),
			'usersOauthAccounts' => array(self::HAS_ONE, 'UsersOauthAccounts', 'user_id'),
			'usersPersonalInfo' => array(self::HAS_ONE, 'UsersPersonalInfo', 'user_id'),
			'usersProspectStudentInfo' => array(self::HAS_ONE, 'UsersProspectStudentInfo', 'user_id'),
			'usersSettings' => array(self::HAS_ONE, 'UsersSettings', 'user_id'),
			'usersStudentInfo' => array(self::HAS_ONE, 'UsersStudentInfo', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'nickname' => 'Nickname',
			'password' => 'Password',
			'slug' => 'Slug',
			'n_post' => 'N Post',
			'n_comment' => 'N Comment',
			'activity' => 'Activity',
			'user_type' => 'User Type',
			'first_semester' => 'First Semester',
			'second_semester' => 'Second Semester',
			'third_semester' => 'Third Semester',
			'fourth_semester' => 'Fourth Semester',
			'fifth_semester' => 'Fifth Semester',
			'sixth_semester' => 'Sixth Semester',
			'seventh_semester' => 'Seventh Semester',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('n_post',$this->n_post,true);
		$criteria->compare('n_comment',$this->n_comment,true);
		$criteria->compare('activity',$this->activity,true);
		$criteria->compare('user_type',$this->user_type,true);
		$criteria->compare('first_semester',$this->first_semester);
		$criteria->compare('second_semester',$this->second_semester);
		$criteria->compare('third_semester',$this->third_semester);
		$criteria->compare('fourth_semester',$this->fourth_semester);
		$criteria->compare('fifth_semester',$this->fifth_semester);
		$criteria->compare('sixth_semester',$this->sixth_semester);
		$criteria->compare('seventh_semester',$this->seventh_semester);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
