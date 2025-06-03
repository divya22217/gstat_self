<?php
    error_reporting(0);
    $uri_request=$_SERVER['REQUEST_URI'];
    $url_array=explode('?',$uri_request);
    $parameters=$url_array[1];
    $parameters_array=explode('&',$parameters);
    $spcl_char=["'"=>'&#39;','"'=>'','<'=>'','>'=>'','alert'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','#'=>'','!'=>'','%'=>'','$'=>'','^'=>'','*'=>''];
    $aa = 0;
    for($i=0; $i < count($parameters_array); $i++) :;
        $getPara_array=explode("=",$parameters_array[$i]);
        $paraName=$getPara_array[0];
        $getPvalue=$getPara_array[1];
        $_REQUEST[$paraName]=htmlspecialchars($getPvalue);
        $aa = 1;
    endfor;
    if($aa > 0):;
        foreach($_GET as $key=>$val):;
                if(is_array($val)){
                        foreach($val as $key1=>$val1):;
                                if(is_array($val1)){
                                        foreach($val1 as $key2=>$val2):;
                                                if(is_array($val2)) {
                                                        $innerData[$key1][$key2]=$val2;
                                                }
                                                else    $innerData[$key1][$key2]=strtr($val2,$spcl_char);
                                        endforeach;
                                }
                                else $innerData[$key1]=strtr($val1, $spcl_char);
                        endforeach;
                        $_GET[$key]=$innerData;
                }
                else $_GET[$key]=strtr($val,$spcl_char);
        endforeach;
    endif;

    foreach($_POST as $key=>$val):;
            if(is_array($val)){
                    foreach($val as $key1=>$val1):;
                            if(is_array($val1)){
                                    foreach($val1 as $key2=>$val2):;
                                            if(is_array($val2)) {
                                                    $innerData[$key1][$key2]=$val2;
                                            }
                                            else    $innerData[$key1][$key2]=htmlspecialchars(strtr($val2, $spcl_char));
                                    endforeach;
                            }
                            else $innerData[$key1]=htmlspecialchars(strtr($val1, $spcl_char));
                    endforeach;
                    $_POST[$key]=$innerData;
            }
            else $_POST[$key]=htmlspecialchars(strtr($val, $spcl_char));
    endforeach;
    ?>
