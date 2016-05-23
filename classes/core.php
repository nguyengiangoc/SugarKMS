<?php
    class Core {
        
        public $objURL;
        public $objPage;
        public $objNavigation;
        public $cPage;
        public $cPage_id;
        public $cPage_params;
        
        public $_meta_title = 'Sugar KMS';
        public $_meta_description = 'Sugar KMS';
        public $_meta_keywords = 'Sugar KMS';
        
        public function __construct() {
            $this->objURL = new URL();
            $this->objPage = new Page();
            //$this->objNavigation = new Navigation($this->objURL);
        }
        
        
        public function processLogin() {
            ob_start();
            
            set_include_path(implode(PATH_SEPARATOR, array( //path separator la de phan tach cac path noi chung va include path noi rieng
                        realpath(ROOT_PATH.DS.TEMPLATE_DIR),
                        realpath(ROOT_PATH.DS.PAGES_DIR),
                        get_include_path() //lay nhung duong dan mac dinh trong include path trong file php.ini cua server
            )));
            
            //process cPage de load duoc trang log in
            $url = $_SERVER['REQUEST_URI'];
            
            if(substr($url, 0, 1) == '/') {
                $url = substr($url, 1); 
            }
            
            if(substr($url, -1) == '/') {
                $url = substr($url, 0, -1); 
            }
            $url = explode('?', $url)[0];
            $url = explode('/', $url);
            array_shift($url); 
            $cPage = array_shift($url); 
            $this->cPage = $cPage;
            
            if(Login::isLogged()) {       
                //echo '1';
                $this->processURL($cPage, $url);
                
            } else {
                //echo '2';
                if(Login::checkCookie()) {
                    //echo '2';
                    $this->processURL($cPage, $url);
                    
                } else {
                    //echo '3';
                    if($cPage == 'login') {
                        require_once(ROOT_PATH.DS.PAGES_DIR.DS.$cPage.'.php');
                    } else {
                        Helper::redirect(Login::$_login_page);
                    }                        
                }
            }
            
            ob_get_flush(); 
            

            
            
            //$cPage = $this->objURL->cpage;
            //$params = $this->objURL->params;
            
            //process Login
            //if(in_array($cPage, Login::$_not_required)) {
//                                
//                require_once(ROOT_PATH.DS.PAGES_DIR.DS.$cPage.'.php');
//                
//            } else {
//
//                
//            }           
                       
        }
        
        
        public function processURL($cPage, $url_params) {
            
            switch($cPage) {
                
                case '':
                    //Helper::redirect(Login::$_default);
                    $member_id = Session::getSession(Login::$_login_admin);
                    $objMember = new Member();
                    $current_user = $objMember->getMemberById($member_id);
                    require_once(ROOT_PATH.DS.PAGES_DIR.DS.'home.php'); 
                    
                break;
                
                case 'login':
                    Helper::redirect('/sugarkms/');
                break;
                    
                
                case 'logout':
                    require_once(ROOT_PATH.DS.PAGES_DIR.DS.'logout.php');
                break;
                
                default:
                
                    $member_id = Session::getSession(Login::$_login_admin);
                    $objMember = new Member();
                    $current_user = $objMember->getMemberById($member_id);
                    
                    //tim coi co cpage co phai la group nao trong db khong
                    $group = $this->objPage->getGroups(array('name' => $cPage));
                    
                    if(!empty($group) && count($group) == 1) { //neu cpage la mot trong nhung group da duoc luu trong dbase thi moi xet den params tren url
                        
                        $group = $group[0];
                                                                        
                        $page_params = array();
                        
                        //ghi vao page params dua theo url params
                        
                        if(empty($url_params)) { //neu khong co params tren url thi lay params cua default page cho vao                            
                            
                            $default_page = $this->objPage->getPages(array('default' => 1, 'group_id' => $group['id']));
                                                        
                            if(!empty($default_page) && count($default_page) == 1) { //neu co duy nhat 1 default page thi moi xu ly tiep                               
                                                                
                                //lay params cua default page
                                $default_page_params = $this->objPage->getPageParams(array('page_id' => $default_page[0]['id']));
                                
                                //echo '<h1 style="background:white;">';
//                                var_dump($default_page_params);
//                                echo '</h1>'; 
                            
                                if(empty($default_page_params)) {
                                    $error = "No default page found.";
                                } else {
                                    foreach($default_page_params as $default_param) {
                                        if($default_param['required_value'] != '') {
                                            
                                            //chi cho vao khi default page khong co params tuy bien (required value rong~)
                                            $page_params[$default_param['param']] = $default_param['required_value'];
                                            $this->cPage_id = $default_page[0]['id'];
                                            
                                        } else {
                                            $error = "No default page found.";
                                        }
                                        
                                    }
                                }
                                
                            } else {
                                $error = "No default page found.";
                            }
                            
                        } else { //neu co params trong url
                            
                            
                            //neu chi co 1 thanh phan va thanh phan do la so, tuc la id, vay them action=view vao, 
                            //con truong hop member thi split theo "-" roi xet thanh phan dau tien
                            if(count($url_params) == 1) {
                                if(is_numeric($url_params[0])) {
                                    $url_params[] = 'view';
                                }
//                                //} else {
////                                    $first_element = $url_params[0];
////                                    $array = explode('-', $first_element);
////                                    $length = count($array)-1;
////                                    if(is_numeric($array[$length])) {
////                                        $url_params[0] = $array[$length];
////                                        $url_params[] = 'view';
////                                    };
////                                    
////                                }
//                                
//                                
                            }
//                            
//                            if(count($url_params) == 2) {
//                            //if($cPage == 'member' && count($url_params) == 2) {
//                                if(is_numeric($url_params[0])) {
//                                    $url_params[] = 'view';
//                                    //echo '<h1 style="background:white;">';
////                                    var_dump($url_params);
////                                    echo '</h1>'; 
//                                }
//                            }
                            
                            $pages = $this->objPage->getPages(array('group_id' => $group['id']));
                            //echo '<h1 style="background:white;">';
//                            var_dump($url_params);
//                            echo '</h1>'; 
                            
                            foreach ($pages as $page) {
                                //echo 'page name: ';
//                                var_dump($page['name']);
//                                echo '<br />';
                                
                                $params_db = $this->objPage->getPageParams(array('page_id' => $page['id']), array('order' => 'asc'));
                                
                                //echo '<h1 style="background:white;">';
//                                echo 'params db for page '.$page['name'].': ';
//                                var_dump($params_db);
//                                echo '</h1>';
                                
                                if(count($params_db) == count($url_params)) {
                                    
                                    
                                    foreach ($params_db as $key => $param_db) {
                                        
                                        //echo 'current key: '.$key.'<br />';
    //                                        echo 'param db name: '.$param_db['param'].'<br />';
    //                                        echo 'uri at current key: '.$url_params[$key].'<br />';
    //                                        echo '<br />';
                                        if($param_db['required_value'] != '' && $url_params[$key] != $param_db['required_value']) {
                                            //var_dump($url_params[$key]);
//                                            var_dump($param_db['required_value']);
                                            continue 2;                                          
                                        } else {
                                            $page_params[$param_db['param']] = $url_params[$key]; 
                                            //unset($params_db[$key]);                                                                                      
                                        }
                                    //var_dump($page_params);
                                        
                                        
                                    } 
                                    if(count($page_params) == count($url_params)) {
                                        $this->cPage_id = $page['id'];
                                        break;
                                    }
                                     
                                    //echo 'result: '.empty($params_db);
                                }
                                
                            }
//                            echo '<h1 style="background:white;">';
//                                var_dump($page_params);
//                                var_dump($this->cPage_id);
//                                echo '</h1>';
//                            
                            
                            if(empty($page_params) || count($page_params) != count($url_params)) {
                                $error = "No record found for this page.";
                            } 
                            
                        }
                        
                        if(isset($error)) {
                            require_once(ROOT_PATH.DS.PAGES_DIR.DS.'error.php'); 
                        } elseif (isset($page_params) && !empty($this->cPage_id)) {
                            
                            $this->cPage_params = $page_params;                                                    
                            $this->processPageSource();
                                                                                   
                        }
                                               
                        
                    } else { //neu cpage khong phai la group nao da duoc luu trong database
                                                
                        $error = "No page group found.";
                        require_once(ROOT_PATH.DS.PAGES_DIR.DS.'error.php'); 
                        
                    }
                
                
                
                
            }

        }
        
        public function processPageSource() {
            
            $member_id = Session::getSession(Login::$_login_admin);
            $objMember = new Member();
            $current_user = $objMember->getMemberById($member_id);         
            //var_dump($current_user);
            $page_details = $this->objPage->getPages(array('id' => $this->cPage_id))[0];
                
            if(!empty($page_details['php_file_directory'])) {
                if(file_exists(ROOT_PATH.DS.PAGES_DIR.DS.$page_details['php_file_directory'])) {
                    
                    $cPage = $this->cPage;
                    $params = $this->cPage_params;
                    //var_dump($params);
                    // Tao object
                    $object = ucwords($cPage);
                    switch($cPage) {                                                      
    
                        case 'exco':
                        $objProject = new Project();
                        break;
                        
                        default:
                        if(file_exists(ROOT_PATH.DS.CLASSES_DIR.DS.$object.'.php')) {
                            ${'obj'.$object} = new $object();
                        }                                    
                        break;
                    }
                    if($cPage == 'database') {
                        $header = 'Database :: '.ucwords(str_replace('-', ' ', $params['table']));    
                    }
                    
                    if(array_key_exists('id', $params)) {                        
                        
                        switch($cPage) {                   
                                                            
                            case 'exco':
                                $id = $params['id'];
                                $project = $objProject->getProjectById($id);
                                $result = $project;
                            break;
                            
                            default:
                                $id = $params['id'];
                                ${$cPage} = ${'obj'.$object}->{'get'.$object.'byId'}($id);
                                $result = ${$cPage};
                            break;
                        }
                        
                        if(empty($result)) {
                            
                            $error = "No record found for the ID provided.";
                        }    
                        
                    }                          
                
                } else {
                    $error = "The source directory is invalid.";
                }                           
            } else {
                $error = "The source directory is not recorded.";
            }
            
            if(isset($error)) {
                
                require_once(ROOT_PATH.DS.PAGES_DIR.DS.'error.php'); 
                
            } else {
                
                
                if($this->objPage->canAccess($this->cPage_params, $member_id, $page_details)) {
                    //echo '<h1 style="background:white;">';
//                    var_dump($this->cPage_params);
//                    var_dump($this->cPage_id);
//                    echo '</h1>';
                    require_once(ROOT_PATH.DS.PAGES_DIR.DS.$page_details['php_file_directory']);
                } else {
                    $error = 'You do not have permission to access this page';
                    require_once(ROOT_PATH.DS.PAGES_DIR.DS.'error.php');   
                }
                
            }
            
        }
        
        public function getCurrentURL($exclude = null, $add = null) {
            $cPage_params = $this->cPage_params;
            if(!empty($exclude) && is_array($exclude)) {
                foreach($exclude as $param) {
                    if(array_key_exists($param, $cPage_params)) {
                        unset($cPage_params[$param]);
                    }                    
                }
            }
            if(!empty($add) && is_array($add)) {
                foreach($add as $key => $value) {
                    $cPage_params[$key] = $value;
                }
            }
            $result = $this->objPage->generateURL($this->cPage, $cPage_params);
            return $result;
        }

        
        
        
        
        
        
    }
?>