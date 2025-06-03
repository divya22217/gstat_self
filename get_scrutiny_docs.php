<?php 


include("db_inc1.php");
$filing_no = $_POST['filing_no'];
$query = "select documentuploadmodelid,fileupload,filename,docum_type from document_upload where filing_no = ?";
$docs_query = $db->prepare($query);
$docs_query->bindParam(1, $filing_no, PDO::PARAM_STR);
$docs_query->execute();
$docs = $docs_query->fetchAll();

foreach($docs as $k=>$v){ ?>
	<input type="checkbox" id="doc_<?php echo $v['documentuploadmodelid'] ?>" name="docs[]" value="<?php echo $v['documentuploadmodelid'] ?>">
  <label for="vehicle1"> <?php echo $v['docum_type'].'  /  '.$v['filename']; ?></label><br>
	
<?php }
?>