<?php
    class Validation {
        public $objForm;
        
        public $_errors = array();
        
        public $_errorsMessages = array();
        
        public $_prefilled_fields = '';
        
        public $_message = array(
            "name" => "Please provide the name",
            "gender" => "Please provide the gender",
            "day" => "Please provide the day in the date of birth",
            "month" => "Please provide the month in the date of birth",
            "year" => "Please provide the year in the date of birth",
            "personal_email" => "Please provide the personal email",
            "not_email" => "Your input does not have the structure of an email",
            "duplicate_email" => "This email is already recorded in another profile",
            "phone" => "Please provide the phone number",
            "skype" => "Pleas provide the skype nickname",
            "facebook" => "Please provide the link to the member's Facebook personal page",
            "high_school" => "Please provide the high school",
            "uni" => "Please provide the university",
            "login" => "Your combination of email and password is not valid.",
            "grad_year_h" => "Please provide the high school graduation year",
            "grad_year_u" => "Please provide the university graduation year",
            "project_type_id" => "Please select the project type",
            "project_year" => "Please select the project year",
            "current" => "Please provide the current password",
            "current_mismatch" => "The password provided does not match your current password",
            "new" => "Please provide the new password",
            "retype" => "Please retype the new password",
            "new_mismatch" => "The retype new password does not match the new password."

        );
        
        public $_expected = array();
        //de cho vao nhung~ thanh phan trong form can duoc dem vao xu ly
        
        public $_required = array();
        
        public $_special = array();
        
        public $_post = array();
        
        public $_post_remove = array();
        
        public $_post_format = array();
        
        public function __construct($objForm = null) {
            $this->objForm = is_object($objForm) ? $objForm : new Form();
        }
        
        public function displayRequired($keyword = null) {
            if(!empty($keyword)) {
                if(in_array($keyword, $this->_required)) {
                    return '*';
                }
            }
        }
        
        public function displayPrefilledRequired($keyword = null) {
            if(!empty($keyword)) {
                if(array_key_exists($keyword, $this->_prefilled_fields)) {
                    return '*';
                }
            }
        }
        
        public function process() {
            if($this->objForm->isPost()) {
                //neu da co cac thanh phan trong array post va trong array required co ten cac field can phai dien
                $this->_post = !empty($this->_post) ? $this->_post : $this->objForm->getPostArray($this->_expected);
                //chi lay tu array post cac thanh phan co key nam trong array expected 
                //lay vao trong array post cua objValid
                if(!empty($this->_post)) {
                    foreach($this->_post as $key => $value) {
                    //luc nay da lay xong cac thanh phan trong array post
                        if(!empty($this->_special)) {
                            foreach($this->_special as $case) {
                                if(in_array($key, $case)) {
                                    $this->checkSpecial($key, $value, $case['case_type']);
                                }
                            } 
                            
                        } 
                        
                        if(in_array($key, $this->_required) && Helper::isEmpty($value)) {
                        //neu 
                            $this->add2Errors($key);
                        }
                        
                        //$this->check($key, $value); 
                        //tien hanh kiem tra tung thanh phan trong array post
                        //thanh phan email nam trong array special
                        //nen khi vong lap chay toi thanh phan email se chay quay ham checkSpecial, tuc la chay qua ham isEmail\
                        //neu ten key co trong array required nhung gia tri lay tu post la rong thi phai bao loi
                        //cho vao array error
                    }
                }
            }
        }
        
        public function checkSpecial($key, $value, $type) {
            switch($type) {
                case('check_is_email'):
                    if(empty($value)) {
                        $this->add2Errors($key);
                    } else {
                        if(!$this->isEmail($value)) {
                            $this->_errors['not_email'] = $key;
                        }
                    }
                break;
            }
        }
        
        public function check($key, $value) {
            if(!empty($this->_special) && array_key_exists($key, $this->_special)) {
                $this->checkSpecial($key, $value, $this->_special[$key]);
            } else {
                if(in_array($key, $this->_required) && Helper::isEmpty($value)) {
                //neu 
                    $this->add2Errors($key);
                }
            }
        }
        
        public function add2Errors($key = null, $value = null) {
            if(!empty($key)) {
                $this->_errors[] = $key; //them vao thanh phan tiep theo, index la so, khong phai co key rieng
                if(!empty($value)) {
                    $this->_errorsMessages['valid_'.$key] = $this->wrapWarn($value); 
                    //value dung de tao re validation message rieng khac voi message da co san trong array cua object
                } elseif (array_key_exists($key, $this->_message)) {
                    $this->_errorsMessages['valid_'.$key] = $this->wrapWarn($this->_message[$key]);
                }
            }
            
        }
        
        
        
        public function isEmail($email = null) {
            if(!empty($email)) {
                $result = filter_var($email, FILTER_VALIDATE_EMAIL);
                return !$result ? false : true;
            }
            return false;
        }
        
        public function isValid($array = null) {
            //phai cho ham nay chay thi process moi duoc chay
            //sau khi process chay xong thi se dua het error vao trong array error
            if(!empty($array)) {
                $this->_post = $array;
            }
            $this->process();
            if (empty($this->_errors) && !empty($this->_post)) {
                //remove all unwanted fields
                if(!empty($this->_post_remove)) {
                    //neu co thanh phan nao trong post remove, tuc la thanh phan nay la mot field trong form nhung khi xu ly khong can dung den
                    //thi xoa ra khoi array post
                    foreach($this->_post_remove as $value) {
                        unset($this->_post[$value]);
                    }
                }
                //format all required field
                if(!empty($this->_post_format)) {
                    foreach($this->_post_format as $key => $value) {
                        $this->format($key, $value);
                    }
                }
                return true;
            }
            return false;
        }
        
        public function format($key, $value) {
            switch($value) {
                case 'password':
                $this->_post[$key] = Login::hash($this->_post[$key]);
                break;
            }
        }
        
        public function validate($key) {
            if(!empty($this->_errors) && in_array($key, $this->_errors)) {
                $index = array_search($key, $this->_errors);
                if(is_string($index) && $index == 'not_email') {
                    return $this->wrapWarn($this->_message['not_email']);
                } else {
                    return $this->wrapWarn($this->_message[$key]);
                }
                
            }
            //method nay de hien thi loi~ cu the cua mot field
            //duoc goi ra ngay truoc field do trong form
        }
        
        
        public function wrapWarn($mess = null) {
            if(!empty($mess)) {
                return "<span class=\"warn\">{$mess}</span>";
            }
        }
        
        
        
        
        
        
        
    }
?>