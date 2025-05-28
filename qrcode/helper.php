<?php
function appendqrcode($pdf,$file,$image,$uploadDir_appended_qr_pdf,$filePath_appended_qr_pdf,$append_type=''){
    //echo $filePath_appended_qr_pdf; die;
    if(file_exists($file)){ 
        $pagecount = $pdf->setSourceFile($file); 
    }else{ 
        die('Source PDF not found!'); 
    } 
     
    // Add watermark image to PDF pages 
    for($i=1;$i<=$pagecount;$i++){ 
        $tpl = $pdf->importPage($i); 
        $size = $pdf->getTemplateSize($tpl); 
        $pdf->addPage(); 
        $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE); 
         
        //Put the watermark 
        if($append_type == 'first'){
            if($i == 1){
                $xxx_final = ($size['width']-205); 
                $yyy_final = ($size['height']-270); 
                $pdf->Image($image, $xxx_final, $yyy_final, 0, 0, 'png');
            }
        }else if($append_type == 'last'){
            if($i == $pagecount){
                $xxx_final = ($size['width']-205); 
                $yyy_final = ($size['height']-270); 
                $pdf->Image($image, $xxx_final, $yyy_final, 0, 0, 'png');
            }
        }else{
            $xxx_final = ($size['width']-205); 
            $yyy_final = ($size['height']-270); 
            $pdf->Image($image, $xxx_final, $yyy_final, 0, 0, 'png');
        } 
    } 
    
    if(!file_exists($uploadDir_appended_qr_pdf)){
        mkdir($uploadDir_appended_qr_pdf, 0777, true);
    }
    // Output PDF with watermark 
   // $pdf->Output();
    $pdf->Output('F', $filePath_appended_qr_pdf);
}

function appendqrcode_second($pdftk,$pdf,$filePath_qr,$filePath_qr_pdf,$filePath_qpdf_with_qr){
    $pdf->AddPage();
	$pdf->Image($filePath_qr,6,10,15,15);
	$pdf->Output('F', $filePath_qr_pdf, true);


	$result = $pdftk->background($filePath_qr_pdf)
		->saveAs($filePath_qpdf_with_qr);
	if ($result === false) {
		$error = $pdftk->getError();
		echo $error; die;
	} 
}


function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "https";
    }
    else{
        $protocol = 'https';
    }
    return $protocol . "://" . $_SERVER['SERVER_NAME'];;
}



?>
