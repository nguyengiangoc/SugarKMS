<?php
    class dbase {
        private $_host = "localhost";
        private $_user = "root";
        private $_password = "";
        private $_name = "sugar";
        
        private $_conndb = false;
        public $_last_query = null;
        public $_affected_rows = 0;
        
        public $_insert_keys = array();
        public $_insert_values = array();
        public $_update_sets = array();
        
        public $_id;
        
        public $_members = 'members';
        
        public $_districts = 'districts';
        public $_high_schools = 'high_schools';
        public $_universities = 'universities';
        public $_school_abbr = 'school_abbr';
        
        public $_involvements = 'involvements';  
        
        public $_projects = 'projects';
        public $_project_types = 'project_types';
        public $_project_waves = 'project_waves';
        
        public $_positions = 'positions';
        public $_teams = 'teams';
        public $_position_team = 'position_team';
        
        
        public $_pages = 'pages';
        public $_page_groups = 'page_groups';
        public $_page_criteria = 'page_criteria';
        public $_page_params = 'page_params';
        
        public $_contact_access = 'contact_access';
        
        public $_applications = 'applications';
        public $_application_status = 'application_status';
        public $_recruitments = 'recruitments';
        public $_questions = 'questions';
        public $_choices = 'question_choices';
        
        public function __construct() {
            //ham construct duoc thi hanh ngay khi tao ra 1 object moi thuoc class nay
            $this->connect();
            //ket noi voi database ngay khi tao ra object dbase
        }
        
        private function connect() {
            $this->_conndb = mysql_connect($this->_host, $this->_user, $this->_password);
            if (!$this->_conndb) {
                die ("Database connection failed:<br/>" . mysql_error());
            } else {
                $_select = mysql_select_db($this->_name, $this->_conndb);
                if (!$_select) {
                    die("Selection failed:</br>" . mysql_error());
                }
                mysql_set_charset("utf8",$this->_conndb);
            }
        }
        
        public function close() {
            if(!mysql_close($this->_conndb)) {
                die ("Closing connection failed.");
            }
        }
        
        public function escape($value) {
            if(function_exists("mysql_real_escape_string")) {
                if (get_magic_quotes_gpc()) {
                    $value = stripslashes($value);
                }
                $value = mysql_real_escape_string($value);    
            } else {
                if (!get_magic_quotes_gpc()) {
                    $value = addslashes($value);
                }
            }
            return $value;
        }
        
        public function query($sql) {
            $this->_last_query = $sql;
            $result = mysql_query($sql, $this->_conndb);
            $this->displayQuery($result);
            return $result;
        }
        
        public function displayQuery($result) {
            if(!$result) {
                $output = "Database query failed: " . mysql_error() . "<br / >";
                $output .= "Last SQL query was: " . $this->_last_query;
                die($output);
            } else {
                $this->_affected_rows = mysql_affected_rows($this->_conndb);
            }
        }
        
        public function fetchAll($sql) {
            $result = $this->query($sql);
            $out = array();
            while ($row = mysql_fetch_assoc($result)) {
                $out[] = $row;
            }
            mysql_free_result($result);
            return $out;
        }
        
        public function fetchOne ($sql) {
            $out = $this->fetchAll($sql);
            return array_shift($out);
            //array shift lay ra thanh phan dau tien trong mot mang
        }
        
        public function lastId() {
            return mysql_insert_id($this->_conndb);
            //sau khi ket noi dc voi database thi conndb khong con gia tri false ma la lan ket noi gan day nhat
        }
        
        public function prepareInsert($array = null) {
            if(!empty($array)) {
                foreach($array as $key => $value) {
                    $this->_insert_keys[] = $key;
                    $this->_insert_values[] = $this->escape($value);
                }
            }
        }
        
        public function insert($table = null) {
            if(!empty($table) && !empty($this->_insert_keys) && !empty($this->_insert_values)) {
            $sql = "INSERT INTO `{$table}` (`".implode("`, `", $this->_insert_keys) . "`) VALUES ('" . implode("', '", $this->_insert_values) . "')";
                
                if($this->query($sql)) {
                    $this->_insert_keys = array();
                    $this->_insert_values = array();
                    $this->_id = $this->lastId();
                    return true;
                } 
                return false;
            }
        }
        
        public function prepareUpdate($array = null) {
            if(!empty($array)) {
                foreach($array as $key => $value) {
                    $this->_update_sets[] = "`{$key}` = '".$this->escape($value)."'";
                }
            }
        }
        
        public function update($table = null, $id = null) {
            if(!empty($table) && !empty($id) && !empty($this->_update_sets)) {
                $sql = "UPDATE `{$table}` SET ".implode(", ", $this->_update_sets)." WHERE `id` = '".$this->escape($id)."'";
                
                if( $this->query($sql)){
                    $this->_update_sets = array();
                    return true;
                }
                return false;
            }
        }
        
        public function getTable($case = null) {
            if(!empty($case)) {
                switch($case) {
                    
                    case 'application':
                    $table = $this->_applications;
                    break;
                    
                    
                    case 'choice':
                    $table = $this->_choices;
                    break;
                    
                    case 'member':
                    $table = $this->_members;
                    break;
                    
                    case 'high_school':
                    $table = $this->_high_schools;
                    break;
                    
                    case 'university':
                    $table = $this->_universities;
                    break;   
                    
                    case 'school_abbr':
                    $table = $this->_school_abbr;
                    break;
                    
                    case 'page':
                    $table = $this->_pages;
                    break;
                    
                    case 'page_group':
                    $table = $this->_page_groups;
                    break;
                    
                    case 'page_params':
                    $table = $this->_page_params;
                    break;
                    
                    case 'involvement':
                    $table = $this->_involvements;
                    break;
                    
                    case 'page_criteria':
                    $table = $this->_page_criteria;
                    break;
                    
                    case 'position':
                    $table = $this->_positions;
                    break;
                    
                    case 'project_type':
                    $table = $this->_project_types;
                    break;
                    
                    case 'question':
                    $table = $this->_questions;
                    break;
                    
                    case 'team':
                    $table = $this->_teams;
                    break;
                    
                    case 'position_team':
                    $table = $this->_position_team;
                    break;
                    
                    case 'application':
                    $table = $this->_applications;
                    break;
                    
                    case 'recruitment':
                    $table = $this->_recruitments;
                    break;
                    
                    default:
                    $table = '';
                    
                    
                }
                return $table;
                
            }
        }
        
        public function remove($case = null, $id = null, $params = null) {
            if(!empty($case)) {
                $table = $this->getTable($case);
                if(isset($table) && !empty($table)) {
                    if(!empty($id) && empty($params)) {
                        $sql = "DELETE FROM `".$this->escape($table)."` WHERE `id` = '".$this->escape($id)."'";
                        return $this->query($sql);
                    }
                    if(empty($id) && !empty($params) && is_array($params)) {
                        $sql = "DELETE FROM `".$this->escape($table)."` ";
                        $where = array();
                        $sql .= " WHERE ";
                        foreach($params as $key => $value) {
                            $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."'";
                        }
                        $sql .= implode(' AND ', $where);
                        return $this->query($sql);
                    }
                    
                }
            }
        }
        
        public function add($case = null, $params = null) {
            if(!empty($case) && !empty($params) && is_array($params)) {
                $table = $this->getTable($case);
                if(isset($table) && !empty($table)) {
                    $this->prepareInsert($params);
                    $success = array($this->insert($table));
                    
                    if($success) {
                        return array('success' => $success, 'id' => $this->_id);
                    } else {
                        return array('success' => $success);
                    }
                    
                    
                }
            }
        }
        
        public function changeField($case = null, $id = null, $params = null) {
            if(!empty($case) && !empty($id) && !empty($params) && is_array($params)) {
                $table = $this->getTable($case);
                if(isset($table) && !empty($table)) {
                    $this->prepareUpdate($params);
                    return $this->update($table, $id);
                    
                    
                    
                }
            }
        }
        
        public function get($case = null, $params = null) {
            if(!empty($case)) {
                $table = $this->getTable($case);
                if(isset($table) && !empty($table)) {
                    $sql = "SELECT * FROM `{$table}` ";
                    if(!empty($params) && is_array($params)) {
                        $sql .= " WHERE ";
                        $where = array();
                        foreach($params as $key => $value) {
                            $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."'";
                            
                        }
                        $sql .= implode(' AND ', $where);
                        
                    }
                    return $this->fetchAll($sql);
                }
                
            }
            
        }
        
    }
?>