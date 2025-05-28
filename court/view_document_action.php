<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
$path = $_REQUEST['path'];
$path = urldecode($_REQUEST['path']);
if (!empty($path) && strstr($path, '/NCLAT_Documents/ncltdoc/casedoc/')) {

 ?>
	<span style='float:right;margin-right:25px;'></span>
<iframe  src="https://efiling.nclat.gov.in/nclat/scrutiny/readpdf.php?path=<?php echo urlencode($path); ?>" style='width:100%;height:500px;' allowfullscreen></iframe>
<?php die; 
/* $filePath=$path;
$filename="abc.pdf";
header('Content-type:application/pdf');
header('Content-disposition: inline; filename="'.$filename.'"');
header('content-Transfer-Encoding:binary');
header('content-Length: ' . filesize($filePath));
header('Accept-Ranges:bytes');
@ readfile($filePath);
exit; */
 $ext = pathinfo($path,PATHINFO_EXTENSION);
	if($ext == 'pdf') { 
	$localFilePath = '../scrutiny/defects.pdf';
$fpath = '/scrutiny/defects.pdf';
$serverfile = $path;

$serverfile = $path;
$res = file_get_contents($path);
file_put_contents($localFilePath,$res);
	?>
	<span style='float:right;margin-right:25px;'></span>
<iframe  src="https://efiling.nclat.gov.in/nclat<?php echo $fpath; ?>" style='width:100%;height:500px;' allowfullscreen></iframe>

<?php } 

} else{
echo "not found!";
}

?>
