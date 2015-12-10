<?php
    class Core {
        
        public $objURL;
        public $objNavigation;
        public $objAdmin;
        
        public $_meta_title = 'Sugar KMS';
        public $_meta_description = 'Sugar KMS';
        public $_meta_keywords = 'Sugar KMS';
        
        public function __construct() {
            $this->objURL = new URL();
            $this->objNavigation = new Navigation($this->objURL);
        }
        
        public function run() {
            ob_start();
            switch($this->objURL->module) {
                //case 'panel' :
//                    set_include_path(implode(PATH_SEPARATOR, array( //path separator la de phan tach cac path noi chung va include path noi rieng
//                        realpath(ROOT_PATH.DS.'admin'.DS.TEMPLATE_DIR),
//                        realpath(ROOT_PATH.DS.'admin'.DS.PAGES_DIR),
//                        get_include_path() //lay nhung duong dan mac dinh trong include path trong file php.ini cua server
//                    )));
//                    $this->objAdmin = new Admin();
//                    require_once(ROOT_PATH.DS.'admin'.DS.PAGES_DIR.DS.$this->objURL->cpage.'.php');
//                break;
                default:
                    set_include_path(implode(PATH_SEPARATOR, array( //path separator la de phan tach cac path noi chung va include path noi rieng
                        realpath(ROOT_PATH.DS.TEMPLATE_DIR),
                        realpath(ROOT_PATH.DS.PAGES_DIR),
                        get_include_path() //lay nhung duong dan mac dinh trong include path trong file php.ini cua server
                    )));

                    if($this->objURL->cpage != 'logout') {
                        if(!isset($_SESSION['aid']) && !isset($_SESSION['valid'])) {
                            Login::checkCookie(); 
                        }
                        //Login::restrictAdmin($this->objURL);
                        

                    }
                    $this->objAdmin = new Admin();
                    $profile = $this->objAdmin->getAdminProfile(Session::getSession(Login::$_login_admin));
                    require_once(ROOT_PATH.DS.PAGES_DIR.DS.$this->objURL->cpage.'.php'); 
                    //construct cua URL se goi method process, method process se tra va cpage
            }
            ob_get_flush();            
        }
    }
?>