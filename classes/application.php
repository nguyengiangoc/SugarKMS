<?php
    class Application extends Dbase {
        
        public function getApplications($params = null) {
            if(!empty($params) && is_array($params)) {
                $sql = "SELECT *
                        FROM `{$this->_applications}` ";
                $where = array();
                
                foreach($params as $key => $value) {
                    $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."' ";
                    
                }
                $sql .= ' WHERE '.implode(' AND ', $where);
                
                return $this->fetchAll($sql);
            }
        }
        
        public function getQuestionsForApplication($id = null) {
            if(!empty($id)) {
                $sql = "SELECT q.*               
                        FROM `{$this->_questions}` q 
                        WHERE q.`application_id` = '".$this->escape($id)."'";
                return $this->fetchAll($sql);
            }
        }
        
    }
?>