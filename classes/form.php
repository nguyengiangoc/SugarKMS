<?php
    class Form {
        
        public function isPost($field = null) {
            if(!empty($field)) {
                if(isset($_POST[$field])) {
                    return true;
                } //kiem tra xem co mot index nao giong voi bien field duoc nhap vao trong post hay khong
                return false;
            } else {
                if(!empty($_POST)) {
                    return true;
                } //kiem tra xem co mot index nao do duoc set trong post hay chua
                return false;
            }
        }
        
        public function getPost($field = null) {
            if(!empty($field) && $this->isPost($field)) {
                if(is_string($_POST[$field])) {
                    return strip_tags($_POST[$field]);
                } else {
                    return $_POST[$field];
                }
            }
            return null;
        }
        
        public function stickySelect($field, $value, $default = null) {
            if ($this->isPost($field) && $this->getPost($field) == $value) {
                return " selected=\"selected\"";
                //neu nhu trong array post co thanh phan nay va id dang xet giong voi value cua thanh phan da chon
                //cho no lam select 
            } else {
                return !empty($default) && $default == $value ?  " selected=\"selected\"" : null; 
                //neu so nhap vao cho bien record bang voi id cua quoc gia dang xet thi cho quoc gia do la selected, duoc chon mac dinh
            }
        }
        
        public function getPostArray($expected = null) {
            $out = array();
            if($this->isPost()) {
                foreach($_POST as $key => $value) {
                    if(!empty($expected)) {
                        if(in_array($key, $expected)) {
                            //tim xem trong array post co cac key giong nhu cac key duoc dua vao tu bien expected hay khong
                            if(is_string($value)) { $out[$key] = strip_tags($value); } else { $out[$key] = $value; }
                            
                            //neu co thi cho vao bien out de return
                        }
                    } else {
                        $out[$key] = strip_tags($value);
                    }
                }
            }
            return $out;
        }
        
        public function stickyText($field, $value = null) {
            if($this->isPost($field)) {
                return stripslashes($this->getPost($field));
            } else {
                return !empty($value) ? $value : null;
            }
        }
        
        public function stickyRadio($field = null, $value = null, $data = null) {
            $post = $this->getPost($field);
            if(!Helper::isEmpty($post)) {
                if($post == $value) {
                    return ' checked="checked"';
                }
            } else {
                return !Helper::isEmpty($data) && $value == $data ? ' checked ="checked"' : null;
            }
        }
        
        public function stickyRemoveClass ($field = null, $value = null, $data= null, $class = null, $single = false) {
            $post = $this->getPost($field);
            if(!Helper::isEmpty($post)) {
                if($post != $value) {
                    return $single ? ' class="'.$class.'"' : ' '.$class;
                }
            } else {
                if($value != $data) {
                    return $single ? ' class="'.$class.'"' : ' '.$class;
                }
            }
        }
        
    }
?>
