<?php
require_once('constants.php');
$client = new SoapClient(SERVER_URL, array(
    'trace'=> 1,
    'exceptions' => 0,
    'soap_version' => SOAP_1_2
));

$v_id = trim($_GET["id"]);
$v_isn =  trim($_GET['isn']);

$v_successUrl = URL_FOR_RETURN . 'success.php';
$v_failUrl = URL_FOR_RETURN . 'fail.php?data=' . json_encode($data);
$v_homeUrl = URL_FOR_RETURN . 'send.php';

$params = array(
    'isn' => $v_isn,
    'id' => $v_id,
    'successUrl'=> $v_successUrl,
    'failUrl' => $v_failUrl,
    'homeUrl' => $v_homeUrl,
);

$result = $client->GetPaymentURL($params);

$TypeErr = $result->GetPaymentURLResult->Errors->ServiceError->ErrorType;
if ($TypeErr == 'None') {
    header('Location: ' . $result->GetPaymentURLResult->Result, true, 301);
} else {
    echo 'Error:' . $result->GetPaymentURLResult->Errors->ServiceError->ErrorText;
}
