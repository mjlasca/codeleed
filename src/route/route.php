<?php
include("../../SQL.php");
include("../services/AccessRequest.php");
include("../controller/LeedsController.php");
include("../controller/AdminLeedsController.php");
require '../../vendor/autoload.php';


$validateRequest = new AccessRequest();
$validateRequest->isLocalRequest();

$input = json_decode(file_get_contents('php://input'), true);
if(isset($input['code_leed'])){
	$codeLeeds = new LeedsController();
	$codeLeeds->validateCode($input['code_leed']);
}
if(isset($input['get_code'])){
    $admin = new AdminLeedsController();
    $admin->getLeedCode($input['get_code']);
}
if(isset($input['codeDelete'])){
    $admin = new AdminLeedsController();
    $admin->deleteCode($input['codeDelete']);
}
if(isset($input['code'])){
    $admin = new AdminLeedsController();
    $admin->createOrUpdateList($input);
}
if(isset($input['sendmail'])){
    $admin = new AdminLeedsController();
    $admin->sendMail($input);
}
if(isset($_GET['list_code'])){
	$admin = new AdminLeedsController();
	$admin->listCode($_GET['search']);
}
