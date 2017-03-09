<?php

error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');


   
  
$client = new SoapClient('http://online.skblago.ru/onlineservice.asmx?wsdl', array(
    'trace' => 1,
    'exceptions' => 0,
    'soap_version' => SOAP_1_2
));



$params = ['token'];

$result = $client->Ping($params);

  print "Ping Запрос :\n".htmlspecialchars($client->__getLastRequest()) ."\n"; 
  print "Ping Ответ:\n".htmlspecialchars($client->__getLastResponse())."\n"; 
  
