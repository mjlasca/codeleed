<?php
class AdminLeedsController {

    public function __construct() {
       
    }

    /**
     * validate code db
     * @return json_encode
     */
    public function listCode(){
        $db = new SQL();
		$result = $db->query("SELECT * FROM codeleed ORDER BY code DESC");
		if($result !== FALSE){
			header('Content-Type: application/json');
			echo json_encode($result);
		}else{
			header('Content-Type: application/json');
			echo json_encode(['msg' => false]);
		}
    }


    public function createOrUpdateList($data){
        header('Content-Type: application/json');
        $db = new SQL();
		$result = $db->query("SELECT COUNT(1) as cant FROM codeleed WHERE code = '".$data["code"]."'");
		if(isset($result[0]['cant']) && $result[0]['cant'] == 1){
            $res = $db->query("UPDATE codeleed SET category='".$data["category"]."' ,url_redirect='".$data["url_redirect"]."' WHERE code='".$data["code"]."'");
            var_dump($res);
            if($res !== FALSE){
                echo json_encode(['success' => true]);
                return;
            }
		}else{
            $res = $db->query("INSERT INTO codeleed(code,category,url_redirect) VALUES('".$data["code"]."','".$data["category"]."','".$data["url_redirect"]."')");
            var_dump($res);
            if($res !== FALSE){
                echo json_encode(['success' => true]);
                return;
            }
		}
        echo json_encode(['success' => false]);
    }
}
