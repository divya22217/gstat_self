<?php
#require_once('../../includes/helper.php');
#deny_direct_access();
    session_start();
    include("../../db_inc1.php");
    require_once('../../object_storage/S3Service.php');


    const MENU_ACCESS_NAME = 'LISTING';

    $arrmixAllHeaders   = getallheaders();
   
    //TODO: uncomment below code
    $intUserId = ( !empty( $_REQUEST['userId'] ) ) ? $_REQUEST['userId'] : '';

    if( !isset( $_REQUEST['filing_no'] ) || empty($_REQUEST['filing_no'] ) ) {
        $strResponse['data']['error'] = 'Invalid filing number';
        echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
        exit;
    }

    if( !isset( $_REQUEST['schema_name'] ) || empty($_REQUEST['schema_name'] ) ) {
        $strResponse['data']['error'] = 'Something Went Wrong!';
        echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
        exit;
    }

    $arrmixPostedFormData   = $_POST;
    $strAction              = 'list';
    $intFilingNo            = $_REQUEST['filing_no'];
    $strSchemaName          = $_REQUEST['schema_name'];

    switch( $strAction ) {
        case 'list':
            listDocumentsData( $intUserId, $db, $arrmixPostedFormData, $intFilingNo, $strSchemaName );
            break;
        default:
            listDocumentsData( $intUserId, $db, $arrmixPostedFormData, $intFilingNo, $strSchemaName );
    }

    function validateSearchFilter( $arrmixPostedFormData ) {
        if( !empty( $arrmixPostedFormData['uploaded_date_from'] )
            && empty( $arrmixPostedFormData['uploaded_date_to'] ) ) {
            $strResponse['data']['error'] = "Uploaded date to is required.";
            echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
            return false;
        }

        if( empty( $arrmixPostedFormData['uploaded_date_from'] )
            && !empty( $arrmixPostedFormData['uploaded_date_to'] ) ) {
            $strResponse['data']['error'] = "Uploaded date from is required.";
            echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
            return false;
        }

        if( !empty( $arrmixPostedFormData['uploaded_date_from'] )
            && !empty( $arrmixPostedFormData['uploaded_date_to'] )
            && strtotime( $arrmixPostedFormData['uploaded_date_from'] )
            > strtotime( $arrmixPostedFormData['uploaded_date_to'] ) ) {
            $strResponse['data']['error'] = "Invalid date range.";
            echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
            return false;
        }

        return true;
    }

    function listDocumentsData( $intUserId, $db, $arrmixPostedFormData = [],
                $intFilingNo = NULL, $strSchemaName = NULL ) {
        if( !validateSearchFilter( $arrmixPostedFormData ) ) {
            exit;
        }
    
            $strSqlCaseDetails = "SELECT
            *,
            du.e_reference_no,
            CASE WHEN cd.list_with_defect = '0'
            THEN 
            CONCAT( ct.case_type_desc, ' No. ', cd.case_no,
            '/', cd.case_year )
            WHEN cd.list_with_defect = '1'
            THEN CONCAT( ct.case_type_desc, ' No. ', 'D', cd.case_no,
             '/', cd.case_year )
            END 
            formated_Case_number
            FROM
            $strSchemaName.case_Detail cd
            JOIN case_type ct ON ( cd.case_type = ct.id )
            LEFT JOIN document_upload du ON ( cd.filing_no = du.filing_no )
            WHERE
            cd.filing_no = " . "'$intFilingNo'";                    
                               
            $objPdoStatement = $db->prepare($strSqlCaseDetails);
            $objPdoStatement->execute();

            $mixCaseDetailRow = $objPdoStatement->fetch(PDO::FETCH_ASSOC);
          
            if( empty( $mixCaseDetailRow ) ) {
                $strResponse['data']['error'] = "No Case details found";
                echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
                exit();
            }

            $arrmixUploadedDocuments['case_detail'] = $mixCaseDetailRow;
          
            $strSqlJoin = NULL;
            
            $strSqlNoticesJoin = NULL;
            $strSqlDailyOrdersJoin = NULL;
            $strSqlDefectsJoin = NULL;
            $strSqlOfficeRemarksJoin = NULL;

            $strUploadedDateFrom = isset( $arrmixPostedFormData['uploaded_date_from'] )
                                    ? $arrmixPostedFormData['uploaded_date_from'] : '';
            $strUploadedDateTo = isset( $arrmixPostedFormData['uploaded_date_to'] )
                                    ? $arrmixPostedFormData['uploaded_date_to'] : '';
            $strFiledBy = isset( $arrmixPostedFormData['filed_by'] )
                                    ? $arrmixPostedFormData['filed_by'] : '';
            $strDocumentTypes = isset( $arrmixPostedFormData['document_type'] ) ?
                                    $arrmixPostedFormData['document_type'] : '';

            if( !empty( $strUploadedDateFrom ) || '' != $strUploadedDateFrom ) {
                $strSqlJoin .= ' AND ( du.document_filed_date::DATE >= ' . "'$strUploadedDateFrom'::DATE" .
                ' AND du.document_filed_date::DATE <= ' . "'$strUploadedDateTo'::DATE" . ')';

                $strSqlNoticesJoin .= ' AND ( ncd.notice_date::DATE >= ' . "'$strUploadedDateFrom'::DATE" .
                ' AND ncd.notice_date::DATE <= ' . "'$strUploadedDateTo'::DATE" . ')';

                $strSqlDefectsJoin .= ' AND ( od.entry_dt::DATE >= ' . "'$strUploadedDateFrom'::DATE" .
                ' AND od.entry_dt::DATE <= ' . "'$strUploadedDateTo'::DATE" . ')';

                $strSqlDailyOrdersJoin .= ' AND ( od.order_date::DATE >= ' . "'$strUploadedDateFrom'::DATE" .
                ' AND od.order_date::DATE <= ' . "'$strUploadedDateTo'::DATE" . ')';

                $strSqlOfficeRemarksJoin .= ' AND ( rcd.remark_listing_date::DATE >= ' . "'$strUploadedDateFrom'::DATE" .
                ' AND rcd.remark_listing_date::DATE <= ' . "'$strUploadedDateTo'::DATE" . ')';
            }

            if( !empty( $strFiledBy ) || '' != $strFiledBy || NULL != $strFiledBy ) {
                $strImplodedFiledBy = "'" . str_replace(",", "','", $strFiledBy) . "'";
                $strSqlJoin .= ' AND ecp.party_flag IN ( ' . $strImplodedFiledBy . ')';

		if( 'O' == $strFiledBy ) {
                    $strSqlDailyOrdersJoin .= 'AND od.order_type IN ( \'D\', \'F\' )';
                }
            }
           
            if( !empty( $strDocumentTypes ) || '' != $strDocumentTypes ) {
                $strImplodedDocumentTypes = "'" . str_replace(",", "','", $strDocumentTypes) . "'";
                $strSqlJoin .= ' AND du.docum_type IN ( ' . $strImplodedDocumentTypes . ')';
                $strSqlNoticesJoin .= ' AND st.name IN ( ' . $strImplodedDocumentTypes . ')';
                // $strSqlDefectsJoin .= ' AND du.docum_type IN ( ' . $strImplodedDocumentTypes . ')';
                $strSqlDailyOrdersJoin .= ' AND mot.name IN ( ' . $strImplodedDocumentTypes . ')';
                $strSqlOfficeRemarksJoin .= ' AND rt.name IN ( ' . $strImplodedDocumentTypes . ')';
            }

            $strUnionNoticesJoin = NULL;
            $strSqlDailyOrders = NULL;
            $strOrderBy = ' ORDER BY filing_date';
            $strSqlOfficeRemarks = NULL;
          
/// For Orders
            if( NULL == $strFiledBy || '' == $strFiledBy || in_array( 'O', explode( ',', $strFiledBy ) ) ) {
            $strSqlDailyOrders .= " UNION ALL
                                    SELECT DISTINCT ON (od.order_date)
                                    od.pdf_path AS file_upload_path,
                                    od.filename AS filename_path,
                                    od.filename AS document_name,
                                    CASE 
                                        WHEN od.order_type = 'D' THEN 'Daily Order'
                                        WHEN od.order_type = 'F' THEN 'Final Order'
                                        ELSE 'Unknown'
                                    END AS document_type,
                                    od.order_date AS filing_date,
                                    'Appeal' AS filed_in,
                                    '' AS formatted_case_number,
                                    '-' AS filed_by_name,
                                    '' AS filed_by_party,
                                    '' AS notice_html,
                                COALESCE(uc.username, od.user_id::VARCHAR) AS username
                                FROM delhipb.order_daily od
                                LEFT JOIN users_cis uc ON od.user_id::int4 = uc.id
                                WHERE
                                    od.filing_no = '{$intFilingNo}'
                                {$strSqlDailyOrdersJoin}
                                ";
            }

            $strSqlDocumentDetails = "SELECT
                                        du.fileupload as file_upload_path,
                                        du.filename as filename_path,
                                        CONCAT( du.original_file, '' ) as document_name,
                                        CONCAT(du.docum_type, '') as document_type,
                                        du.created_at as filing_date,
                                        CASE
                                            WHEN du.miscellaneous_ref_no IS NULL THEN 'Appeal'
                                            WHEN ( ( du.miscellaneous_ref_no IS NOT NULL
                                                        OR du.miscellaneous_ref_no != '')
                                                    AND  ( du.miscellenous_no IS NOT NULL
                                                            OR du.miscellenous_no != '') ) THEN '-'
                                        END filed_in,
                                        CASE
                                            WHEN cd.list_with_defect='0'
                                                THEN CONCAT( cd.case_no, '/', cd.case_year )
                                            WHEN cd.list_with_defect= '1'
                                                THEN CONCAT( 'D', cd.case_no, '/', cd.case_year )
                                        END formated_Case_number,
                                        ecp.name as filed_by_name,
                                        CONCAT( ' ( ', ecp.party_flag, ecp.party_serial_no, ' ) ' ) as filed_by_party,
                                        '' as notice_html,
                                        CONCAT(esu.name, '' ) as username
                                    FROM 
					document_upload du
					LEFT JOIN LATERAL (
                                            SELECT * FROM e_cases_party 
                                            WHERE filing_no = du.filing_no AND party_flag = du.party_type 
                                            LIMIT 1
                                        )ecp ON true
                                        JOIN $strSchemaName.case_Detail cd ON ( du.filing_no = cd.filing_no )
                                      
                                        LEFT JOIN loginmodel lm ON ( du.loginid::varchar = lm.loginid::varchar )
                                        LEFT JOIN e_sign_up esu ON ( lm.loginidgenerated = esu.loginidgenerated )
                                      
                                    WHERE
                                        ( du.deleted_date IS NULL OR du.deleted_date = '' )
                                        AND du.filing_no = " . "'$intFilingNo'" .
                                        $strSqlJoin . $strUnionNoticesJoin . $strSqlDailyOrders . $strSqlOfficeRemarks . $strOrderBy . ' ';
 
  //print_r($strSqlDocumentDetails);die("hello");
                                        // ORDER BY filing_date
            $objPdoStatement = $db->prepare($strSqlDocumentDetails);
            $objPdoStatement->execute();
            //$db = null;
            $arrmixUploadedDocuments['document_Detail'] = [];
            while ($mixDocumentDetailRow = $objPdoStatement->fetch(PDO::FETCH_ASSOC)) {

                /****************** Document file pdf from S3****/

            $path = $mixDocumentDetailRow['file_upload_path'];
          
             if (!empty($path) && (strstr($path, 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/') || strstr($path, 'Efile_Document/gstdoc/casedoc/'))) {
                $encoded_path = urlencode($path);
            $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
			 $domain=rtrim($base_url, '/');
               
		    $url1 = $domain."/gstat/scrutiny/readpdf.php?path=".$encoded_path;
          
             } 
             else{
                $url1=$domain."/gstat/superindex/api/01DSC-Signed-APL05001_1745382399891.pdf";
                
	     }
	  
            // Added File size for uploaded doc 23-04-25//
	    $s3 = new S3Service();
	    $fileSize = $s3->getFileSizeFromS3( $encoded_path);
            if($fileSize == 0)
            {
                $url1=$domain."/gstat/public/filenotfound.html";
                $fileSize = "0 B";
           
	    }    
    
	    $mixDocumentDetailRow =  array_merge($mixDocumentDetailRow,array('doc_path'=>$url1,'doc_size'=>$fileSize,'filing_no'=>$intFilingNo));
           //print_r($mixDocumentDetailRow);die;
            $arrmixUploadedDocuments['document_Detail'][] = $mixDocumentDetailRow;
            }

            $strResponse = [];
            $strResponse['data'] = $arrmixUploadedDocuments;
            echo json_encode($strResponse, JSON_PRETTY_PRINT) . "\n";
    }



    
    
    
   
    
