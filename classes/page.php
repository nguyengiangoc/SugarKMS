<?php
    class Page extends Dbase {
        
        public $page_details = array(); 
        
        public function __construct() {
            parent::__construct();
        }
        
        public function setPageArray($array) {
            if(!empty($array) && is_array($array)) {
                $this->page_details = $array;
            }
        }
        
        public function checkIfGroupRecorded($name = null) {
            if(!empty($name)) {
                $sql = "SELECT 1 FROM `{$this->_page_groups}` WHERE `name` = '".$this->escape($name)."'";
                $result = $this->fetchAll($sql);
                if(empty($result)) {
                    return false;
                } else {
                    return true;
                }
            }
            
        }
        
        public function getPages($params = null, $order = null) {
            $sql = "SELECT * FROM `{$this->_pages}` ";
            if(!empty($params) && is_array($params)) {
                $sql .= " WHERE ";
                $where = array();
                foreach($params as $key => $value) {
                    $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."'";
                    
                }
                $sql .= implode(' AND ', $where);
                if(!empty($order) && is_array($order)) {
                    $sql .= " ORDER BY ";
                    $order_by = array();
                    foreach($order as $field => $way) {
                        $order_by[] = " `".$field."` ".strtoupper($way)." ";
                    }
                    $sql .= implode(', ',$order_by);
                    
                }
            }
            return $this->fetchAll($sql);
        }
        
        public function getPageFromURL($cPage = null, $params = null) {
            if(!empty($cPage) && !empty($params) && is_array($params)) {
                $sql = "SELECT p.`id` AS `!page_id` ";
                $join = array();
                $where = array();
                $select = array();
                $position = 1;
                foreach($params as $key => $value) {
                    $select[] = ", pr".$position.".`required_value` AS `".$key."` "; 
                    $join[] = " INNER JOIN `{$this->_page_params}` pr".$position." ON p.`id` = pr".$position.".`page_id` ";             
                    $where[] = "pr".$position.".`param` = '".$this->escape($key)."'";
                    $position++;
                }
                $sql .= implode(' ', $select);
                $sql .= " FROM `{$this->_pages}` p
                            INNER JOIN `{$this->_page_groups}` g
                                ON g.`id` = p.`group_id` ";
                $sql .= implode(' ', $join);
                $sql .= " WHERE g.`name` = '".$this->escape($cPage)."' ";
                $sql .= ' AND '.implode(' AND ', $where);
                //$sql .= "+";
                
                $pages = $this->fetchAll($sql);
                $result = array();
                foreach($pages as $page) {
                    $id = array_shift($page);
                    foreach($page as $param_name => $param_value) {
                        if($param_value != '') {
                            if($params[$param_name] != $param_value) {
                                continue 2;
                            }
                        } else {
                            if($params[$param_name] == '') {
                                continue 2;
                            }
                        }
                    }
                    $sql = "SELECT * FROM `{$this->_pages}` WHERE `id` = '".$id."'";
                    return $this->fetchOne($sql);
                }

                //$result = array();
//                foreach($pages as $page) {
//                    $id = array_shift($page);
//                    $check = array();
//                    foreach($page as $param_name => $param_value) {
//                        if($param_value != '') {
//                            if($params[$param_name] != $param_value) {
//                                $check[] = 0;
//                            } else {
//                                $check[] = 1;
//                            }
//                        } else {
//                            $check[] = 1;
//                        }
//                    }
//                    $result[] = array('check' => $check, 'id' => $id);
//                }
//                return $result;
            }

                
        }
        
        public function getPageParams($params = null, $order = null) {
            $sql = "SELECT * FROM `{$this->_page_params}` ";
            if(!empty($params) && is_array($params)) {
                $sql .= " WHERE ";
                $where = array();
                foreach($params as $key => $value) {
                    $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."'";
                    
                }
                $sql .= implode(' AND ', $where);
                if(!empty($order) && is_array($order)) {
                    $sql .= " ORDER BY ";
                    $order_by = array();
                    foreach($order as $field => $way) {
                        $order_by[] = " `".$field."` ".strtoupper($way)." ";
                    }
                    $sql .= implode(', ',$order_by);
                    
                }
            }
            return $this->fetchAll($sql);
        }
        
        public function getGroups($params = null, $order = null) {
                
            $sql = "SELECT * FROM `{$this->_page_groups}` ";
            if(!empty($params) && is_array($params)) {
                $where = array();
                $sql .= " WHERE ";
                foreach($params as $key => $value) {
                    $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."'";
                }
                $sql .= implode(' AND ', $where);
                
            }
            if(!empty($order) && is_array($order)) {
                    $sql .= " ORDER BY ";
                    $order_by = array();
                    foreach($order as $field => $way) {
                        $order_by[] = " `".$field."` ".strtoupper($way)." ";
                    }
                    $sql .= implode(', ',$order_by);
                    
                }
            return $this->fetchAll($sql);
            
        }        
        
        public function addCriteria($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->prepareInsert($params); 
                $this->insert($this->_page_criteria);
                $id = $this->_id; 
                if(!empty($id)) {
                    return array('success' => true, 'id' => $id);
                } else {
                    return array('success' =>  false, 'id' => '');
                }
            }
        }
        
        
                
        public function getLastPosition($group_id) {
            if(!empty($group_id)) {
                $sql = "SELECT MAX(`order`) AS `last` FROM `{$this->_pages}` WHERE `group_id` = '".$this->escape($group_id)."'";
                return $this->fetchOne($sql)['last'];
            }
            
        }
        
        public function addAction($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->prepareInsert($params); 
                $this->insert($this->_pages);
                $id = $this->_id; 
                if(!empty($id)) {
                    return array('success' => true, 'id' => $id);
                } else {
                    return array('success' =>  false, 'id' => '');
                }
            }
        }
        
        public function updateAction($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params); 
                return $this->update($this->_pages, $id);
            }
        }
        
        public function getCriteria($params = null) {
            $sql = "SELECT * FROM `{$this->_page_criteria}` ";
            if(!empty($params) && is_array($params)) {
                $where = array();
                $sql .= " WHERE ";
                foreach($params as $key => $value) {
                    $where[] = "`".$this->escape($key)."` = '".$this->escape($value)."'";
                }
                $sql .= implode(' AND ', $where);
                $sql .= ' ORDER BY `exco_project` DESC ';
            }
            return $this->fetchAll($sql);
        }
               
        public function removeAllPageAccess($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_page_criteria}` WHERE `page_id` = '".$this->escape($id)."'";
                return $this->query($sql);
            }
        }
        
        public function removeActionPage($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_page_criteria}` WHERE `page_id` = '".$this->escape($id)."'";
                $this->query($sql);
                $sql = "DELETE FROM `{$this->_pages}` WHERE `id` = '".$this->escape($id)."'";
                return $this->query($sql);
            }
        }
        
        public function canAccess($cPage_params = null, $member_id = null, $page_details = null, $cPage = null) {
            
            if(!empty($cPage_params) && !empty($member_id)) {
                if(empty($page_details) && !empty($cPage)) {
                    $page_details = $this->getPageFromURL($cPage, $cPage_params);
                    if(empty($page_details)) {
                        return false;
                    }
                }
                
                $objMember = new Member();
                
                if($objMember->isAdmin($member_id)) {
                    return true;
                } else {
                    
                    if($page_details['everyone']) {
                        return true;
                    } else {
                        
                        $criteria = $this->getCriteria(array('page_id' => $page_details['id']));
                        foreach($criteria as $criterion) {
                            
                            if($criterion['other'] != 0) {
                                
                                switch($criterion['other']) {
                                    
                                    case 1:
                                        if($member_id == $cPage_params['id']) {
                                            return true;
                                        }
                                    break;
                                    
                                }
                            } else {
                                
                                $involvements = $objMember->getInvolvements(array('member_id' => $member_id));
                                foreach($involvements as $involvement) {
                                    if($involvement['position_id'] == $criterion['position_id'] && $involvement['team_id'] == $criterion['team_id']) {
                                        if($criterion['currency']) {
                                            if(($involvement['year_end'] > date("Y")) || ($involvement['year_end'] == date("Y") && $involvement['month_end'] > date("m"))) {
                                                return true;
                                            }
                                        } else {
                                            
                                            return true;                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    
                }
                return false;
                
            }
            return false;
        }
        
        public function generateURL($cPage = null, $params = null) {
            $result = '/sugarkms/';
            if(!empty($cPage)) {
                $result .= $cPage;
                if(!empty($params)) {
                    if(array_key_exists('id', $params)) {
                        
                        switch($cPage) {
                            
                            case 'member':
                                $objMember = new Member();
                                $member = $objMember->getMemberById($params['id']);
                                //$params['id'] = $member['entity'].'-'.$params['id'];
                                $params['entity'] = $member['entity'];
                            break;
                            
                            case 'project':
                            case 'exco':
                            
                                $objProject = new Project();
                                $project = $objProject->getProjectById($params['id']);
                                $params['entity'] = str_replace(' ', '-',strtolower($project['name'])).'-'.strtolower(str_replace(' ', '', str_replace(')','',str_replace(' (','-',$project['project_time']))));
                            break;
                            
                            default:
                                if(count($params) == 1) {
                                    $params['action'] = 'view';
                                }
                                
                            break;
                            
                        }
                        
                        
                    }
                    $page_details = $this->getPageFromURL($cPage, $params);
                    if(!empty($page_details)) {
                        $page_params = $this->getPageParams(array('page_id' => $page_details['id']), array('order' => 'asc'));
                        
                        foreach($page_params as $page_param) {
                            if(!($page_param['param'] == 'action' && $page_param['required_value'] == 'view')) {
                                $result .= '/';
                                //$result .= $page_param['param'].'/';
                                $result .= empty($page_param['required_value']) ? $params[$page_param['param']] : $page_param['required_value']; 
                            }
                                                   
                        }
                                           
                    }
                }                
                return $result; 
            }
        }

        
    }
?>