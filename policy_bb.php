<?php

error_reporting(-1);
ini_set('display_errors', 'On');
defined('SERVER_URL') || define('SERVER_URL', 'http://onlinedev.skblago.ru/onlineservice.asmx?wsdl');



//ping
$client = new SoapClient(SERVER_URL, array(                    
    'trace' => 1,
    'exceptions' => 0,
    'soap_version' => SOAP_1_2
));                          


$params = array('token');

$result = $client->Ping($params);

  print "<br>Ping Запрос :\n".htmlspecialchars($client->__getLastRequest()) ."\n"; 
  print "<br>Ping Ответ:\n".htmlspecialchars($client->__getLastResponse())."\n"; 
  
      
//CreatePanasonic

 
$params = array(
    'token',
    'data' => array(
        'PeriodUnit' => 'year',
        'PeriodLength' => '1',
        'BeginDate' => date('Y-m-d\TH:i:sP', strtotime('tomorrow')),
        'CompanyCODE' =>  'Site_example'/*$data->Head->CompanyCode*/ ,
        'OrderNum' => '10001'/*$data->Head->OrderNum*/,
        'Program' => 'EXT',
        'Email' => 'example@example.ru'/*$email*/,
        'Telephone' => '123123213'/*$phone_number*/,
        'Elements' => /*$sales*/
         array('PanasonicSale' => array(
          'Num' => '0',
          'Brand' => 'Tefal',
          'Cat' =>'1969003709',
          'ModelName' =>'Utug',
          'Price' => '123123',
          'Warranty' =>'1',
          'Other' =>'Other...',
          'Program' => 'EXT'
         )
         
         )
         
         ,
        
        
        'Insurer' => array(
            'LastName' => 'LastName'/*trim($_REQUEST['last_name'])*/,
            'FirstName' => 'FirstName' /*trim($_REQUEST['first_name'])*/,
            'Patronymic' => 'Patromic' /*trim($_REQUEST['middle_name'])*/,
            'Birthday' =>  date('Y-m-d\TH:i:sP',strtotime('1980-04-19 18:31:27')) /*$birthday*/,
            'TelephoneNumber' =>  '213213213' /*$phone_number*/,
            'Email' => 'example@fff.ru'/*$email*/,
            'Address' => array(
                'PostIndex' => '12321'/*$data->Head->PIndex*/,
                'Locality' => 'Local'/*$data->Head->Locality*/,
                'Street' => 'street'/*$data->Head->Street*/,
                'House' => 'House' /*$data->Head->House*/,
                'Corp' => 'Corp'/*$data->Head->Corp*/,
                'Flat' => 'Flat'/*$data->Head->Flat*/,
            ),
            'RussianDocument' => array(
                'PassportNumber' => '1231231'/*trim($_REQUEST['pass_number'])*/,
                'PassportSerial' => '12321' /*trim($_REQUEST['pass_series'])*/,
            ),
        ),
        'Pasport' => array(
            'PassportNumber' => '1231231'/*trim($_REQUEST['pass_number'])*/,
            'PassportSerial' => '12321' /*trim($_REQUEST['pass_series'])*/,
        ),
        'PaymentInfo' => array(
            'PaymentType' => 'CreditCard',
            'Periodicity' => 'Annualy',
            'ConfirmedPremium' => '1000'/*trim($_REQUEST['price-total'])*/,
        )
    ),
);



//CreateRetailPanasonicInsurance
//CreatePanasonicInsurance

$result = $client->CreatePanasonicInsurance($params);
print "<br>Запрос :\n".htmlspecialchars($client->__getLastRequest()) ."\n"; 
print "<br>Ответ:\n".htmlspecialchars($client->__getLastResponse())."\n"; 
$policy_result = (array) $result->CreatePanasonicInsuranceResult->Result;  



$v_id = trim($policy_result["ID"]);
$v_isn =  trim($policy_result['ISN']);

print "<br>ID \n:".$v_id . " ".$v_isn;


    