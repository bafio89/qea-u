<?php
/** 
 * @author Dav Ide <g.davide1989@gmail.com>
 * @author Niccolò Ciardo <contact@nciardo.com>
 */

/* password field */
echo $form->passwordFieldControlGroup($model, 'password', array(
	'errorOptions' => array('hideErrorMessage' => true)
)); 
 
/* confirm password field */
echo $form->passwordFieldControlGroup($model, 'confirm_password', array(
	'errorOptions' => array('hideErrorMessage' => true)
));
