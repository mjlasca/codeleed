<?php
include("../../controller/AdminLeedsController.php");
include("../../../vendor/autoload.php");
if(isset($_GET['utm_m'])){
	$admin = new AdminLeedsController();
    $admin->createUserMailerLite( $_GET['utm_m'], urldecode($_GET['utm_url']), $_GET['utm_category'], $_GET['utm_source']);
}
