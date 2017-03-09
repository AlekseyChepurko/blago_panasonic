<?php
require_once('constants.php');

$client = new SoapClient(SERVER_URL, array(
    'trace' => 1,
    'exceptions' => 0,
    'soap_version' => SOAP_1_2
));

$v_id = trim($_GET["id"]);
$v_isn = trim($_GET['isn']);
$v_num = trim($_GET['form_num']);

$params = array(
    'isn' => $v_isn,
    'id' => $v_id,
    'formn' => $v_num,
);

$result = $client->GetContractPolicyForm($params);

$TypeErr = $result->GetContractPolicyFormResult->Errors->ServiceError->ErrorType;
if ($TypeErr == 'None') {
    header("Cache-Control: private");
    header("Content-type: application/pdf");

    if ($_REQUEST['type'] == 'pdf') {
        header('Content-Disposition: attachment; filename=' . strtr($params['id'], array('/' => '-')) . 'f' . $v_num . '.pdf');
    }
    echo $result->GetContractPolicyFormResult->Result;

} else {
    echo $result->GetContractPolicyFormResult->Errors->ServiceError->ErrorText;
}