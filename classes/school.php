<?php
    class School extends Application {
        
        private $_high_schools = 'high_schools';
        private $_universities = 'universities';
        
        public function __construct() {
            parent::__construct();
        }
        
        public function getHighSchoolList ($name) {
            if(!empty($name)) {
                $sql = "SELECT `name` FROM `{$this->_high_schools}` WHERE `name` LIKE '%".$this->db->escape($name)."%' LIMIT 4 ";
                return $this->db->fetchAll($sql);
            } 
            return null;
        }
        
        public function getUniversityList ($name) {
            if(!empty($name)) {
                $sql = "SELECT `name` FROM `{$this->_universities}` WHERE `name` LIKE '%".$this->db->escape($name)."%' LIMIT 4 ";
                return $this->db->fetchAll($sql);
            } 
            return null;
        }
        
    }
?>