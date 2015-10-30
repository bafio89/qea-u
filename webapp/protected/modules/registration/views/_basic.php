<?php

/* email field */
echo $form->textFieldControlGroup($model,'email',array(
	'errorOptions' => array('hideErrorMessage' => true)
));

/* nickname field */
echo $form->textFieldControlGroup($model,'nickname',array(
	'errorOptions' => array('hideErrorMessage' => true)
));
