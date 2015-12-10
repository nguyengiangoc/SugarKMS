<?php
    class Login {
        
        public static $_login_front = "cid";
        public static $_login_page_admin = "/sugarkms/";
        public static $_dashboard_admin = "/sugarkms/panel/products";
        public static $_default = "/sugarkms/members";
        public static $_login_admin = "aid";
        public static $_valid_login = "valid";
        public static $_referrer = "refer";
        public static $_cookie_name = 'SugarKMSCookie';
        public static $_cookie_time = 2592000; //30 days
        
        
        public static function checkCookie() {
            if(isset($_COOKIE[self::$_cookie_name])) {
                parse_str($_COOKIE[self::$_cookie_name]);
                $objMember = new Member();
                $result = $objMember->getMemberByHash($hash);
                if(!empty($result)) {
                    $time = $result['time'];
                    if($time < time() + 2592000) {
                        $_SESSION[self::$_login_admin] = $result['id'];
                        $_SESSION[self::$_valid_login] = 1;
                    } else {
                        $objMember->updateMember(array('cookie_hash' => '', 'time' => 0),$result['id']);
                        setcookie(self::$_cookie_name, "", time() - 3600, '/', $_SERVER['SERVER_NAME']);
                        unset($_COOKIE[self::$_cookie_name]);
                    }
                }
                
            }
        }
        
        
        public static function isLogged($case = null) {
            if(!empty($case)) {
                if(isset($_SESSION[self::$_valid_login]) && $_SESSION[self::$_valid_login] == 1) {
                //kiem tra xem trong array session co attribute valid = 1  khong
                    return isset($_SESSION[$case]) ? true :  false;
                    //kiem tra xem trong array session co attribute cid hoac aid khong
                } 
                return false;
            }
            return false;
        }
        
        public static function loginFront($id, $url = null) {
            if(!empty($id)) {
                $url = !empty($url) ? $url : self::$_dashboard_front.PAGE_EXT;
                $_SESSION[self::$_login_front] = $id;
                $_SESSION[self::$_valid_login] = 1;
                Helper::redirect($url);
            }
        }
        
        public static function loginAdmin($id = null, $url = null, $remember = null) {
            if(!empty($id)) {
                $url = !empty($url) ? $url : self::$_dashboard_admin;
                $_SESSION[self::$_login_admin] = $id;
                $_SESSION[self::$_valid_login] = 1;
                if($remember == 1) {
                    $hash = md5(time().$id);
                    $objMember = new Member();
                    $objMember->updateMember(array('cookie_hash' => $hash, 'time' => time()),$id);
                    setcookie(self::$_cookie_name, 'hash='.$hash, time() + self::$_cookie_time, '/', $_SERVER['SERVER_NAME']);
                }
                Helper::redirect($url);
            }
        }
        
        public static function restrictAdmin($objURL = null) {
            $objURL = is_object($objURL) ? $objURL : new URL();
            if(!self::isLogged(self::$_login_admin)) {
                $url = $objURL->cpage != "logout" ?
                    self::$_login_page_admin.'index/'.self::$_referrer."/".$objURL->cpage.PAGE_EXT :
                    self::$_login_page_admin.PAGE_EXT;
                Helper::redirect($url);
            }
        }
        
        public static function string2Hash($string = null) {
            if(!empty($string)) {
                return hash('sha512', $string);
            }
        }
        
        public static function getFullNameFront($id = null) {
            if(!empty($id)) {
                $objUser = new User();
                $user = $objUser->getUser($id);
                if(!empty($user)) {
                    return $user['first_name']." ".$user['last_name'];
                }
            }
        }
        
        public static function logout() {
            $objMember = new Member();
            $objMember->updateMember(array('cookie_hash' => '', 'time' => 0),$_SESSION['aid']);
            $_SESSION['aid'] = null;
            $_SESSION['valid'] = null;
            unset($_SESSION['aid']);
            unset($_SESSION['valid']);
            setcookie(self::$_cookie_name, "", time() - 3600, '/', $_SERVER['SERVER_NAME']);
            unset($_COOKIE[self::$_cookie_name]);
        }
        
    }

?>