<?php
session_start();
// Common Function
function deny_direct_access()
{
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
        $result = [
            'message' => 'Direct access not allowed'
        ];
        die(json_encode($result));
    }
}

function generate_token()
{
    unset($_SESSION['token']);
    $_SESSION['token'] = hash('sha256', time() . rand(100000, 999999));
    return $_SESSION['token'];
}

function validate_token($token)
{
    if ($token == $_SESSION['token']) {
        return true;
    }
    return false;
}

// $access_token = (isset($_POST['ouertokenkey']) && $_POST['ouertokenkey'] == '446c1b3be6e73086c35e0f6e94f499ce965da4201c352809db5083a6952b0d66')?$_POST['srfCaseStatus']:'';
// if(empty($access_token)){
// echo "Forbidden";
// die;
// }
