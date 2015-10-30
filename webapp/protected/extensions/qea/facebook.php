<?php

session_start();

require 'vendor/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;


FacebookSession::setDefaultApplication('286043231591201', '4efa214db52acdafb2757124e0d55d9d');

$redirect_url = "https://localhost/qea/index.php";

$session = new FacebookSession('CAAEEJ6E1myEBALv4dPlHSQRMNNVZBhe45ZC50983vmTJaM5ugthllyMfiBLJwHo73UWypkiCtDJDk8XOVpdYjQDbtbFvwPJ70gL6vSBwvNMuRlRH3ErCa9eC152IyvW8md4vZAhikeMaEWg3rHgPx896zZBRR0gibBUxOoV4ndTGO4Loh0nxfLmBuVflUHVl0oS6frSu6kCE6dLHYkZBosY2FLTpdCikZD');


$me = (new FacebookRequest(
  $session, 'GET', '/160266294021266/feed'
))->execute()->getGraphObject(GraphUser::className());

var_dump($me);
?>