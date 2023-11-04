<?php

    require '../vendor/autoload.php';

    class MailerLiteConnect
    {
        protected $mailerliteClient;

        public function __construct($key = '5e9780f931cba81a610981c223301993') {
            $this->mailerliteClient = new \MailerLiteApi\MailerLite($key);
        }

       public function validateUser($mail, $groupId = 103579870){
            $groups = $this->mailerliteClient->groups();
            $users = $this->mailerliteClient->subscribers();

            $subscriber = $users->find($mail);
            http_response_code(200);
    header('Content-Type: application/json');
            echo print_r( $groups->get());
            //echo json_encode($subscriber);
            return false;
            if(count((array)$subscriber) > 2){
                $subscriberId = $subscriber->id;
                $groupSubscribers = $groups->getSubscriber($groupId, $subscriberId);
                
                if(count((array)$groupSubscribers) <= 2){
                    //create user in group
                    $subscriber = [
                    'email' => $mail,
                    ];
        
                    try{
                        $groups->addSubscriber($groupId, $subscriber); 
                        return true;
                    }catch(Exception $e){
                        return false;
                    }
                }else{
                    return true;
                }
            }

            return false;
        }
    }



?>