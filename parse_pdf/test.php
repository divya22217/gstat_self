<?php

include 'vendor/autoload.php';
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('81803706-2020.pdf');
$text = $pdf->getText();
echo $text;


?>