<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/constants.php');

$years = array(1, 2, 3);


function escape_sequence_decode($str) {

    // [U+D800 - U+DBFF][U+DC00 - U+DFFF]|[U+0000 - U+FFFF]
    $regex = '/\\\u([dD][89abAB][\da-fA-F]{2})\\\u([dD][c-fC-F][\da-fA-F]{2})
              |\\\u([\da-fA-F]{4})/sx';

    return preg_replace_callback($regex, function($matches) {

        if (isset($matches[3])) {
            $cp = hexdec($matches[3]);
        } else {
            $lead = hexdec($matches[1]);
            $trail = hexdec($matches[2]);

            // http://unicode.org/faq/utf_bom.html#utf16-4
            $cp = ($lead << 10) + $trail + 0x10000 - (0xD800 << 10) - 0xDC00;
        }

        // https://tools.ietf.org/html/rfc3629#section-3
        // Characters between U+D800 and U+DFFF are not allowed in UTF-8
        if ($cp > 0xD7FF && 0xE000 > $cp) {
            $cp = 0xFFFD;
        }

        // https://github.com/php/php-src/blob/php-5.6.4/ext/standard/html.c#L471
        // php_utf32_utf8(unsigned char *buf, unsigned k)

        if ($cp < 0x80) {
            return chr($cp);
        } else if ($cp < 0xA0) {
            return chr(0xC0 | $cp >> 6).chr(0x80 | $cp & 0x3F);
        }

        return html_entity_decode('&#'.$cp.';');
    }, $str);
}



if (isset($_POST['data']) && !empty($_POST['data'])) {
    $data = (array)json_decode($_POST['data']);
} elseif (isset($_GET['data']) && !empty($_GET['data'])) {
    $data = (array)json_decode(urldecode($_GET['data']));
}

//file_put_contents("./logs/GET_".date('Y-m-d').".txt", "\r\n"."[".date('Y-m-d H:i:s')."] IP:". $_SERVER["REMOTE_ADDR"]." AGENT:".$_SERVER['HTTP_USER_AGENT']."REFERER:".$_SERVER['HTTP_REFERER'], FILE_APPEND);
//file_put_contents("./logs/GET_".date('Y-m-d').".txt", serialize($data), FILE_APPEND);

                                                              

file_put_contents("./logs/GET_".date('Y-m-d').".txt",  escape_sequence_decode(json_encode($data)) . "\r\n", FILE_APPEND);
//serialize($data)


if (empty($data) || empty($data['Sales'])) {
    throw new Exception('Нет данных о приобритённых товарах');
}

$sales = $data['Sales'];
$used_keys = array();
$client = new SoapClient(SERVER_URL, array(
    'trace' => 1,
    'exceptions' => 0,
    'soap_version' => SOAP_1_2
));

$elements = array();

foreach ($sales as $key => $sale) {
    $elements[$key] = (array) $sale;
    $elements[$key]['Num'] = $key;
    $elements[$key]['Program'] = 'EXT';
    $sales[$key]->Program = 'EXT';
}

$params = array(
    'token',
    'data' => array(
        'PeriodUnit' => "year",
        'PeriodLength' => '1',
        'PromoCode' => 438532795,
        'BeginDate' => date('Y-m-d\TH:i:sP', strtotime('tomorrow')),
        'CompanyCODE' => $data['Head']->CompanyCODE,
        'OrderNum' => $data['Head']->OrderNum,
        'Program' => 'EXT',
        'Elements' => $elements,
    )
);

if (!empty($data['Head']->Email)) {
    $params['data']['Email'] = $data['Head']->Email;
}
if (!empty($data['Head']->Telephone)) {
    $params['data']['Telephone'] = $data['Head']->Telephone;
}

$result = $client->CalcRetailPanasonicInsurance($params);
$all_result = isset($result->CalcRetailPanasonicInsuranceResult->Result) ? (array)$result->CalcRetailPanasonicInsuranceResult->Result : array();

foreach ($elements as &$element) {
    $element['Program'] = 'SAV';
}
$params['data']['Elements'] = $elements;

$params['data']['Program'] = 'SAV';

$sav_result = $client->CalcRetailPanasonicInsurance($params);
$sav_result = isset($sav_result->CalcRetailPanasonicInsuranceResult->Result) ? (array)$sav_result->CalcRetailPanasonicInsuranceResult->Result : array();

$ext_total = 0;
if (!empty($all_result['XMLData'])) {
    $xml = new DOMDocument();
    $xml->loadXML($all_result['XMLData']);
    $nodes = $xml->firstChild->childNodes;

    for ($i = 0; $i < $nodes->length; $i++) {
        $child = $nodes->item($i)->childNodes;
        $key = $child->item(0)->nodeValue;
        $data['Sales'][$key]->ResultExt = $child->item(1)->nodeValue;
        $ext_total += $data['Sales'][$key]->ResultExt;
    }
}

$sav_total = 0;
if (!empty($sav_result['XMLData'])) {
    $xml = new DOMDocument();
    $xml->loadXML($sav_result['XMLData']);
    $nodes = $xml->firstChild->childNodes;

    for ($i = 0; $i < $nodes->length; $i++) {
        $child = $nodes->item($i)->childNodes;
        $key = $child->item(0)->nodeValue;
        $data['Sales'][$key]->ResultSav = $child->item(1)->nodeValue;
        $sav_total += $data['Sales'][$key]->ResultSav;
    }
}

if (isset($data['Sales']) && !empty($data['Sales'])) {
    foreach ($data['Sales'] as $key => $sale) {
        if (in_array($key, $used_keys)) {
            unset($data['Sales'][$key]);
            continue;
        }
        $sale->Quantity = 1;
        foreach ($sales as $index => $value) {
            if (
                $sale->ModelName == $value->ModelName &&
                $key != $index
            ) {
                $sale->Quantity++;
                array_push($used_keys, $index);
            }
        }
    }
}