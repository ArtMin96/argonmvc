<?php

namespace Core;

class CSRF {
    
    /**
     * Creates a csrf token and stores it in $_SESSION
     * @method generateToken
     * @return string        value of the token set in $_SESSION
     */
    public static function generateToken() {
        $token = base64_encode(openssl_random_pseudo_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }

    /**
     * Check to see if the csrf token is valid
     * @method checkToken
     * @param  string     $token value that was posted
     * @return boolean           returns whether or not the token was correct
     */
    public static function checkToken($token) {
        return (Session::exists('csrf_token') && Session::get('csrf_token') == $token);
    }

    /**
     * Creates a hidden input to be used in a form for csrf
     * @method csrfInput
     * @return string    return html string for form input
     */
    public static function csrfInput() {
        return '<input type="hidden" name="csrf_token" id="csrf_token" value="'.self::generateToken().'" />';
    }
}