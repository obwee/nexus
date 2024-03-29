<?php
// Middleware for AJAX requests.

include_once 'utils/autoload.php';

$sClassName = $_REQUEST['class'];
$sAction = $_REQUEST['action'];
$sFile = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/class/Controllers/class.' . $sClassName . '.php';

$aResult =    array(
    'result' => false,
    'msg'    => 'Invalid request.'
);

// Check if class exists.
if (isset($_REQUEST['class']) === false || file_exists($sFile) === false) {
    // Return error message.
    echo json_encode($aResult);
    exit;
}

$_POST = (empty($_POST) === false) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);

if (empty($_FILES) === false) {
    $_POST = array_merge($_POST, $_FILES);
}

// Invoke the class.
$oClass = new $sClassName($_POST);

// Check if method exists.
if (method_exists($oClass, $sAction) === false) {
    // Return error message.
    echo json_encode($aResult);
    exit;

}
// Execute the method for the class.
$aResult = $oClass->$sAction();

return $aResult;
