<?php
#require_once('../../includes/helper.php');
#deny_direct_access();
    //require_once __DIR__.'/../classes/Database.php';
    include("../../db_inc1.php");

    $strAction = $_REQUEST['action'];

    
    switch( $strAction ) {
        case 'listAllEDocumentOwners':
            listAllEDocumentOwners($db);
            break;
    }

    function listAllEDocumentOwners($db) {
        $strSqlUsers = 'SELECT
                            *
                        FROM
                            e_document_owner
                        WHERE
                            e_document_owner_id NOT IN (9)
                        ORDER BY
                            e_document_owner_id';
        $objPdoStatement = $db->prepare($strSqlUsers);
        $objPdoStatement->execute();
        $arrmixUsers = ( array ) $objPdoStatement->fetchAll(PDO::FETCH_ASSOC);

       // $db = NULL;

        $strResponse = [];
        $strResponse['data'] = $arrmixUsers;

        echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
    }
?>
