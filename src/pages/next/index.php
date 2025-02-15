<?php
include("../../controller/AdminLeedsController.php");
include("../../../vendor/autoload.php");
if(isset($_GET['utm_m'])){
	$admin = new AdminLeedsController();
    $admin->createUserMailerLite( base64_decode($_GET['utm_m']), urldecode($_GET['utm_url']));
}
