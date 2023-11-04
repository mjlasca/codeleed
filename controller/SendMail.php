<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);
$code_status = 400;
$data = ['status' => false, 'msg' => 'No hay datos para enviar'];
$basic_uri =  $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]. "/VIP/ol";

    if(isset($_REQUEST['id']) && isset($_REQUEST['email']) && isset($_REQUEST['utm_type']) && $_REQUEST['utm_type'] == 'download' && isset($_REQUEST['utm_group'])){
        $id = $_REQUEST['id'];
        $email = $_REQUEST['email'];
        $subject = isset($_REQUEST['utm_subject']) ? $_REQUEST['utm_subject'] : 'Descarga el archivo del vídeo' ;

        try {
            // Configuración del servidor SMTP de Gmail
            $mail->SMTPDebug = 0; // 0 para desactivar el modo debug
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'especialistasenexcel1@gmail.com'; // Correo electrónico de Gmail
            $mail->Password = 'vkchmiahtucjifdv'; // Contraseña de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('especialistasenexcel1@gmail.com', 'El automatizador');
            $mail->addAddress($email, $email);

            // Contenido del correo electrónico
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = '<h2>Hola, espero que estés teniendo un buen día.</h2>
                <p>A continuación encontrarás el enlace para descargar el archivo que solicitaste. Por favor, haz clic en el siguiente enlace para descargar el archivo:</p>
                <br>
                '.$basic_uri.'/next/?utm_m='.str_replace("&", "%26", base64_encode($email)).'&utm_id='.$id.'&utm_group='.$_REQUEST['utm_group'].'
                <br>
                <p>Si tienes algún problema para descargar el archivo, por favor házmelo saber y estaré encantado de ayudarte.</p>
                <p>Ten en cuenta que este correo electrónico es confidencial y solo está destinado al destinatario. Si has recibido este correo electrónico por error, por favor avísame inmediatamente y elimina este correo electrónico de tu sistema.

                Espero que esto te sea útil. Si tienes alguna otra pregunta, no dudes en preguntar.</p>
                <br><br><br>
                
                <a href="https://especialistasenexcel.com" target="_blank">
                <img width="300px" height="50px" src="https://especialistasenexcel.com/wp-content/uploads/2022/11/cropped-Logo-y-texto-3-1-600x97.png" alt="Logo Automatizador">
                </a><br>
                Email: contacto@especialistasenexcel.com
                </a>
                '
            ;

            // Envío del correo electrónico
            $mail->send();

            $code_status = 200;

            $data =[
                    'status' => true,
                    'msg' => 'El correo electrónico se envió correctamente.'
                ];
            
        } catch (Exception $e) {

            $code_status = 400;

            $data = 
                [
                    'status' => false,
                    'msg' => 'El correo electrónico no se pudo enviar. Error: ', $mail->ErrorInfo
                ];
            
            
        }
    }


    if(isset($_REQUEST['email']) && isset($_REQUEST['utm_type']) && $_REQUEST['utm_type'] == 'campaign' && isset($_REQUEST['utm_group'])){
        $email = $_REQUEST['email'];
        $subject = isset($_REQUEST['utm_subject']) ? $_REQUEST['utm_subject'] : 'Descarga el archivo del vídeo' ;
        $group = $_REQUEST['utm_group'];
        $url = isset($_REQUEST['utm_url']) ? $_REQUEST['utm_url'] : 'https://especialistasenexcel.com/' ;

        try {
            // Configuración del servidor SMTP de Gmail
            $mail->SMTPDebug = 0; // 0 para desactivar el modo debug
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'especialistasenexcel1@gmail.com'; // Correo electrónico de Gmail
            $mail->Password = 'vkchmiahtucjifdv'; // Contraseña de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('especialistasenexcel1@gmail.com', 'El automatizador');
            $mail->addAddress($email, $email);

            // Contenido del correo electrónico
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = '<h2>Hola, espero que estés teniendo un buen día.</h2>
                <p>Hemos trabajado duro para crear algo especial y estamos emocionados de que lo veas</p>
                <p>Simplemente haz clic en el siguiente enlace:</p>
                '.$basic_uri.'/next/?utm_m='.str_replace("&", "%26", base64_encode($email)).'&utm_group='.$group.'&utm_url='.$url.'
                <br>
                <p>¡Gracias por tu tiempo y esperamos que disfrutes de la campaña!

                Espero que esto te ayude. ¡Házmelo saber si necesitas algo más!</p>
                <br><br><br>
                
                <a href="https://especialistasenexcel.com" target="_blank">
                <img width="300px" height="50px" src="https://especialistasenexcel.com/wp-content/uploads/2022/11/cropped-Logo-y-texto-3-1-600x97.png" alt="Logo Automatizador">
                </a><br>
                Email: contacto@especialistasenexcel.com
                '
            ;

            // Envío del correo electrónico
            $mail->send();

            $code_status = 200;

            $data =[
                    'status' => true,
                    'msg' => 'El correo electrónico se envió correctamente.'
                ];
            
        } catch (Exception $e) {

            $code_status = 400;

            $data = 
                [
                    'status' => false,
                    'msg' => 'El correo electrónico no se pudo enviar. Error: ', $mail->ErrorInfo
                ];
            
            
        }

    }


    http_response_code($code_status);
    header('Content-Type: application/json');

    echo json_encode($data);
    
?>
