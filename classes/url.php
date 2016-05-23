<?php
    class URL {
        public $key_page = 'page';
        public $module = 'front';
        public $main = 'index';
        public $cpage = 'index';
        public $c = 'login';
        public $a = 'index';
        public $params = array(); //tat ca nhung phan trong dau '/' tren link
        public $paramsRaw = array();
        public $stringRaw;
        public $page_id = '';
        
        public function __construct() {
            //$this->process();
        }
        
        public function process() {
            $uri = $_SERVER['REQUEST_URI'];
            if(!empty($uri)) {
                
                //$uriQ = explode('?', $uri); 
//                //cau truc cua link la abc/xyz.php?param=blah&param=blah
//                //tach ra de tim xem co param nao o tren link, sau dau cham hoi se la tat ca cac param duoc gan bang dau &, nen day la array
//                $uri = $uriQ[0]; //thanh phan dau tien se la link day du
//                if(count($uriQ) > 1) { //tren link ngoai link day du con co param
//                    $this->stringRaw = $uriQ[1]; 
//                    $uriRaw = explode('&', $uriQ[1]); //tach ra tung param
//                    if(count($uriRaw > 1)) { //neu co nhieu hon 1 param
//                        foreach($uriRaw as $key => $row) {
//                            $this->splitRaw($row); //dua param va property vao trong array paramsRaw theo cau truc key: param, value: property
//                        }
//                    } else {
//                        $this->splitRaw($uriRaw[0]); //neu co 1 param thi dua param dau tien vao trong array paramsRaw
//                    }
//                }

                $firstChar = substr($uri, 0, 1);
                if($firstChar == '/') {
                    $uri = substr($uri, 1); 
                }
                $lastChar = substr($uri, -1);
                if($lastChar == '/') {
                    $uri = substr($uri, 0, -1); 
                }

                if(!empty($uri)) {
                    
                    $uri = explode('/', $uri);
                    array_shift($uri); 
                    $this->cpage = array_shift($uri); 
                    
                    
                    //$pairs = array();
                    //foreach($uri as $key => $value) {
//                            $pairs[] = $value;
//                            if(count($pairs) > 1) {
//                                if(!Helper::isEmpty($pairs[1])) {
//                                    if($pairs[0] == $this->key_page) {
//                                        $this->cpage = $pairs[1];
//                                    } else if ($pairs[0] == 'c') {
//                                        $this->c = $pairs[1];
//                                    } else if ($pairs[0] == 'a') {
//                                        $this->a = $pairs[1];
//                                    }
//                                    $this->params[$pairs[0]] = $pairs[1];
//                                }
//                                $pairs = array();
//                            }
//                        }

                    
                    
                    $objPage = new Page();
                    $group = $objPage->getGroups(array('name' => $this->cpage));
                    
                    if(!empty($group) && count($group) == 1) {
                                                
                        //neu cpage la mot trong nhung group da duoc luu trong dbase thi moi xet den params tren url
                        $params = array();
                        
                        if(empty($uri)) {
                            
                            //neu khong co params tren url thi lay params cua default page cho vao
                            $defaultPage = $objPage->getPages(array('default' => 1, 'group_id' => $group[0]['id']));
                                                        
                            if(!empty($defaultPage) && count($defaultPage) == 1) {
                                $defaultParams = $objPage->getPageParams(array('page_id' => $defaultPage[0]['id']));
                                foreach($defaultParams as $defaultParam) {
                                    if($defaultParam['required_value'] != '') {
                                        $params[$defaultParam['param']] = $defaultParam['required_value'];
                                    } else {
                                        $error = "No record found in database for this page.";
                                    }
                                    
                                }
                            }
                            
                        } else {
                            
                            if(count($uri) == 1 && is_numeric($uri[0])) {
                                $uri[] = 'view';
                            }
                            
                            $pages = $objPage->getPages(array('group_id' => $group[0]['id'])); 
                        
                            foreach ($pages as $page) {
                                $params_db = $objPage->getPageParams(array('page_id' => $page['id']), array('order' => 'asc'));
                                
                                if(count($params_db) == count($uri)) {
                                    //var_dump($params_db);
                                    foreach ($params_db as $key => $param_db) {
                                        //echo 'current key: '.$key.'<br />';
    //                                        echo 'param db name: '.$param_db['param'].'<br />';
    //                                        echo 'uri at current key: '.$uri[$key].'<br />';
    //                                        echo '<br />';
                                        if($param_db['required_value'] == '') {
                                            $params[$param_db['param']] = $uri[$key];
                                            unset($params_db[$key]);
                                        } else {
                                            if($uri[$key] == $param_db['required_value']) {
                                                $params[$param_db['param']] = $uri[$key];
                                                unset($params_db[$key]);
                                            }
                                        }
                                    } 
                                    //echo 'result: '.empty($params_db);
                                    if(empty($params_db)) {
                                        $this->page_id = $page['id'];
                                        $this->params = $params;
                                        break;
                                    }
                                }
                                
                            } 
                            
                        }
                    }
                    //var_dump($this->params);
                    
                }
            }
        }
        
        public function splitRaw($item = null) {
            if(!empty($item) && !is_array($item)) {
                $itemRaw = explode('=', $item);
                if(count($itemRaw) > 1 && !Helper::isEmpty($itemRaw[1])) {
                    $this->paramsRaw[$itemRaw[0]] = $itemRaw[1];
                }
            }
        }
        
        public function getRaw($param = null) {
            if(!empty($param) & array_key_exists($param, $this->paramsRaw)) {
                return $this->paramsRaw[$param];
            }
        }
        
        public function get($param = null) {
            if(!empty($param) && array_key_exists($param, $this->params)) {
                return $this->params[$param];
            }
        }
        
        public function href($main = null, $params = null) {
            if(!empty($main)) {
                $out = array($main);
                if(!empty($params) && is_array($params)) {
                    foreach($params as $key => $value) {
                        $out[] = $value; //array cho vao se co dang ten param va property
                    }
                }
                return implode('/', $out).PAGE_EXT; //khi xuat ra se co dang main/ten param/property
            }
        }
        
        public function getCurrent($exclude = null, $extension = false, $add = null) {
            $out = array();
            if($this->module != 'front') {
                $out[] = $this->module;
            }
            $out[] = $this->main;
            if(!empty($this->params)) {
                if(!empty($exclude)) {
                    $exclude = Helper::makeArray($exclude);
                    foreach($this->params as $key => $value) {
                        if(!in_array($key, $exclude)) { //neu co exclude thi chi cho vao array out nhung gi khong phai exclude
                            $out[] = $key;
                            $out[] = $value;
                        }
                    }
                } else {
                    foreach($this->params as $key => $value) {
                        $out[] = $key;
                        $out[] = $value;
                    }
                }
            }
            if(!empty($add)) {
                $add = Helper::makeArray($add);
                foreach($add as $item) {
                    $out[] = $item;
                }
            }
            $url = implode('/', $out);
            $url .= $extension ? PAGE_EXT : null;
            return '/sugarkms/'.$url;
            //ket qua la dang index/blah blah/blah blah
        }

    }
?>