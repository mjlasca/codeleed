<?php
use MailerLiteApi\MailerLite;
use Dotenv\Dotenv;

    class MailerLiteConnect
    {
        protected $mailerliteClient;
        private $groupId;

        public function __construct() {
            $dotenv = Dotenv::createImmutable(__DIR__ .'/../../');
            $dotenv->load();
            $this->groupId = $_ENV['MAIL_GID'];
            $this->mailerliteClient = new MailerLite(['api_key' => $_ENV['MAIL_KEY']]);
        }

       public function validateUser($mail,$category,$source){
            $groups = $this->mailerliteClient->groups();
            $users = $this->mailerliteClient->subscribers();
            $subscriber = $users->find($mail);

            if(count((array)$subscriber) > 3){
                
                $subscriberId = $subscriber->id;
                $groupSubscribers = $groups->getSubscriber($this->groupId, $subscriberId);
 
                //if(count((array)$groupSubscribers) < 4){
                    $subscriber = [
                        'email' => $mail,
                        'fields' => [
                            'source2' => $source,
                            'category' => $category
                        ]
                    ];
                    try{
                        $rest = $groups->addSubscriber($this->groupId, $subscriber); 
                        return true;
                    }catch(Exception $e){
                        return false;
                    }
                /*}else{
                    return true;
                }*/
            }

            return false;
        }
    }



?>