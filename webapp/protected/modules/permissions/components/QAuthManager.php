<?php

Yii::import('application.modules.permissions.models.*');

/**
 * This component manages the user authorization.
 * Basically it helps to check if a user has permissions over an object.
 * 
 * TODO:
 * - Add cache support.
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class QAuthManager extends CComponent {
	
	/**
	 * It defines if queries performed while the system checks for permissions will be cached or not.
	 * If you want to enable/disable the cache, use the method "setCacheEnabled".
	 * 
	 * @var boolean
	 */
	private static $_cacheEnabled = false;
	
	/**
	 * 
	 * @var unknown
	 */
	const SUBJECT_USERS = "users";
	
	/**
	 * 
	 * @var unknown
	 */
	const SUBJECT_GROUPS = "groups";
	
	
	/**
	 * Grants access to an user over a resource.
	 * 
	 * @param string $subj_type
	 * @param number $subj_id
	 * @param string $rule_name
	 * @param string $obj_type
	 * @param number $obj_id
	 * @return boolean
	 */
	public static function grantAccess($subj_type, $subj_id, $rule_name, $obj_type = null, $obj_id = null)
	{
		$ret_val = false;
		
		if (isset($subj_type) && isset($subj_id) && isset($rule_name)) {
			$rule = Rule::model()->findByAttributes(array('name' => $rule_name));
			
			if (isset($rule)) {
				$attr['subject_type'] = $subj_type;
				$attr['subject_id'] = $subj_id;
				$attr['rule_id'] = $rule->id;
				$attr['object_type'] = isset($obj_type) ? $obj_type : null;
				$attr['object_id'] = isset($obj_id) ? $obj_id : null;
				
				$found = Permission::model()->findByAttributes($attr);
				
				if (!isset($found)) {
					$obj_perm = new Permission();
					$obj_perm->subject_type = $subj_type;
					$obj_perm->subject_id = $subj_id;
					$obj_perm->rule_id = $rule->id;
					$obj_perm->object_type = isset($obj_type) ? $obj_type : null;
					$obj_perm->object_id = isset($obj_id) ? $obj_id : null;
					
					if ($obj_perm->save()) {
						$ret_val = true;
					}
				}
			}
		}
		
		return $ret_val;
	}

	/**
	 * Revokes access to an user over a resource.
	 * 
	 * @param string $subj_type
	 * @param number $subj_id
	 * @param string $rule_name
	 * @param string $obj_type
	 * @param number $obj_id
	 * @return boolean
	 */
	public static function revokeAccess($subj_type, $subj_id, $rule_name, $obj_type = null, $obj_id = null)
	{
		$ret_val = false;
		
		if (isset($subj_type) && isset($subj_id) && isset($rule_name)) {
			$rule = Rule::model()->findByAttributes(array('name' => $rule_name));
				
			if (isset($rule)) {
				$attr['subject_type'] = $subj_type;
				$attr['subject_id'] = $subj_id;
				$attr['rule_id'] = $rule->id;
				$attr['object_type'] = isset($obj_type) ? $obj_type : null;
				$attr['object_id'] = isset($obj_id) ? $obj_id : null;
				
				if (Permission::model()->deleteAllByAttributes($attr)) {
					$ret_val = true;
				}
			}
		}
		
		return $ret_val;
	}
	
	/**
	 * This utility says if a user can execute certain rules over an object.
	 * 
	 * @param User $user
	 * @param string $rule_name
	 * @param string $obj_type
	 * @param number $obj_id
	 * @return boolean Returns true if the user has access, false otherwise.
	 */
	public static function hasAccess($user, $rule_name, $obj_type = null, $obj_id = null)
	{
		$ret_val = false;
		
		if (isset($user) && isset($rule_name)) {
			/* rule */
			$rule = Rule::model()->findByAttributes(array('name' => $rule_name));
			
			/* does it exist? */
			if (isset($rule)) {
				$perms_attr['rule_id'] = $rule->id;
				$perms_attr['object_type'] = isset($obj_type) ? $obj_type : null;
				$perms_attr['object_id'] = isset($obj_id) ? $obj_id : null;
				$perms = Permission::model()->findAllByAttributes($perms_attr);
				
				if (isset($perms)) {
					foreach ($perms as $p) {
						if ($p->subject_type == 'users' && $p->subject_id == $user->id) {
							$ret_val = true;
							break;
						}
					}
					
					if (!$ret_val) {
						foreach ($perms as $p) {
							if ($p->subject_type == 'groups' && $user->isInGroup($p->subject_id)) {
								$ret_val = true;
								break;
							}
						}
					}
				}
			}
		}
		
		return $ret_val;
	}

	/**
	 * A subject is who'll be authorized (users or groups).
	 * This method returns an array of records which contains all the permissions related to the subject.
	 * 
	 * @param string $subj_type
	 * @param number $subj_id
	 * @param string $object_type
	 * @param number $object_id
	 * @return Permission 
	 */
	public static function getSubjectPermissions($subj_type, $subj_id, $object_type = null, $object_id = null)
	{
		$subj_perms = array();
		
		if (isset($subj_type) && isset($subj_id)) {
			$subj_criteria = new CDbCriteria();
			$subj_criteria->condition = 'subject_type = :subj_type AND subject_id = :subj_id';
			$subj_criteria->params[':subj_type'] = $subj_type;
			$subj_criteria->params[':subj_id'] = $subj_id;
			
			if(isset($object_type)){
				$subj_criteria->addCondition("object_type = :obj_type"); 
				$subj_criteria->params[':obj_type'] = $object_type;
			}
				
			if(isset($object_id)){
				$subj_criteria->addCondition("object_id = :obj_id");
				$subj_criteria->params[':obj_id'] = $object_id;
			}
				
			$subj_perms = Permission::model()->findAll($subj_criteria);
		}
		
		return $subj_perms;
	}
	
	/**
	 * An object is who'll be accessed by subjects (users or groups).
	 * This method returns an array of records which contains all the permissions related to the object.
	 * 
	 * @param string $obj_type
	 * @param number $obj_id
	 * @return Permission ...
	 */
	public static function getObjectPermissions($obj_type, $obj_id = null)
	{
		$obj_perms = array();
		
		if (isset($obj_type)) {
			$obj_criteria = new CDbCriteria();
			$obj_criteria->condition = 'object_type = :obj_type';
			$obj_criteria->params[':obj_type'] = $obj_type;
			
			if (isset($obj_id)) {
				$obj_criteria->condition .= ' AND object_id = :obj_id';
				$obj_criteria->params[':obj_id'] = $obj_id;
			}
			
			$obj_perms = Permission::model()->findAll($obj_criteria);
		}
		
		return $obj_perms;
	}

	/**
	 * This method allows you to specify if queries (results) will be cached or not.
	 *
	 * @param boolean $enabled It turns the cache on/off.
	 * @return boolean It returns true if the cache is enabled, false otherwise.
	 * 
	 * @see CFileCache
	 */
	public static function setCacheEnabled($enabled)
	{
		if (isset($enabled) && is_bool($enabled)) {
			self::$_cacheEnabled = $enabled;
		}
	
		return self::isCacheEnabled();
	}

	/**
	 * It returns true if the cache is enabled, false otherwise.
	 * 
	 * @return boolean It returns true if the cache is enabled, false otherwise.
	 */
	public static function isCacheEnabled()
	{
		return self::$_cacheEnabled;
	}

	/**
	 * Not implemented yet.
	 */
	public static function flushCache()
	{
		throw new Exception('Not implemented yet.');
	}
	
	/**
	 * Not implemented yet.
	 * 
	 * @param unknown $seconds
	 */
	public static function setCacheExpirationTime($seconds)
	{
		throw new Exception('Not implemented yet.');
	}
	
	/**
	 * Not implemented yet.
	 * 
	 * @return number
	 */
	public static function getCacheExpirationTime()
	{
		throw new Exception('Not implemented yet.');
		return 0;
	}
	
}
