<?php

require '../vendor/autoload.php';
use MailerLiteApi\MailerLite;

    class MailerLiteConnect
    {
        protected $mailerliteClient;

        public function __construct($key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiMGI3YzgzNGU2YTQwZWQ4YzFmNDlkNmYzN2U3MWVkZmJlMmZjZWEzYzhkYmQ0NjhiYjgyODgxZDM5ZDIyNjBjMDRkNzRlNDVkYmZjYmE2NTgiLCJpYXQiOjE2OTkwNzEwNTQuMTcwNDAzLCJuYmYiOjE2OTkwNzEwNTQuMTcwNDA2LCJleHAiOjQ4NTQ3NDQ2NTQuMTY2MDY0LCJzdWIiOiI1OTU5NSIsInNjb3BlcyI6W119.Yx6o8WH6HsOUW2UpSCMvGu_zIehg9iqgxAM3iVRFbwE8F3XAyhEukjtXgWAX7onqsV6wRpDcJMXD4DQtk0B1J3Jg7siRvsVDyutZxap6dhl1bwUz8-ZkS2dwZ4FqMpLyF4pp8EgrFlgjF9jcI0_HnlhQW8BVXHP7ke6WIYrjWW3biq-L-V8jhnINYTusAtgw6JKTsgSTMqsX9xeuknTEhrIuEbQ1mgqCugo8tH6kB0vjPReQvPcDUua0NSqYyRabKzT6qOJ2Eb1xw3kV2M69aDd2CuV6pQU3fsKg-YCWiX0PM8lXfILig6eh5X9xkHhKAFZjC4N7Q8eGneLYsu1aKHJzlQZXPS_tFrRZVLftoddWFYL85MEQtJz3PG4oGcMJDroUYqRVp-eEVNJ0MzETMsp1prQn0Nfqyh1BEzydJHT812x7NAmUWG_iok3DJ-L9F2kuvsoQFMVaKY73VHOSTSW2kk96ES1XE49nu34CFEvuONZKP0jtvhf3SFmKVdb3xi_PMXLPlB7Ubde5lIV8KLj3fPXsqt6KYQ76j4fLH2S7aJHZMLy1OFjMWMixxGvI2bKUGVUZW6wiFlp43GRJOrbtgOQ3zLHNv92z0i_6pwo1BvsLQtrX6fuH-J5QLRM6sNmO_mAOSVjte_psfj74ipW89N96tCaAFunGW6goC08') {
            $this->mailerliteClient = new MailerLite(['api_key' => $key]);
        }

       public function validateUser($mail, $groupId = 103829400057808272){
            $groups = $this->mailerliteClient->groups();
            $users = $this->mailerliteClient->subscribers();
            $subscriber = $users->find($mail);

            if(count((array)$subscriber) > 3){
                $subscriberId = $subscriber->id;
                $groupSubscribers = $groups->getSubscriber($groupId, $subscriberId);
 
                if(count((array)$groupSubscribers) < 4){

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