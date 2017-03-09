<?php
require_once('constants.php');

$all_data = array_merge($_REQUEST, $_POST);
$prev_all_data = (array) json_decode($all_data['all_data']);
$all_data = array_merge($all_data, $prev_all_data);
$data = json_decode($all_data['data']);
$sales = @json_decode($all_data['sales']);
$sales = empty($sales) ? $data->Sales : $sales;

$all_sales = array();
$index = 0;
foreach ($sales as $key => $sale) {
    $sale = (array) $sale;
    for ($i = 1; $i <= $sale['Quantity']; $i++) {
        if (!empty($all_data['good-' . ($key + 1) . '-protection']) || !empty($all_data['all_goods_protection'])) {
            $sav_sale = $sale;
            $sav_sale['Program'] = 'SAV';
            $sav_sale['Num'] = $index++;
            $all_sales[] = $sav_sale;
        }
        if (!empty($all_data['all_goods_ins_length'])) {
            $sale['Num'] = $index++;
            $sale['Program'] = 'EXT';
            $sale['Warranty'] = $all_data['all_goods_ins_length'];
            $all_sales[] = $sale;
        }
    }
    if (!empty($all_data['good-' . ($key + 1) . '-insurance'])) {
        $all_data['good_' . ($key + 1) . '_amount'] = empty($all_data['good_' . ($key + 1) . '_amount']) ? 1 : $all_data['good_' . ($key + 1) . '_amount'];
        for ($j = 1; $j <= $all_data['good_' . ($key + 1) . '_amount']; $j++) {
            if (!empty($all_data['good-' . ($key + 1) . '-insurance'])) {
                $sale['Num'] = $index++;
                if (!empty($all_data['good_' . ($key + 1) . '_ins_length'])) {
                    $sale['Program'] = 'EXT';
                    $sale['Warranty'] = $all_data['good_' . ($key + 1) . '_ins_length'];
                }
                $all_sales[] = $sale;
            }
        }
    }
}

$year = trim($all_data['birth_year']);
$month = trim($all_data['birth_month']);
if (strlen($month) < 2) {
    $month = '0' . $month;
}
$day = trim($all_data['birth_day']);
if (strlen($day) < 2) {
    $day = '0' . $day;
}
$birthday = $year . '-' . $month . '-' . $day . 'T03:00:01+03:00';
$phone_number = preg_replace('/[^0-9]/', '', trim($all_data['phone']));
$email = trim($all_data['email']);

$insurer = array(
    'LastName' => trim($all_data['last_name']),
    'FirstName' => trim($all_data['first_name']),
    'Patronymic' => trim($all_data['middle_name']),
    'Birthday' => $birthday,
    'TelephoneNumber' => $phone_number,
    'Email' => $email,
    'RussianDocument' => array(
        'PassportNumber' => trim($all_data['pass_number']),
        'PassportSerial' => trim($all_data['pass_series']),
    ),
);

$insurer['Address']['PostIndex'] = $all_data['index'];
$address_parts = explode(', ', $all_data['address']);
$insurer['Address']['Locality'] = empty($data->Head->Locality) ? (empty($address_parts[0]) ? '' : $address_parts[0]) : $data->Head->Locality;
$insurer['Address']['Street'] = empty($data->Head->Street) ? (empty($address_parts[1]) ? '' : $address_parts[1]) : $data->Head->Street;
$insurer['Address']['House'] = empty($data->Head->House) ? (empty($address_parts[2]) ? '' : $address_parts[2]) : $data->Head->House;
$insurer['Address']['Corp'] = empty($data->Head->Corp) ? (empty($address_parts[3]) || count($address_parts) < 5 ? '' : $address_parts[3]) : $data->Head->Corp;
$address_flat = count($address_parts) > 3 ? (count($address_parts) > 4 ? $address_parts[4] : $address_parts[3]) : '';
$insurer['Address']['Flat'] = empty($data->Head->Flat) ? $address_flat : $data->Head->Flat;

$client = new SoapClient(SERVER_URL, array(
    'trace' => 1,
    'exceptions' => 0,
    'soap_version' => SOAP_1_2
));

$params = array(
    'token',
    'data' => array(
        'PeriodUnit' => 'year',
        'PeriodLength' => '1',
        'BeginDate' => date('Y-m-d\TH:i:sP', strtotime('tomorrow')),
        'CompanyCODE' => $data->Head->CompanyCODE,
        'OrderNum' => $data->Head->OrderNum,
        'Program' => 'EXT',
        'Email' => $email,
        'Telephone' => $phone_number,
        'Elements' => $all_sales,
        'Insurer' => $insurer,
        'Pasport' => array(
            'PassportNumber' => trim($all_data['pass_number']),
            'PassportSerial' => trim($all_data['pass_series']),
        ),
        'PaymentInfo' => array(
            'PaymentType' => 'CreditCard',
            'Periodicity' => 'Annualy',
            'ConfirmedPremium' => trim($all_data['price-total']),
        )
    ),
);

$result = $client->CreatePanasonicInsurance($params);
$policy_result = (array) $result->CreatePanasonicInsuranceResult->Result;

$v_id = trim($policy_result["ID"]);
$v_isn =  trim($policy_result['ISN']);

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