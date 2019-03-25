<?php

namespace App\Models;
use Core\{Model, Cookie, Session};
use App\Models\{Users, UserSessions};
use Core\Validators\{MinValidator, MaxValidator, RequiredValidator, UniqueValidator, EmailValidator, MatchesValidator};


class Users extends Model {
    
    protected static $_table = 'users', $_softDelete = true;
    public static $currentLoggedInUser = null;
    public $id, $username, $email, $password, $fname, $lname, $acl, $deleted = 0, $confirm;
    const blackListedFormKeys = ['id', 'deleted'];
    
    public function validator() {
        
        $requiredFields = ['fname' => 'First Name', 'lname' => 'Last Name', 'email' => 'Email Address'];
        foreach($requiredFields as $field => $display) {
            $this->runValidation(new RequiredValidator($this, ['field' => $field, 'message' => $display.' is required.']));
        }
        
        // Username Validation
        $this->runValidation(new MinValidator($this, ['field' => 'username', 'rule' => 6, 'message' => 'Your username must be at least 6 characters.']));
        $this->runValidation(new MaxValidator($this, ['field' => 'username', 'rule' => 150, 'message' => 'Username cannot be over 150 characters.']));
        $this->runValidation(new UniqueValidator($this, ['field' => 'username', 'message' => 'That Username is already taken.']));
        
        // Email Validation
        $this->runValidation(new EmailValidator($this, ['field' => 'email', 'message' => 'You must provide valid email.']));
        
        // Password Validation
        $this->runValidation(new MinValidator($this, ['field' => 'password', 'rule' => 6, 'message' => 'Password must be a minimum of 6 characters.']));
        if($this->isNew()) {
            $this->runValidation(new MatchesValidator($this, ['field' => 'password', 'rule' => $this->confirm, 'message' => "Your passwords do not match."]));
        }
    }
    
    public function beforeSave() {
        $this->timeStamps();
        if($this->isNew()) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }
    }

    public static function findByUsername($username) {
        return self::findFirst(['conditions' => 'username = ?', 'bind' => [$username]]);
    }
    
    public static function findByEmail($email) {
        return self::findFirst(['conditions' => 'email = ?', 'bind' => [$email]]);
    }
    
    public static function currentUser() {
        if(!isset(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)) {
            self::$currentLoggedInUser = self::findById((int)Session::get(CURRENT_USER_SESSION_NAME));
        }
        return self::$currentLoggedInUser;
    }
    
    public function login($rememberMe = false) {
        Session::set(CURRENT_USER_SESSION_NAME, $this->id);
        if($rememberMe) {
            $hash = md5(uniqid() + rand(0, 100));
            $user_agent = Session::uagent_no_version();
            Cookie::set(REMEMBER_ME_COOKIE_NAME, $hash, REMEMBER_ME_COOKIE_EXPIRY);
            $fields = ['session' => $hash, 'user_agent' => $user_agent, 'user_id' => $this->id];
            self::$_db->query("DELETE FROM user_sessions WHERE user_id = ? AND user_agent = ?", [$this->id, $user_agent]);
            $us = new UserSessions();
            $us->assign($fields);
            $us->save();
            // self::$_db->insert('user_sessions', $fields);
        }
    }
    
    public static function loginUserFromCookie() {
        $userSession = UserSessions::getFromCookie();
        if($userSession && $userSession->user_id != '') {
            $user = self::findById((int)$userSession->user_id);
            if($user) {
                $user->login();
            }
            return $user;
        }
        return;
    }
    
    public function logout() {
        $userSession = UserSessions::getFromCookie();
        if($userSession) {
            $userSession->delete();
        }
        Session::delete(CURRENT_USER_SESSION_NAME);
        if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
            Cookie::delete(REMEMBER_ME_COOKIE_NAME);
        }
        self::$currentLoggedInUser = null;
        return true;
    }
    
    public function acls() {
        if(empty($this->acl)) return [];
        return json_decode($this->acl, true);
    }

    public static function addAcl($user_id,$acl){
        $user = self::findById($user_id);
        if(!$user) return false;
        $acls = $user->acls();
        if(!in_array($acl,$acls)){
            $acls[] = $acl;
            $user->acl = json_encode($acls);
            $user->save();
        }
        return true;
      }
    
      public static function removeAcl($user_id, $acl){
        $user = self::findById($user_id);
        if(!$user) return false;
        $acls = $user->acls();
        if(in_array($acl,$acls)){
            $key = array_search($acl,$acls);
            unset($acls[$key]);
            $user->acl = json_encode($acls);
            $user->save();
        }
        return true;
    }
    
}