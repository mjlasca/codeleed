<?php
use MailerLiteApi\MailerLite;
use Dotenv\Dotenv;

    class MailerLiteConnect
    {
        protected $mailerliteClient;
        private $groupId;
        private $tokenip;

        public function __construct() {
            $dotenv = Dotenv::createImmutable(__DIR__ .'/../../');
            $dotenv->load();
            $this->groupId = $_ENV['MAIL_GID'];
            $this->tokenip = $_ENV['TOKEN_IP2'];
            $this->mailerliteClient = new MailerLite(['api_key' => $_ENV['MAIL_KEY']]);
        }

       public function validateUser($mail,$category,$source){
            $groups = $this->mailerliteClient->groups();
            $users = $this->mailerliteClient->subscribers();
            $subscriber = $users->find($mail);
            $country = "";
            $city = "";
            $ip = $_SERVER['REMOTE_ADDR'];
            //if (strpos($ip, ':') > -1) {
                $url = "https://ipinfo.io/{$ip}?token={$this->tokenip}";
            /*}else{
                $url = "https://ipgeolocation.abstractapi.com/v1/?api_key={$this->tokenip}&ip_address={$ip}";
            }*/
            try {
                $response = file_get_contents($url);
                
                if ($response !== false) {
                    $data = json_decode($response, true);
                    $country = $data['country'];
                    $city = $data['city'];
                }
            } catch (\Throwable $th) {
                //
            }
            
            

            //if(count((array)$subscriber) > 3){
                
            /*    $subscriberId = $subscriber->id;
                $groupSubscribers = $groups->getSubscriber($this->groupId, $subscriberId);*/
                
                //if(count((array)$groupSubscribers) < 4){
                    $subscriber = [
                        'email' => $mail,
                        'fields' => [
                            'source2' => $source,
                            'category' => $category,
                            'city' => $city,
                            'country2' => $country
                        ],
                        'status' => 'active',
                        'ip_address' => $ip
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
            //}

            return false;
        }

        public function log($msg, $level = 'INFO') {
            $file = __DIR__ . '/logs/app.log';
            if (!file_exists(dirname($file))) {
                mkdir(dirname($file), 0777, true);
            }
            $date = date('Y-m-d H:i:s');
            $log = "[{$date}] [{$level}] {$msg}" . PHP_EOL;
            file_put_contents($file, $log, FILE_APPEND);
        }
    }

    



?>