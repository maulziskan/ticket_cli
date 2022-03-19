<?php
class JWT {

    public function urlbase64_encode($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    public function generate_token($headers, $payload, $secret = 'secret') {
        $headers_encoded = $this->urlbase64_encode(json_encode($headers));
        
        $payload_encoded = $this->urlbase64_encode(json_encode($payload));
        
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
        $signature_encoded = $this->urlbase64_encode($signature);
        
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        
        return $jwt;
    }

    public function cek_jwt($jwt, $secret = 'secret') {
        // pecah token
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // cek expired token
        $expiration = json_decode($payload)->exp;
        $token_expired = ($expiration - time()) < 0;

        // buat token pembanding
        $base64_url_header = $this->urlbase64_encode($header);
        $base64_url_payload = $this->urlbase64_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
        $base64_url_signature = $this->urlbase64_encode($signature);

        // verifikasi token jwt
        $signature_valid = ($base64_url_signature === $signature_provided);
        
        if ($token_expired || !$signature_valid) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}