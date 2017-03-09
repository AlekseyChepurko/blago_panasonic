// Create map with request parameters
$params = array ('surname' => 'Filip', 'lastname' => 'Czaja');
 
// Build Http query using params

$string = '{
   "Head":{
      "CompanyCODE":"PANASONIC",
      "OrderNum":"03453455566",
      "Program":" Идентификатор страхового продукта",
      "LName":"Фамилия",
      "FName":"Имя",
      "PName":"Отчество",
      "Birthday":" Дата рождения  ",
      "Telephone":"79787480493",
      "Email":"incog@list.ru",
      "PIndex":"297600",
      "Locality":"Белогорск",
      "Street":"Нижнегорская",
      "House":"31",
      "Corp":"",
      "Flat":"22",
      "PassportNumber":"269971",
      "PassportSerial":"3914",
      "PassportDate":"Дата выдачи",
      "PassportPdrName":"ФМС",
      "PassportPdrCode":"900-003",
      "AdrReg":{
         "PIndex":"297600",
         "Locality":"Белогорск",
         "Street":"Нижнегорская",
         "House":"31",
         "Corp":"",
         "Flat":"22"
      }
   },
   "Sales":[
      {
         "Brand":"Tefal",
         "Cat":"1969003709",
         "ModelName":"Utug",
         "Price":"10001",
         "Warranty":"1",
         "Other":"Others...."
      },
      {
         "Brand":"Samsung",
         "Cat":"1969003809",
         "ModelName":"Note7",
         "Price":"67000",
         "Warranty":"1",
         "Other":"Others...."
      },
      {
         "Brand":"Samsung",
         "Cat":"1969003809",
         "ModelName":"Note7",
         "Price":"67000",
         "Warranty":"1",
         "Other":"Others...."
      },
      {
         "Brand":"Tefal",
         "Cat":"1969003709",
         "ModelName":"Utug",
         "Price":"10001",
         "Warranty":"1",
         "Other":"Others...."
      }
   ]
}';

// Create Http context details
$contextData = array ( 
                'method' => 'POST',
                'header' => "Connection: close\r\n".
                            "Content-Length: ".strlen($query)."\r\n",
                'data'=> $string );
 
// Create context resource for our request
$context = stream_context_create (array ( 'http' => $contextData ));
 
// Read page rendered as result of your POST request
$result =  file_get_contents (
                  'http://panasonic.ru.xsph.ru/send.php',  // page url
                  false,
                  $context);
 
// Server response is now stored in $result variable so you can process it