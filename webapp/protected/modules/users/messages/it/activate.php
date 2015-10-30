<?php

return array(
	'page.title'=>'Attivazione account',
	
	'page.activation.success'=>'L\'account è stato attivato con successo. <br> Adesso puoi effettuare il <a href="'.Yii::app()->createUrl('users/auth').'">login</a> regolarmente.',
	'page.activation.failure'=>'Sfortunatamente non possiamo attivare il tuo account. <br> Per favore, contatta il nostro staff tecnico per ulteriori informazioni su questo problema.',

	'email.subject.required' => 'Attiva il tuo account',
	'email.subject.done' => 'Il tuo account è attivo!',
);
