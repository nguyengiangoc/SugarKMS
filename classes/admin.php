<?php
    class Admin extends Application {
        private $_table = 'admins';
        private $_members = 'members';        
        public $_id;
        
        public function isUser($email = null, $password = null) {
            if(!empty($email) && !empty($password)) {
                //$password = Login::string2Hash($password);
                $sql = "SELECT * FROM `{$this->_members}` WHERE `personal_email` = '".$this->db->escape($email)."' AND `password` = '".$this->db->escape($password)."'";
                $result = $this->db->fetchOne($sql);
                if(!empty($result)) {
                    $this->_id = $result['id'];
                    return true;
                }
                return false;
            }
        }
        
        public function getAdminProfile($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_members}` WHERE `id` = ".intval($id);
                return $this->db->fetchOne($sql);
            }
        }
        
    }
?>