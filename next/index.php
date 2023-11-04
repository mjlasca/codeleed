<?php

    require '../controller/MailerLiteConnect.php';
    require '../controller/vip.php';

    if(isset($_GET['utm_m']) && isset($_GET['utm_group']) ){
        $group = $_GET['utm_group'];
        if(isset($_GET['utm_id'])){
            $email = base64_decode(str_replace("%26", "&", $_GET['utm_m']));
            $id =  $_GET['utm_id'];
            $mailerLite = new MailerLiteConnect();
            if($mailerLite->validateUser($email, $group)){
                $data = getUrlFile($id);
                if($data["exito"]){
                    $url = $data['linkUs'];
                    header('Location: '.$url);
                }
                
            }
        }
        if(isset($_GET['utm_url'])){
            $url = $_GET['utm_url'];
            $email = base64_decode(str_replace("%26", "&", $_GET['utm_m']));
            
            $mailerLite = new MailerLiteConnect();
            if($mailerLite->validateUser($email, $group)){
                header('Location: '.$url);
            }
        }
    }

    header('Location: https://especialistasenexcel.com/VIP/ol/');

    


?>