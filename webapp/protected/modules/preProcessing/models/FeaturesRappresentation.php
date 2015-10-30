<?php

/**
 * This is the model class for table "features_rappresentation".
 *
 * The followings are the available columns in table 'features_rappresentation':
 * @property string $pid
 * @property string $format_info
 * @property string $n_char
 * @property string $n_char_comment_length
 * @property string $n_char_comment_length_ratio
 * @property string $n_comment
 * @property string $n_like_comment
 * @property string $link_presence
 * @property string $top_author_user
 * @property string $n_like
 * @property string $like_top_user
 * @property string $device
 * @property string $picture_presence
 * @property string $math_symbols_presence
 * @property string $question_mark_presence
 * @property integer $time_element_presence
 * @property string $comment_author_top
 */
class FeaturesRappresentation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'features_rappresentation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid', 'required'),
			array('time_element_presence', 'numerical', 'integerOnly'=>true),
			array('pid', 'length', 'max'=>200),
			array('format_info, n_char, n_char_comment_length, n_char_comment_length_ratio, n_comment, n_like_comment, n_like, math_symbols_presence, question_mark_presence', 'length', 'max'=>10),
			array('link_presence, top_author_user, like_top_user, device, picture_presence, comment_author_top', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pid, format_info, n_char, n_char_comment_length, n_char_comment_length_ratio, n_comment, n_like_comment, link_presence, top_author_user, n_like, like_top_user, device, picture_presence, math_symbols_presence, question_mark_presence, time_element_presence, comment_author_top', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pid' => 'Pid',
			'format_info' => 'Format Info',
			'n_char' => 'N Char',
			'n_char_comment_length' => 'N Char Comment Length',
			'n_char_comment_length_ratio' => 'N Char Comment Length Ratio',
			'n_comment' => 'N Comment',
			'n_like_comment' => 'N Like Comment',
			'link_presence' => 'Link Presence',
			'top_author_user' => 'Top Author User',
			'n_like' => 'N Like',
			'like_top_user' => 'Like Top User',
			'device' => 'Device',
			'picture_presence' => 'Picture Presence',
			'math_symbols_presence' => 'Math Symbols Presence',
			'question_mark_presence' => 'Question Mark Presence',
			'time_element_presence' => 'Time Element Presence',
			'comment_author_top' => 'Comment Author Top',
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

		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('format_info',$this->format_info,true);
		$criteria->compare('n_char',$this->n_char,true);
		$criteria->compare('n_char_comment_length',$this->n_char_comment_length,true);
		$criteria->compare('n_char_comment_length_ratio',$this->n_char_comment_length_ratio,true);
		$criteria->compare('n_comment',$this->n_comment,true);
		$criteria->compare('n_like_comment',$this->n_like_comment,true);
		$criteria->compare('link_presence',$this->link_presence,true);
		$criteria->compare('top_author_user',$this->top_author_user,true);
		$criteria->compare('n_like',$this->n_like,true);
		$criteria->compare('like_top_user',$this->like_top_user,true);
		$criteria->compare('device',$this->device,true);
		$criteria->compare('picture_presence',$this->picture_presence,true);
		$criteria->compare('math_symbols_presence',$this->math_symbols_presence,true);
		$criteria->compare('question_mark_presence',$this->question_mark_presence,true);
		$criteria->compare('time_element_presence',$this->time_element_presence);
		$criteria->compare('comment_author_top',$this->comment_author_top,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FeaturesRappresentation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
