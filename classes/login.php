<?php
    class Login {
        
        public static $_login_front = "cid";       
        public static $_default = "/sugarkms/member";
        public static $_login_admin = "aid";
        public static $_valid_login = "valid";
        public static $_referrer = "refer";
        public static $_cookie_name = 'SugarKMSCookie';
        public static $_cookie_time = 2592000; //30 days
        public static $_not_required = array('logout');
        public static $_login_page = '/sugarkms/login';
        
        
        public static function checkCookie() {
            if(isset($_COOKIE[self::$_cookie_name])) {
                
                //bien string attr1=value1&attr2=value2 thanh bien attr1 va bien attr2
                //trong string cua cookie co attr hash nen bien hash da duoc xac dinh roi
                parse_str($_COOKIE[self::$_cookie_name]);
                
                $objMember = new Member();
                $result = $objMember->getMemberByHash($hash);
                if(!empty($result)) {
                    $time = $result['time_kms'];
                    if(time() < $time + 2592000) {
                        $_SESSION['SugarKMS'][self::$_login_admin] = $result['id'];
                        $_SESSION['SugarKMS'][self::$_valid_login] = 1;
                    } else {
                        $objMember->updateMember(array('cookie_hash_kms' => '', 'time_kms' => 0),$result['id']);
                        setcookie(self::$_cookie_name, "", time() - 3600, '/', $_SERVER['SERVER_NAME']);
                        unset($_COOKIE[self::$_cookie_name]);
                    }
                    return true;
                } 
                return false;
            }
            return false;
        }
        
        
        public static function isLogged() {
            if(isset($_SESSION['SugarKMS'][self::$_valid_login]) && $_SESSION['SugarKMS'][self::$_valid_login] == 1) {
            //kiem tra xem trong array session co attribute valid = 1  khong
                return isset($_SESSION['SugarKMS'][self::$_login_admin]) ? true :  false;
                //kiem tra xem trong array session co attribute cid hoac aid khong
            } 
            return false;
        }

        
        public static function processLogin($id = null, $url = null, $remember = null) {
            if(!empty($id)) {
                $url = !empty($url) ? $url : self::$_default;
                if(!isset($_SESSION['SugarKMS'])) {
                    $_SESSION['SugarKMS'] = array();
                                    
                }                
                $_SESSION['SugarKMS'][self::$_login_admin] = $id;
                $_SESSION['SugarKMS'][self::$_valid_login] = 1;
                if($remember == 1) {
                    $hash = md5(time().$id);
                    $objMember = new Member();
                    $objMember->updateMember(array('cookie_hash_kms' => $hash, 'time_kms' => time()),$id);
                    setcookie(self::$_cookie_name, 'hash='.$hash, time() + self::$_cookie_time, '/', $_SERVER['SERVER_NAME']);
                }
                Helper::redirect($url);
            }
        }
        
        public static function hash($string = null) {
            if(!empty($string)) {
                return hash('sha512', $string);
            }
        }
        
        public static function logout() {
            $objMember = new Member();
            $objMember->updateMember(array('cookie_hash_kms' => '', 'time_kms' => 0),$_SESSION['SugarKMS']['aid']);
            $_SESSION['SugarKMS']['aid'] = null;
            $_SESSION['SugarKMS']['valid'] = null;
            unset($_SESSION['SugarKMS']['aid']);
            unset($_SESSION['SugarKMS']['valid']);
            setcookie(self::$_cookie_name, "", time() - 3600, '/', $_SERVER['SERVER_NAME']);
            unset($_COOKIE[self::$_cookie_name]);
        }
        
    }

?>