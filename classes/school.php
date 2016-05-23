<?php
    class School extends Dbase {
        
        private $_limit = 4;
        
        public function __construct() {
            parent::__construct();
        }
        
        public function getHighSchoolList ($name) {
            if(!empty($name)) {
                $sql = "SELECT DISTINCT h.`name` 
                        FROM `{$this->_high_schools}` h 
                        LEFT JOIN `{$this->_school_abbr}` a
                            ON a.`school_id` = h.`id`
                        WHERE 
                        (a.`abbr` LIKE '%".$this->escape($name)."%' AND a.`high_school` = 1)
                        OR
                        h.`name` LIKE '%".$this->escape($name)."%'
                        LIMIT 4 ";
                $result1 = $this->fetchAll($sql);
                
                //$remaining = $this->_limit - count($result1);
//                
//                $sql = "SELECT `name` FROM `{$this->_high_schools}` WHERE `name` LIKE '%".$this->escape($name)."%' LIMIT 0, {$remaining} ";
//                $result2 = $this->fetchAll($sql);
//                
//                $result = array_merge($result1, $result2);
                return $result1;
            } 
            return null;
        }
        
        public function getUniversityList ($name) {
            if(!empty($name)) {
                $sql = "SELECT DISTINCT u.`name` 
                        FROM `{$this->_universities}` u  
                        LEFT JOIN `{$this->_school_abbr}` a
                            ON a.`school_id` = u.`id`
                        WHERE 
                        (a.`abbr` LIKE '%".$this->escape($name)."%' AND a.`high_school` = 0)
                        OR
                        u.`name` LIKE '%".$this->escape($name)."%'
                        LIMIT 4 ";
                $result1 = $this->fetchAll($sql);
                return $result1;
                //$sql = "SELECT u.`name` 
//                        FROM `{$this->_school_abbr}` a
//                        JOIN `{$this->_universities}` u 
//                            ON a.`school_id` = u.`id`
//                        WHERE a.`abbr` LIKE '%".$this->escape($name)."%' 
//                        AND a.`high_school` = 0
//                        LIMIT 0,2 ";
//                $result1 = $this->fetchAll($sql);
//                
//                $remaining = $this->_limit - count($result1);
//                
//                $sql = "SELECT `name` FROM `{$this->_universities}` WHERE `name` LIKE '%".$this->escape($name)."%' LIMIT 0, {$remaining} ";
//                $result2 = $this->fetchAll($sql);
//                
//                $result = array_merge($result1, $result2);
//                return $result;
            } 
            return null;
        }
        
        public function getSchoolByName($name = null, $high_school = false) {
            if(!empty($name)) {
                if($high_school) {
                    $sql = "SELECT `id`, `name` FROM `{$this->_high_schools}` WHERE `name` = '".$this->escape($name)."' LIMIT 1 ";
                    return $this->fetchOne($sql);
                } else {
                    $sql = "SELECT `id`, `name` FROM `{$this->_universities}` WHERE `name` = '".$this->escape($name)."' LIMIT 1 ";
                    return $this->fetchOne($sql);
                }
            }
        }
        
        public function getSchoolById($id = null, $high_school = false) {
            if(!empty($id)) {
                if($high_school) {
                    $sql = "SELECT `id`, `name` FROM `{$this->_high_schools}` WHERE `id` = '".$this->escape($id)."' LIMIT 1 ";
                    return $this->fetchOne($sql);
                } else {
                    $sql = "SELECT `id`, `name` FROM `{$this->_universities}` WHERE `id` = '".$this->escape($id)."' LIMIT 1 ";
                    return $this->fetchOne($sql);
                }
            }
        }
        
        public function addHighSchool($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->prepareInsert($params); 
                 
                if($this->insert($this->_high_schools)) {
                    $id = $this->_id;
                    return array('result' => true, 'id' => $id);
                };
            }
        }
        
        public function addUni($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->prepareInsert($params); 
                if($this->insert($this->_universities)) {
                    $id = $this->_id;
                    return array('result' => true, 'id' => $id);
                }
            }
        }
        
        public function getAllHighSchools() {
            $sql = "SELECT * FROM `{$this->_high_schools}`";
            return $this->fetchAll($sql);
        }
        
        public function getAllUni(){
            $sql = "SELECT * FROM `{$this->_universities}`";
            return $this->fetchAll($sql);
        }
        
        public function getSchoolAbbr($id = null, $high_school = false) {
            if(!empty($id)) {
                $sql = "SELECT a.`id`, a.`abbr` FROM `{$this->_school_abbr}` a WHERE a.`school_id` = '".$this->escape($id)."'";
                if($high_school) {
                    $sql .= " AND a.`high_school` = 1";
                } else {
                    $sql .= " AND a.`high_school` = 0";
                }
                return $this->fetchAll($sql);
            }
        }
        
        public function checkSchoolInMembers($id = null, $high_school = false) {
            if(!empty($id)) {
                $sql = "SELECT 1 FROM `{$this->_members}` WHERE ";
                if($high_school) {
                    $sql .= " `high_school` = '";
                } else {
                    $sql .= " `uni` = '";
                }
                $sql .= $this->escape($id)."' LIMIT 1";
                $result = $this->fetchAll($sql);
                if(!empty($result)) { return true; } else { return false; }
            }
        }
        
        public function updateHighSchool($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params); 
                return $this->update($this->_high_schools, $id);
            }
        }
        
        public function updateUni($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params); 
                return $this->update($this->_universities, $id);
            }
        }
        
        public function getAbbrById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT 1 FROM `{$this->_school_abbr}` WHERE `id` = ".$this->escape($id);
                return $this->fetchAll($sql);
            }
        }
        
        
    }
?>