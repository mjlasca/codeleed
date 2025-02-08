<?php

class AccessRequest {

    /**
     * Validate request local server
     */
    public function isLocalRequest() {
        $clientIp = $_SERVER['REMOTE_ADDR'];
        if(!$clientIp === '127.0.0.1' && !$clientIp === '::1'){
            http_response_code(403); // Código de estado HTTP 403: Prohibido
            return ['error' => 'Access denied. This resource is only accessible from the server.'];
        }
        
    }
}