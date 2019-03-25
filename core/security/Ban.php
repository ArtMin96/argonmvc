<?php

namespace Core\Security;
use Core\{View, Router};

class Ban {

  /**
   * Returns the users IP Address
   * This data shouldn't be trusted. Faking HTTP headers is trivial.
   * @return string/false the users IP address or false
   */
  public function getIP() {
    // Try REMOTE_ADDR
    if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
      return $_SERVER['REMOTE_ADDR'];
    }
    // Fall back to HTTP_CLIENT_IP
    elseif(isset($_SERVER['HTTP_CLIENT_IP']) and $_SERVER['HTTP_CLIENT_IP'] != '') {
      return $_SERVER['HTTP_CLIENT_IP'];
    }
    // Finally fall back to HTTP_X_FORWARDED_FOR
    // I'm aware this can sometimes pass the users LAN IP, but it is a last ditch attempt
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // Nothing? Return false
    return false;
  }

  /**
   * Checks that the specified string is a valid IP Address
   * @param  string $ip The IP Address
   * @return bool     is the IP Address valid or not?
   */
  public function validIP($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP);
  }

  /**
   * If visitor ip is equal to ip in the text file
   * @return bool if visitor ip is equal to ip in the text file
   */
  public function readBannedIP() {
    $getFile = file_get_contents(ROOT.'/'.BANNED_IP_FILE, true);
    return (strpos($getFile, $this->getIP()) !== false) ? true : false;
  }

  public function isIPExists() {
    $ip = $this->validIP($this->getIP());
    if($ip == $this->readBannedIP()) {
      Router::redirect('restricted/blocked');
    }
  }

  public function writeBannedIPLog($ip) {
    if($this->validIP($ip) && file_exists(ROOT.'/'.BANNED_IP_FILE)) {
      if($ip == $this->readBannedIP()) {
        return false;
      } else {
        $write = file_put_contents(ROOT.'/'.BANNED_IP_FILE, $ip.',', FILE_APPEND);
        return $write;
      }

      //return (strpos($this->readBannedIP(), $ip.PHP_EOL) !== false) ? true : false;
    }
    return false;
  }

}
