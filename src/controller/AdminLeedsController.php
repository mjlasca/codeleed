<?php
require 'MailerLiteConnect.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdminLeedsController {
    private $mail;
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
            $result = $db->query("SELECT * FROM codeleed WHERE code = '". strtoupper($search)."' ORDER BY code ASC");
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
        $data['code'] = strtoupper($data['code']);
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
        $code = strtoupper($code);
        $db = new SQL();
		$result = $db->query("SELECT id,code,category,url_redirect,url_download FROM codeleed WHERE code = '$code'");
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
        $code = strtoupper($code);
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
        $data['code_send'] = strtoupper($data['code_send']);
        $db = new SQL();
		$result = $db->query("SELECT code, url_download FROM codeleed WHERE code = '".$data['code_send']."' ");
		if($result !== FALSE){
            $this->mail = new PHPMailer(true);
            $this->mail->CharSet = 'UTF-8';
            $email = $data['mail_send'];
            $basic_uri = "http://" . $_SERVER['HTTP_HOST'];
            $subject = "Descarga el archivo con código ".$data['code_send'];

            try {
                // Configuración del servidor SMTP de Gmail
                $this->mail->SMTPDebug = 0; // 0 para desactivar el modo debug
                $this->mail->isSMTP();
                $this->mail->Host = 'smtp.hostinger.com';
                $this->mail->SMTPAuth = true;
                $this->mail->Username = 'yosoy@elautomatizador.io'; // Correo electrónico de Gmail
                $this->mail->Password = '3EALenis@'; // Contraseña de Gmail
                $this->mail->SMTPSecure = 'ssl';
                $this->mail->Port = 465;

                // Destinatarios
                $this->mail->setFrom('yosoy@elautomatizador.io', 'EA - Alfonso Lenis');
                $this->mail->addAddress($email, $email);

                // Contenido del correo electrónico
                $this->mail->isHTML(true);
                $this->mail->Subject = $subject;
                $urlDownload = urlencode($result[0]['url_download']);
                $this->mail->Body = '<center><div style="padding:20px; border-radius: 10px;background-color: #fffbff;border: solid 1px #742575;max-width:450px">
                    <p style="padding:10px; font-size:16px;">A continuación encontrarás el enlace para descargar el archivo que solicitaste. <br>Por favor, haz click en el siguiente enlace:</p>
                    <br>
                    <a style="margin: 10px 0px 10px 0px; background-color: #742575; color: #fff; padding: 20px; font-size: 20px; border-radius: 5px; border: solid #f0f 1px;" href="'.$basic_uri.'/codeleed/src/pages/next/?utm_m='.str_replace("&", "%26", base64_encode($email)).'&utm_url='.$urlDownload.'">Descargar Archivo</a><br><br><br>
                    <br>
                    <p style="padding:10px; font-size:16px;">Si has recibido este mensaje por error, simplemente elimina este correo electrónico. <br>No estás suscrito hasta que haga click en el botón de arriba.</p>
                    <br><br><br>
                    
                    Equipo<br>
                    <b>ElAutomatizador.io</b>
                    <br>
                    <div></center>
                    '
                ;
                $this->mail->send();
                $code_status = 200;
                $data =[
                        'success' => true,
                        'msg' => 'El correo electrónico se envió correctamente.'
                    ];
                
            } catch (Exception $e) {
                $code_status = 400;
                $data = 
                    [
                        'success' => false,
                        'msg' => 'El correo electrónico no se pudo enviar. Error: ', $this->mail->ErrorInfo
                    ];
            }
            http_response_code($code_status);
            header('Content-Type: application/json');
            echo json_encode($data);
            return;
        }
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['msg' => "El código no existe", 'success' => false]);
    }

    /**
     * Create user mailerlite
     * @param $data
     *  array with data
     */
    public function createUserMailerLite($mail, $url) {
        $mailerLite = new MailerLiteConnect();
        if($mailerLite->validateUser($mail) == TRUE){
            header("Location: ".$url);
            exit();
            /*header('Content-Type: application/json');
            echo json_encode(['msg' => 'Usuario agregado con éxito','success' => true]);
            return;*/
        }else{
            header('Content-Type: application/json');
            echo json_encode(['msg' => 'Hubo un error al enviar los datos','success' => false]);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(['msg' => 'No hay coincidencia con el código','success' => false]);
    }
}
