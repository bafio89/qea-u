<?php
Yii::import("ext.wr.WithRelatedBehavior");

/**
 * With Related Extended Behavior class
 * 
 * @author mikelantonio
 * @author nciardo 
 * @copyright 2013 QIRIS
 */
class WithRelatedExtendedBehavior extends WithRelatedBehavior{
	
	/**
	 * get validation errors
	 * 
	 * @param array $related list of related models names
	 * @return array the errors list in yii format
	 */
	public function getErrors($related = array()){
		
		$errorList = array();
		$owner=$this->getOwner();
		
		//get owner model error list
		foreach($owner->getErrors() as $attribute=>$errors)
			$errorList[CHtml::activeId($owner,$attribute)]=$errors;
		
		//get related models error list
		foreach($related as $name){
			
			if(is_array($owner->$name))
				foreach($owner->$name as $i =>$rel){
					foreach($rel->getErrors() as $attribute=>$errors)
						$errorList[CHtml::activeId($rel,'['.$i.']'.$attribute)]=$errors;
				}
			else
				foreach ($owner->$name->getErrors() as $attribute=>$errors)
					$errorList[CHtml::activeId($owner->$name, $attribute)]=$errors;
		}
		
		return $errorList;
	}
	
	/**
	 * get validation errors
	 * 
	 * @param array $related list of related models names
	 * @return string the json array of errors list in yii format
	 */
	public function getJsonErrors($related = array()){
		return json_encode($this->getErrors($related));
	}
	
}