<?php

$path = "/Efile_Document/CIS_Document/defects/0710102117902019-1.pdf";
$res = file_get_contents($path);
file_put_contents('abc.pdf',$res);
echo $res;

?>