<?php
class LeedsController {

    public function __construct() {
       
    }

    /**
     * validate code db
     * @param $code
     * @return json_encode
     */
    public function validateCode($code) {
        $ip = $_SERVER['REMOTE_ADDR'];
        var_dump($ip);
        exit;
        $db = new SQL();
		$result = $db->query("SELECT id,code,category,url_redirect FROM codeleed WHERE code = '$code'");
		if($result !== FALSE){
			header('Content-Type: application/json');
			echo json_encode($result);
		}else{
			header('Content-Type: application/json');
			echo json_encode(['msg' => false]);
		}
    }
}
