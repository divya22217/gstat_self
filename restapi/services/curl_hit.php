<?php

// Store the cipher method 
$ciphering = "AES-128-CTR"; 
// Use OpenSSl Encryption method 
$iv_length = openssl_cipher_iv_length($ciphering); 
$options = 0; 

$encryption_iv = '1234567891011121'; 
  
// Store the encryption key 
$encryption_key = "encrypt"; 
//$search_type = 'initial';
$search_type = 'get_case_info';





if($search_type == 'get_case_info'){
	$case_no = '1';
	$case_type = '32';
	$case_year = '2021';
	$location = '10';
	$search_type_encrypted = openssl_encrypt($search_type, $ciphering,$encryption_key, $options, $encryption_iv); 
	/* $case_no = openssl_encrypt($case_no, $ciphering,$encryption_key, $options, $encryption_iv); 
	$case_type = openssl_encrypt($case_type, $ciphering,$encryption_key, $options, $encryption_iv);
	$case_year = openssl_encrypt($case_year, $ciphering,$encryption_key, $options, $encryption_iv);
	$location = openssl_encrypt($location, $ciphering,$encryption_key, $options, $encryption_iv); */
	$params = array('search_type' => $search_type,'case_no' => $case_no,'case_year' => $case_year,'case_type' => $case_type,'location' => $location);
}

if($search_type == 'initial'){
	//$search_type_encrypted = openssl_encrypt($search_type, $ciphering,$encryption_key, $options, $encryption_iv); 
	$params = array('search_type' => $search_type);
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://efiling.nclat.gov.in/nclat/restapi/services/case_info.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $params,
  CURLOPT_HTTPHEADER => array(
    'token: 3fgrrg9w14QY9wwnmVhLE0Wg61m1JfRp4rs_MQz6bpcJnrFUDwp5lPPFCbkKlDiQ9XY3ZIP8zAGCsS8ruN2uKjIaIargXds4545vdferfe4fS'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
