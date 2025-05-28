<?php
#require_once('../../includes/helper.php');
#deny_direct_access();
include("../../db_inc1.php");

    $strAction = $_REQUEST['action'];
    $strSchemaName = $_REQUEST['schema_name'];
   
    switch( $strAction ) {
        case 'listAllDocumentTypes':
            listAllDocumentTypes($db);
            break;
    }

    function listAllDocumentTypes($db) {
        $strSchemaName = $_REQUEST['schema_name'];
        $filingNumber=$_REQUEST['filling_no'];
        $strSqlJoin = NULL;
        $strSqlWhere = NULL;

       
        if( isset( $_REQUEST['doc_owner'] ) && !empty( $_REQUEST['doc_owner'] ) ) {
		$strFiledBy = $_REQUEST['doc_owner'];
		if($strFiledBy != ''){
             $partyTypes = array_map('trim', explode(',', $strFiledBy));
            $quotedPartyTypes = array_map(function($type) {
                return "'" . addslashes($type) . "'";
            }, $partyTypes);
            $partyTypeList = implode(',', $quotedPartyTypes);
	    $strSqlWhere  = ' WHERE du.party_type IN ( ' .$partyTypeList. ')';
		}
            $strSqlWhere .= ' AND du.filing_no =' . "'$filingNumber'" ;
            $strSqlJoin .= ' JOIN e_document_owner edo ON ( edo.e_document_owner_id = edt.e_document_owner_id )';
            $strSqlJoin .= ' JOIN document_upload du ON ( du.subdoctype = edt.e_document_type )';
        }
        $strSqlDocumentTypes = "SELECT
                                    DISTINCT( e_document_name )
                                    e_document_type,
                                    e_document_name
                                FROM
                                    e_document_type edt " .$strSqlJoin . $strSqlWhere . "
                            ORDER BY
                                e_document_name";
                   
                  

        $objPdoStatement = $db->prepare($strSqlDocumentTypes);
        $objPdoStatement->execute();
        $arrobjDocumentTypes = ( array ) $objPdoStatement->fetchAll(PDO::FETCH_ASSOC);

       // $db = NULL;

        $strResponse = [];
        $strResponse['data'] = $arrobjDocumentTypes;
        echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
    }
?>
