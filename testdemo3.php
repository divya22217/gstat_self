
<?php

ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://10.193.85.11/efiling/getdataapl02b.drt?filingNo=2025307201000216&schema=delhipb',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
   // 'token: RJGk1ZXc6nDkrQxn0klNHJKYSqXcjk3',
    'Cookie: SERVERID=gst_bk_efile_91'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response."hjlk";

?>
