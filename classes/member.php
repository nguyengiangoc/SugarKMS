<?php
    class Member extends Dbase {
        
        public $_default_password = 'ProjectSugar';
        
        public function __construct() {
            parent::__construct();
        }
        
        public function getMembers($params = null) {
            $sql = "SELECT m.*, h.`name` AS `high_school`, u.`name` AS `uni`, d.`name` AS `district_name` 
                        FROM `{$this->_members}` m 
                        LEFT JOIN `{$this->_high_schools}` h ON m.`high_school` = h.`id`
                        LEFT JOIN `{$this->_universities}` u ON m.`uni` = u.`id`
                        LEFT JOIN `{$this->_districts}` d ON m.`district` = d.`id`";
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
        
        public function getMemberById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT m.*, h.`name` AS `high_school`, u.`name` AS `uni`, d.`name` AS `district_name` 
                        FROM `{$this->_members}` m 
                        LEFT JOIN `{$this->_high_schools}` h ON m.`high_school` = h.`id`
                        LEFT JOIN `{$this->_universities}` u ON m.`uni` = u.`id`
                        LEFT JOIN `{$this->_districts}` d ON m.`district` = d.`id`
                        WHERE m.`id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
        }
        
        public function getMemberByHash($hash = null) {
            if(!empty($hash)) {
                $sql = "SELECT * FROM `{$this->_members}` WHERE `cookie_hash_kms` = '".$this->escape($hash)."'";
                return $this->fetchOne($sql);
            }
        }
        
        public function getMemberByCrit($criteria) {
            if(is_array($criteria) && array_key_exists('personal', $criteria) && array_key_exists('involvements', $criteria)) {
                
                // CASE 1: ONLY PERSONAL
                if(!empty($criteria['personal']) && empty($criteria['involvements'])) {
                    
                    $sql = "SELECT m.*, h.`name` AS `high_school`, u.`name` AS `uni` 
                            FROM `{$this->_members}` m 
                            LEFT JOIN `{$this->_high_schools}` h ON m.`high_school` = h.`id`
                            LEFT JOIN `{$this->_universities}` u ON m.`uni` = u.`id` 
                            WHERE ";
                    
                    if(array_key_exists('name', $criteria['personal'])) {
                        $sql .= "m.`name` LIKE '%".$this->escape($criteria['personal']['name'])."%'";
                        unset($criteria['personal']['name']);
                        if(!empty($criteria['personal'])) {
                            $sql .= " AND ";
                        }
                    } 
                    
                    $params = array();
                    foreach($criteria['personal'] as $key => $value) {
                        $params[] = "m.`{$key}` = '".$this->escape($value)."'";
                    }
                    $sql .= implode(' AND ',$params);
                    
                //CASE 2: SEARCH BY INVOLVEMENTS    
                } else if(!empty($criteria['involvements'])) {                    
                    
                    $sql = "SELECT DISTINCT m.*, h.`name` AS `high_school`, u.`name` AS `uni`
                                FROM `{$this->_members}` m 
                                JOIN `{$this->_involvements}` i ON m.`id` = i.`member_id`
                                LEFT JOIN `{$this->_high_schools}` h ON m.`high_school` = h.`id`
                                LEFT JOIN `{$this->_universities}` u ON m.`uni` = u.`id` ";
                    
                    switch(count($criteria['involvements'])) {
                        
                        //CASE 2.1: 1 CRITERIA
                        case 1:
                            $sql .= " WHERE ";

                            if(array_key_exists('project_year', $criteria['involvements'])) {
                                
                                //CASE 2.1.1: SEARCH BY YEAR
                                $sql .= " i.`year_start` = ".$criteria['involvements']['project_year']." 
                                        OR i.`year_end` = ".$criteria['involvements']['project_year'];
                                
                                     
                            } else {
                                //CASE 2.1.2: SEARCH BY OTHER 3
                                foreach($criteria['involvements'] as $key => $value) {
                                    $sql .= "i.`{$key}` = '".$this->escape($value)."'";
                                }
                                
                            }
                        break;
                        
                        //CASE 2.2: 2 CRITERIA
                        case 2:
                            if(array_key_exists('project_year', $criteria['involvements'])) {
                                
                                //YEAR + POSITION
                                if(array_key_exists('position_id', $criteria['involvements'])) {
                                    $sql .= " WHERE 
                                        i.`position_id` = ".$criteria['involvements']['position_id']."
                                        AND (i.`year_start` = ".$criteria['involvements']['project_year']." 
                                        OR i.`year_end` = ".$criteria['involvements']['project_year'].")";
                                    return $this->fetchAll($sql);
                                }
                                
                                //YEAR + TEAM
                                if(array_key_exists('team_id', $criteria['involvements'])) {
                                    $sql .= " WHERE 
                                        i.`team_id` = ".$criteria['involvements']['team_id']."
                                        AND (i.`year_start` = ".$criteria['involvements']['project_year']." 
                                        OR i.`year_end` = ".$criteria['involvements']['project_year'].")";
                                    
                                }
                                
                                //YEAR + PROJECT
                                if(array_key_exists('project_type_id', $criteria['involvements'])) {
                                    $sql .= " 
                                        JOIN `{$this->_projects}` pi ON i.`project_id` = pi.`id`
                                        WHERE 
                                        i.`project_type_id` = ".$criteria['involvements']['project_type_id']."
                                        AND pi.`year_start` = ".$criteria['involvements']['project_year'];
                                    
                                }
                                
                                
                                                                    
                            } else {
                                
                                //EVERY OTHER CASES, SEARCH BY EXACT FROM INVOLVEMENTS
                                $sql .= " WHERE ";
                                $params = array();
                                foreach($criteria['involvements'] as $key => $value) {
                                    $params[] = "i.`{$key}` = '".$this->escape($value)."'";
                                }
                                $sql .= implode(' AND ',$params);
                                
                            }
                            
                        break;
                        
                        // CASE 2.3: 3 CRITERIA
                        case 3:
                            $sql .= "
                                JOIN `{$this->_projects}` pi ON i.`project_id` = pi.`id`
                                WHERE ";
                            if(array_key_exists('project_year', $criteria['involvements'])) { 
                                $sql .= "pi.`year_start` = ".$criteria['involvements']['project_year']." AND ";
                                unset($criteria['involvements']['project_year']);
                            }
                            $params = array();
                            foreach($criteria['involvements'] as $key => $value) {
                                $params[] = "i.`{$key}` = '".$this->escape($value)."'";
                            }
                            $sql .= implode(' AND ',$params);
                        break;
                        
                        // CASE 2.4: 4 CRITERIA
                        case 4:
                            $sql .= "
                                JOIN `{$this->_projects}` pi ON i.`project_id` = pi.`id`
                                WHERE 
                                i.`project_type_id` = ".$criteria['involvements']['project_type_id']."
                                AND i.`position_id` = ".$criteria['involvements']['position_id']."
                                AND i.`team_id` = ".$criteria['involvements']['team_id']."
                                AND pi.`year_start` = ".$criteria['involvements']['project_year'];     
                        break;
                        
                    }
                    
                    $sql .= " AND `member` = 1 ";
                    
                    if(!empty($criteria['personal'])) {
                        $sql .= ' AND ';
                        if(array_key_exists('name', $criteria['personal'])) {
                            $sql .= "m.`name` LIKE '%".$this->escape($criteria['personal']['name'])."%'";
                            unset($criteria['personal']['name']);
                            if(!empty($criteria['personal'])) {
                                $sql .= " AND ";
                            }
                        } 
                        
                        $params_personal = array();
                        foreach($criteria['personal'] as $key => $value) {
                            $params_personal[] = "m.`{$key}` = '".$this->escape($value)."'";
                        }
                        $sql .= implode(' AND ',$params_personal);
                    }
                    
                    
                    
                }
                $sql .= ' AND m.`member` = 1 ';
                return $this->fetchAll($sql);
                
            }
            return null;
        }
        
        public function getInvolvement($id) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` WHERE `id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
        }
        
        
        
        public function getInvolvements($params = null, $active = false) {
            if(!empty($params) && is_array($params)) {
                $sql = "SELECT i.*, p.`name` AS `project_name`, f.`name` AS `team_name`, 
                            CASE i.`position_id`
                                WHEN 7 THEN
                                    IF(i2.`member_id` is null, 
                                        LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                        RIGHT(po.`name`, LOCATE('/',po.`name`)+1))
                                WHEN 4 THEN
                                    IF(i3.`member_id` is null, 
                                        LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                        RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                                ELSE po.`name`
                                
                            END 
                            AS `position_name`,
                            pi.`year_start` AS `project_year`,
                            CONCAT (
                                IF(p.`same_start_end` = 1, 
                                    pi.`year_end`, 
                                    IF(p.`write_two_years` = 1, 
                                        CONCAT(pi.`year_start`,' - ',pi.`year_end`),
                                        pi.`year_start`
                                    )
                                ),
                                IF(p.`wave` = 1,
                                    CONCAT(' (',w.`name`,')'), '')
                            )
                            AS `project_time`,
                            CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `participation_time`                            
                            FROM `{$this->_involvements}` `i` 
                            JOIN `{$this->_projects}` `pi` ON i.`project_id` = pi.`id`
                            JOIN `{$this->_project_types}` `p` ON i.`project_type_id` = p.`id`
                            JOIN `{$this->_teams}` f ON i.`team_id` = f.`id`
                            JOIN `{$this->_positions}` po ON i.`position_id` = po.`id`
                            LEFT JOIN `{$this->_project_waves}` w ON i.`project_type_id` = w.`project_type_id` AND i.`wave_id` = w.`id` 
                            LEFT JOIN `{$this->_involvements}` i2 
                                ON i2.`project_id` = i.`project_id`
                                AND i2.`team_id` = i.`team_id`
                                AND i2.`position_id` = i.`position_id`
                                AND i2.`position_id` = 7
                                AND i2.`member_id` <> i.`member_id`
                            LEFT JOIN `{$this->_involvements}` i3 
                                ON i3.`project_id` = i.`project_id`
                                AND i3.`team_id` = i.`team_id`
                                AND i3.`position_id` = i.`position_id`
                                AND i3.`position_id` = 4
                                AND i3.`member_id` <> i.`member_id`
                        WHERE ";
                        
                        $where = array();
                        foreach($params as $key => $value) {
                            $where[] = " i.`".$this->escape($key)."` = '".$this->escape($value)."' ";
                            
                        }
                        $sql .= implode(' AND ', $where);
                        
                        if($active) {
                            $sql .= "AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) )";
                        }
                        $sql .= "ORDER BY i.`year_start` DESC, pi.`month_start` DESC";
                return $this->fetchAll($sql);
                                                
            }
        }
        
        public function isFounder($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` WHERE `member_id` = '".$this->escape($id)."' AND `position_id` = 9 ORDER BY `year_end` DESC";
                $result = $this->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
                
        public function isAdmin($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` WHERE `member_id` = '".$this->escape($id)."' AND `position_id` = 10 ORDER BY `year_end` DESC";
                $result = $this->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isPresident($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `member_id` = '".$this->escape($id)."' 
                        AND `position_id` = 8 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result = $this->fetchOne($sql);                               
                return empty($result) ? false : true;
            }
            return false;
        }
                       
        public function canEditMember($editor = null, $target = null) {
            if(!empty($editor) && !empty($target)) {
                
                //ai cung co the sua profile cua ban than
                if($editor == $target) return true;
                
                //admin co the sua profile cua tat ca moi nguoi
                if($this->isAdmin($editor)) return true;

                
                return false;                
                
            }
            return false; 
        }
        
        public function canEditProject($editor = null, $project = null) {
            if(!empty($editor) && !empty($project)) {
                
                //admin & president co the sua tat ca project
                if($this->isAdmin($editor) || $this->isPresident($editor)) return true;
                
                return false;
            }
        }
        
        public function addMember($array = null) {
            if(!empty($array) & is_array($array)) {
                
                //$params = array(
//                    "name" => $this->escape($array['name']),
//                    "gender" => $this->escape($array['gender']),
//                    "day" => $array['day'],
//                    "month" => $array['month'],
//                    "year" => $array['year'],
//                    "personal_email" => $this->escape($array['personal_email']),
//                    "phone" => $this->escape($array['phone']),
//                    "facebook" => $this->escape($array['facebook']),
//                    "skype" => $this->escape($array['skype']),
//                    "high_school" => $this->escape($array['high_school']),
//                    "grad_year_h" => $this->escape($array['grad_year_h']),
//                    "uni" => $this->escape($array['uni']),
//                    "grad_year_u" => $this->escape($array['grad_year_u']),
//                    "password" => Login::hash(strtolower(str_replace(' ', '', $this->escape($array['name']))))
//                );
                //$this->prepareInsert($params); 
                $this->prepareInsert($array); 
                $out = $this->insert($this->_members);
                $id = $this->_id; 
                
                //if(!empty($array['high_school'])) {
//                    $sql_h = "SELECT 1 FROM `{$this->_high_schools}` WHERE `name` = '".$this->escape($array['high_school'])."'"; 
//                    $result_h = $this->fetchOne($sql_h);
//                    if(empty($result_h)) {
//                        $params_high_school = array('name' => $this->escape($array['high_school']));
//                        $this->prepareInsert($params_high_school);
//                        $this->insert($this->_high_schools);
//                    }
//                }
//                
//                if(!empty($array['uni'])) {
//                    $sql_u = "SELECT 1 FROM `{$this->_universities}` WHERE `name` = '".$this->escape($array['uni'])."'"; 
//                    $result_u = $this->fetchOne($sql_u);
//                    if(empty($result_u)) {
//                        $params_university = array('name' => $this->escape($array['uni']));
//                        $this->prepareInsert($params_university);
//                        $this->insert($this->_universities);
//                    }
//                }

                return array('result' => true, 'id' => $id);
            }
            return array('result' => false, 'id' => null);
        }
        
        public function updateMember($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params);
                
                //if(isset($params['high_school'])) {
//                    $sql_h = "SELECT 1 FROM `{$this->_high_schools}` WHERE `name` = '".$this->escape($params['high_school'])."'"; 
//                    $result_h = $this->fetchOne($sql_h);
//                    if(empty($result_h)) {
//                        $params_high_school = array('name' => $this->escape($params['high_school']));
//                        $this->prepareInsert($params_high_school);
//                        $this->insert($this->_high_schools);
//                    }
//                }
//                
//                if(isset($params['uni'])) {
//                    $sql_u = "SELECT 1 FROM `{$this->_universities}` WHERE `name` = '".$this->escape($params['uni'])."'"; 
//                    $result_u = $this->fetchOne($sql_u);
//                    if(empty($result_u)) {
//                        $params_university = array('name' => $this->escape($params['uni']));
//                        $this->prepareInsert($params_university);
//                        $this->insert($this->_universities);
//                    }
//                }
                return $this->update($this->_members, $id);
            }
            return false;
        }
        
        public function updateInvolvement($id = null, $array = null) {
            if(!empty($id) && !empty($array) && is_array($array)) {
                $this->prepareUpdate($array); 
                return $this->update($this->_involvements, $id);
            }
        }
        
        public function isDuplicateEmail($email = null, $id = null) {
            if(!empty($email)) {
                if($email != 'N/A') {
                    $sql = "SELECT * FROM `{$this->_members}` WHERE `personal_email` = '".$this->escape($email)."'";
                    if(!empty($id)) {
                        $sql .= "AND `id` != '".$this->escape($id)."'";
                    }
                    $result = $this->fetchAll($sql);
                    return !empty($result) ? true : false;
                } else {
                    return false;
                }
                   
            }
            return false;
        }
        
        public function getNameList($name = null, $projectId = null) {
            if(!empty($name)) {
                if(!empty($projectId)) {
                    $sql = "SELECT `name`, `id`, `personal_email` AS `email`,
                            EXISTS(SELECT 1 
                                FROM `{$this->_involvements}` `i`
                                WHERE `i`.`project_id` = '".$this->escape($projectId)."'
                                AND `i`.`member_id` = `m`.`id`) AS `in_project`
                            FROM `{$this->_members}` `m`
                            WHERE `name` LIKE '%".$this->escape($name)."%' ORDER BY `in_project` ASC LIMIT 5 ";
                    return $this->fetchAll($sql);
                } else {
                    $sql = "SELECT `name`, `id`, `personal_email`
                            FROM `{$this->_members}` `m`
                            WHERE `name` LIKE '%".$this->escape($name)."%' LIMIT 4 ";
                                                        
                    return $this->fetchAll($sql);
                }
                
            } else {
                return null;
            }
        }
         
        public function checkNameExists($name = null) {
            if(!empty($name)) {
                $sql = "SELECT COUNT(1) as `result` FROM `{$this->_members}` WHERE `name` = '".$this->escape($name)."'";
                return $this->fetchOne($sql);
            }
        }
        
        public function getAllDistricts() {
            $sql = "SELECT * FROM `{$this->_districts}` ORDER BY `id`";
            return $this->fetchAll($sql);
        }
        
        public function getAllContactAccessRights() {
            $sql = "SELECT * FROM `{$this->_contact_access}` ORDER BY `exco` DESC, `current` DESC";
            return $this->fetchAll($sql);
        }
        
        public function canViewMemberContact($viewer = null, $subject = null, $project = null) {
            if(!empty($viewer)) {
                $sql = "SELECT * FROM `{$this->_contact_access}` ORDER BY `exco` DESC, `current` DESC";
                $rights = $this->fetchAll($sql);
                $result = array();
                foreach($rights as $right) {
                    
                    if($right['exco'] == 1) {
                        $type = " AND `project_type_id` = 5";
                    } else if($right['exco'] == 0) {
                        $type = " AND `project_type_id` != 5";
                    }
                    
                    if($right['current'] == 1) {
                        $current = " AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) )";
                    } else if($right['current'] == 0) {
                        $current = " AND ( ( i.`year_end` < YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` < MONTH(CURDATE()) ) )";
                    }
                    
                    $sql = "SELECT 1 FROM `{$this->_involvements}` i WHERE `member_id` = ".$this->escape($viewer).$type.$current;
                    $record = $this->fetchAll($sql);
                    if(empty($record)) {
                        $result[] = 0;
                    } else {
                        switch($right['view_right']) {
                            case 1: //view all
                            return true;
                            break;
                            
                            case 2: //same project
                            
                            if(!empty($subject) && empty($project)) {
                                $sql = "SELECT 1 
                                        FROM `{$this->_involvements}` i1
                                        JOIN `{$this->_involvements}` i2
                                            ON i1.`project_id` = i2.`project_id`
                                        WHERE i1.`member_id` = ".$this->escape($viewer)." AND i2.`member_id` = ".$this->escape($subject);
                                        if($right['exco'] == 1) {
                                            $sql .= " AND i1.`project_type_id` = 5";
                                        } else if($right['exco'] == 0) {
                                            $sql .= " AND i1.`project_type_id` != 5";
                                        }
                            
                            } else if(!empty($project) && empty($subject)) {
                                $sql = "SELECT 1 
                                        FROM `{$this->_involvements}` 
                                        WHERE `member_id` = ".$this->escape($viewer)." AND `project_id` = ".$this->escape($project);
                                
                            }
                            $involvement = $this->fetchAll($sql);
                            if(empty($involvement)) {
                                $result[] = 0;
                            } else {
                                $result[] = 1;
                            }
                            
                            break;
                        }
                    }
                }
                if(in_array(1, $result)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        public function generateURLentity($name) {
            if(!empty($name)) {
                $entity = strtolower($name);
                
                $array_a = array("a", "á", "à", "ả", "ã", "ạ", 
                                 "ă", "ắ", "ằ", "ẳ", "ẵ", "ặ", 
                                 "â", "ấ", "ầ", "ẩ", "ẫ", "ậ",
                                 "A", "Á", "À", "Ả", "Ã", "Ạ",
                                 "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ",
                                 "Â", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ");
                
                $entity = str_replace($array_a, "a", $entity);
                
                $array_e = array("e", "é", "è", "ẻ", "ẽ", "ẹ", 
                                 "ê", "ế", "ề", "ể", "ễ", "ệ",
                                 "E", "É", "È", "Ẻ", "Ẽ", "Ẹ",
                                 "Ê", "Ế", "Ề", "Ể", "Ễ", "Ệ");
                                 
                $entity = str_replace($array_e, "e", $entity);
                
                $array_i = array("i", "í", "ì", "ỉ", "ĩ", "ị",
                                 "I", "Í", "Ì", "Ỉ", "Ĩ", "Ị");
                
                $entity = str_replace($array_i, "i", $entity);
                
                $array_o = array("o", "ó", "ò", "ỏ", "õ", "ọ",                                  
                                 "ô", "ố", "ồ", "ổ", "ỗ", "ộ",
                                 "ơ", "ớ", "ờ", "ở", "ỡ", "ợ",
                                 "O", "Ó", "Ò", "Ỏ", "Õ", "Ọ",
                                 "Ô", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ",
                                 "Ơ", "Ớ", "Ờ", "Ở", "Ỡ", "Ợ");
                                 
                $entity = str_replace($array_o, "o", $entity);
                
                $array_u = array("u", "ú", "ù", "ủ", "ũ", "ụ", 
                                 "ư", "ứ", "ừ", "ử", "ữ", "ự",
                                 "U", "Ú", "Ù", "Ủ", "Ũ", "Ụ",
                                 "Ư", "Ứ", "Ừ", "Ử", "Ữ", "Ự"); 
                
                $entity = str_replace($array_u, "u", $entity);
                
                $array_y = array("y", "ý", "ỳ", "ỷ", "ỹ", "ỵ",
                                 "Y", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ");
                
                $entity = str_replace($array_y, "y", $entity);
                
                $array_d = array("đ", "Đ");
                
                $entity = str_replace($array_d, "d", $entity);
                
                
                $entity = str_replace(" ", "-", $entity);
                
                return ($entity);
                
                
            }
        }
        
        
    }
?>