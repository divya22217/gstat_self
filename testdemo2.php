<?php 


// echo date('d/m/Y',strtotime(date('Y-m-d')));
// $url = 'https://uat-efiling.gstat.gov.in/efiling/getdataapl02b.drt?filingNo=2025307201000216&schema=delhipb';
// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_URL => $url,
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Cookie: SERVERID=gst_bk_efile_91'
//   ),
// ));
// $response = curl_exec($curl);
// curl_close($curl);
// echo $response;


function test($url) { 
	$curl = curl_init();
 curl_setopt_array($curl, array(
   CURLOPT_URL => $url,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => '',
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 0,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => 'GET',
   CURLOPT_HTTPHEADER => array(
     'Cookie: SERVERID=gst_bk_efile_91'
   ),
 ));
 $response = curl_exec($curl);
curl_close($curl);
 echo $response;

//    $command = "curl -s $url > /dev/null &";
  //  exec($command);
}

echo $url = "https://10.193.85.33/efiling/getdataapl02b.drt?filingNo=2025307201000216&schema=delhipb";
$res = test($url);
echo "<br/> response : ".$res;

?>
