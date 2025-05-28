<?php
if (!function_exists('block_spacialcahr')) {
	function block_spacialcahr($str,$actual_link)
	{   
		 if(!in_array($actual_link,array('mis/add_causelist_remark_action.php','court/order_creation_bulk_ind_action.php'))){
			$str1 = trim(html_entity_decode(html_entity_decode($str)));
			$arrayrestricfirst = array("=", "+", "-", "@", "0x09", "0x0D");
			// echo $str1[0];
			if (!empty($str1) && (preg_match("/[><]=?[\d]*/", $str1) or in_array($str1[0], $arrayrestricfirst))) {
				return false;
			} else {
				return true;
			}
		 }else{
		 	return true;	
			}
		
	}
}
// function block_spacialcahr($str)
// {
// 	$str1 = $str; //trim(html_entity_decode(html_entity_decode($str)));
// 	$arrayrestricfirst = array("=", "+", "-", "@", "0x09", "0x0D", "'");
// 	if (!empty($str1) && (preg_match("/[><]=?[\d]*/", $str1) or in_array($str1[0], $arrayrestricfirst))) {
// 		//echo "test1";          
// 		return false;
// 	} else {
// 		//echo "test2"  ;          
// 		return true;
// 	}
// }
$uri_request = $_SERVER['REQUEST_URI'];
$url_array = explode('?', $uri_request);
$actual_link = str_replace("/gstat/","",$uri_request);
if (is_array($url_array) && !empty($url_array[1])) {
	$parameters = $url_array[1];
	$parameters_array = @explode('&', $parameters);
	for ($i = 0; $i < count($parameters_array); $i++) {
		$getPara_array = @explode("=", $parameters_array[$i]);
		$paraName = $getPara_array[0];
		$getPvalue = $getPara_array[1];
		$_REQUEST[$paraName] = htmlentities(htmlspecialchars($getPvalue));
	}
}
$request_array = $_REQUEST;
foreach ($request_array as $key => $value) {

	if (!is_array($value)) {
		if (block_spacialcahr($value,$actual_link)) {
			// $_REQUEST[$key] = htmlentities(htmlspecialchars($value));
		} else {
			echo $value;
			die("invalid value in requestn :" . $key);
		}
	} else {
		foreach ($value as $key2 => $value2) {
			if (block_spacialcahr($value2,$actual_link)) {
				// $_REQUEST[$key][$key2] = htmlentities(htmlspecialchars($value2));
			} else {
				echo $value2;
				die("invalid value in requesta:" . $key2);
			}
		}
	}
}


$post_array = $_POST;
foreach ($post_array as $key => $value) {
	if (!is_array($value)) {
		if (block_spacialcahr($value,$actual_link)) {
		} else {
			die("invalid  post value  in :" . $key);
		}
	} else {
		foreach ($value as $key2 => $value2) {
			if (block_spacialcahr($value2,$actual_link)) {
				// $_POST[$key][$key2] = htmlentities(htmlspecialchars($value2));
			} else {
				die("invalid  post value in :" . $key2);
			}
		}
	}
}

//print_r($post_array);

$get_array = $_GET;
foreach ($get_array as $key => $value) {
	if (!is_array($value)) {
		if (block_spacialcahr($value,$actual_link)) {
			//$_POST[$key] = htmlentities(htmlspecialchars($value));
		} else {
			die("invalid get value in :" . $key);
		}
	} else {
		foreach ($value as $key2 => $value2) {
			if (block_spacialcahr($value2,$actual_link)) {
				//$_POST[$key][$key2] = htmlentities(htmlspecialchars($value2));
			} else {
				die("invalid  get value in :" . $key2);
			}
		}
	}
}
