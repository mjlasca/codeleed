<?php
require 'MailerLiteConnect.php';
class AdminLeedsController {

    public function __construct() {
       
    }

    /**
     * validate code db
     * @return json_encode
     */
    public function listCode($search = ''){
        $db = new SQL();
		$result = $db->query("SELECT * FROM codeleed ORDER BY code ASC");
        if(!empty($search))
            $result = $db->query("SELECT * FROM codeleed WHERE code = '".$search."' ORDER BY code ASC");
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
        try{
            if(isset($result[0]['cant']) && $result[0]['cant'] == 1 && !empty($data["edit"])){
                $res = $db->query("UPDATE codeleed SET category='".$data["category"]."'
                                         ,url_redirect='".$data["url_redirect"]."'
                                         ,url_download='".$data["url_download"]."'
                                          WHERE code='".$data["code"]."'");
                if($res !== FALSE){
                    echo json_encode(['success' => true]);
                    return;
                }
            }else if(empty($data['edit'])){
                $res = $db->query("INSERT INTO codeleed(code,category,url_redirect,url_download) VALUES('".$data["code"]."','".$data["category"]."','".$data["url_redirect"]."','".$data["url_download"]."')");
                if($res !== FALSE){
                    echo json_encode(['success' => true]);
                    return;
                }
            }
        }catch (Exception $ex){
            echo json_encode(['success' => false, 'msg' => $ex->getMessage()]);
            return;
        }
		
        echo json_encode(['success' => false]);
    }

    /**
     * get register code db
     * @param $code
     * @return json_encode
     */
    public function getLeedCode($code) {
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

    /**
     * delete code db
     * @param $code
     * @return json_encode
     */
    public function deleteCode($code) {
        $db = new SQL();
		$result = $db->query("DELETE FROM codeleed WHERE code = '$code' ");
		if($result !== FALSE){
			header('Content-Type: application/json');
			echo json_encode($result);
		}else{
			header('Content-Type: application/json');
			echo json_encode(['msg' => false]);
		}
    }

    /**
     * send mail mailerlite
     * @param $data
     *  array with data
     */
    public function sendMail($data) {
        $db = new SQL();
		$result = $db->query("SELECT code, url_download FROM codeleed WHERE code = '".$data['code_send']."' ");
		if($result !== FALSE){
            $url = $result[0]["url_download"];
            $mailerLite = new MailerLiteConnect();
            if($mailerLite->validateUser($data['mail_send'],$url) == TRUE){
                header('Content-Type: application/json');
                echo json_encode(['msg' => 'Usuario agregado con éxito','success' => true]);
                return;
            }else{
                header('Content-Type: application/json');
                echo json_encode(['msg' => 'Hubo un error al enviar los datos','success' => false]);
                return;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['msg' => 'No hay coincidencia con el código','success' => false]);
    }
}
