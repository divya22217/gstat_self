<?php

  /*  ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL); */

//try{
	
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 
	
ini_set('memory_limit','-1');

$encoded_path = $_POST['pdfpath'];
$type = (isset($_POST['type']) && $_POST['type'] != '')?$_POST['type']:'';
 $path = urldecode($encoded_path);
 
 if($type == 3){
	$ext = pathinfo($path,PATHINFO_EXTENSION);
	if($ext == 'pdf') { 
	$localFilePath = '../doc/0794120000112018-3.pdf';
	$fpath = '/doc/0794120000112018-3.pdf';
	$serverfile = $path;
	//echo $path;
	$res = file_get_contents($path);
	file_put_contents($localFilePath,$res);
	 ?>
	<iframe  src="https://efiling.nclat.gov.in/nclat/<?php echo $fpath; ?>" style='width:100%;height:500px;' allowfullscreen></iframe>
	<?php
		}
 
 } else{
 
 if($type == ''){
	$id = "close_pdf";
}else{
	$id = "close_pdf_new";
}
 
 $ext = pathinfo($path,PATHINFO_EXTENSION);
	if($ext == 'pdf') { 
	$localFilePath = '../scrutiny/defects.pdf';
$fpath = '/scrutiny/defects.pdf';
$serverfile = $path;
//echo $path;
if(startsWith($path,"/casedoc")) {
    $res = file_get_contents('../'.$path);
	$new_path = urlencode('../'.$path);
}
else {
	$new_path = urlencode($path);
    $res = file_get_contents($path); 
}

file_put_contents($localFilePath,$res);
 ?>
<span style='float:right;margin-right:25px;'><button type="button" id="<?php echo $id; ?>" class="btn-danger">&times;</button></span>
<!--<iframe  src="https://ngtonline.nic.in/ngt<?php echo $fpath; ?>" style='width:100%;height:100%;' allowfullscreen></iframe>-->
<iframe  src="https://efiling.nclat.gov.in/nclat/scrutiny/readpdf_file.php?path=<?php echo $new_path; ?>" style='width:100%;height:100%;' allowfullscreen></iframe>
<?php
	} else {
		
		$localFilePath = '../doc/images.jpg';
$fpath = '/doc/images.jpg';
$serverfile = $path;
$res = file_get_contents($path);
file_put_contents($localFilePath,$res);
	?>
	<span style='float:right;margin-right:25px;'><button type="button" id="<?php echo $id; ?>" class="btn-danger">&times;</button></span>
<iframe  src="../<?php echo $fpath; ?>" style='width:100%;height:100%;' allowfullscreen></iframe>
	
 <?php } } ?>
