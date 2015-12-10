<?php
    class Member extends Application {
       
        private $_members = 'members';
        private $_project_types = 'project_types';
        private $_projects = 'projects';
        private $_involvements = 'involvements';
        private $_teams = 'teams';
        private $_positions = 'positions';
        private $_waves = 'waves';
        private $_high_schools = 'high_schools';
        private $_universities = 'universities';

        private $_year = '';
        private $_next = '';
        
        public function __construct() {
            parent::__construct();
            $this->_year = date("Y");
            $this->_next = $this->_year + 1;
        }
        
        public function getAllMembers() {
            $sql = "SELECT * FROM `{$this->_members}` ORDER BY `name` ASC";
            return $this->db->fetchAll($sql);
        }
        
        public function getMemberById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_members}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getMemberByHash($hash = null) {
            if(!empty($hash)) {
                $sql = "SELECT * FROM `{$this->_members}` WHERE `cookie_hash` = '".$this->db->escape($hash)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getMemberByCrit($criteria) {
            if(is_array($criteria) && array_key_exists('personal', $criteria) && array_key_exists('involvements', $criteria)) {
                
                // CASE 1: ONLY PERSONAL
                if(!empty($criteria['personal']) && empty($criteria['involvements'])) {
                    
                    $sql = "SELECT `id`, `name`, `high_school`, `grad_year_h`, `uni`, `grad_year_u`, `personal_email` FROM `{$this->_members}` WHERE ";
                    
                    if(array_key_exists('name', $criteria['personal'])) {
                        $sql .= "`name` LIKE '%".$this->db->escape($criteria['personal']['name'])."%'";
                        unset($criteria['personal']['name']);
                        if(!empty($criteria['personal'])) {
                            $sql .= " AND ";
                        }
                    } 
                    
                    $params = array();
                    foreach($criteria['personal'] as $key => $value) {
                        $params[] = "`{$key}` = '".$this->db->escape($value)."'";
                    }
                    $sql .= implode(' AND ',$params);
                    
                    return $this->db->fetchAll($sql);
                } else if(!empty($criteria['involvements'])) {                    
                    
                    switch(count($criteria['involvements'])) {
                        
                        //CASE 2.1: 1 CRITERIA
                        case 1:
                            $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                FROM `{$this->_members}` m 
                                JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                WHERE ";

                            if(array_key_exists('project_year', $criteria['involvements'])) {
                                
                                //CASE 2.1.1: SEARCH BY YEAR
                                $sql .= " i.`year_start` = ".$criteria['involvements']['project_year']." 
                                        OR i.`year_end` = ".$criteria['involvements']['project_year'];
                                return $this->db->fetchAll($sql); 
                                     
                            } else {
                                //CASE 2.1.2: SEARCH BY OTHER 3
                                foreach($criteria['involvements'] as $key => $value) {
                                    $sql .= "i.`{$key}` = '".$this->db->escape($value)."'";
                                }
                                return $this->db->fetchAll($sql);
                            }
                        break;
                        
                        //CASE 2.2: 2 CRITERIA
                        case 2:
                            if(array_key_exists('project_year', $criteria['involvements'])) {
                                
                                //YEAR + POSITION
                                if(array_key_exists('position_id', $criteria['involvements'])) {
                                    $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                        FROM `{$this->_members}` m 
                                        JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                        WHERE 
                                        i.`position_id` = ".$criteria['involvements']['position_id']."
                                        AND (i.`year_start` = ".$criteria['involvements']['project_year']." 
                                        OR i.`year_end` = ".$criteria['involvements']['project_year'].")";
                                    return $this->db->fetchAll($sql);
                                }
                                
                                //YEAR + TEAM
                                if(array_key_exists('team_id', $criteria['involvements'])) {
                                    $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                        FROM `{$this->_members}` m 
                                        JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                        WHERE 
                                        i.`tean_id` = ".$criteria['involvements']['team_id']."
                                        AND (i.`year_start` = ".$criteria['involvements']['project_year']." 
                                        OR i.`year_end` = ".$criteria['involvements']['project_year'].")";
                                    return $this->db->fetchAll($sql);
                                }
                                
                                //YEAR + PROJECT
                                if(array_key_exists('project_type_id', $criteria['involvements'])) {
                                    $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                        FROM `{$this->_members}` m 
                                        JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                        JOIN `{$this->_projects}` pi ON i.`project_id` = pi.`id`
                                        WHERE 
                                        i.`project_type_id` = ".$criteria['involvements']['project_type_id']."
                                        AND pi.`year_start` = ".$criteria['involvements']['project_year'];
                                    
                                }
                                                                    
                            } else {
                                
                                //EVERY OTHER CASES, SEARCH BY EXACT FROM INVOLVEMENTS
                                $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                        FROM `{$this->_members}` m 
                                        JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                        WHERE ";
                                $params = array();
                                foreach($criteria['involvements'] as $key => $value) {
                                    $params[] = "i.`{$key}` = '".$this->db->escape($value)."'";
                                }
                                $sql .= implode(' AND ',$params);
                                return $this->db->fetchAll($sql);
                            }
                            
                        break;
                        
                        case 3:
                            $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                FROM `{$this->_members}` m 
                                JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                JOIN `{$this->_projects}` pi ON i.`project_id` = pi.`id`
                                WHERE ";
                            if(array_key_exists('project_year', $criteria['involvements'])) { 
                                $sql .= "pi.`year_start` = ".$criteria['involvements']['project_year']." AND ";
                                unset($criteria['involvements']['project_year']);
                            }
                            $params = array();
                            foreach($criteria['involvements'] as $key => $value) {
                                $params[] = "i.`{$key}` = '".$this->db->escape($value)."'";
                            }
                            $sql .= implode(' AND ',$params);
                        break;
                        
                        case 4:
                            $sql = "SELECT DISTINCT m.`id`, m.`name`, m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`, m.`personal_email`
                                FROM `{$this->_members}` m 
                                JOIN `{$this->_involvements}` i ON m.`id` = i.`person_id`
                                JOIN `{$this->_projects}` pi ON i.`project_id` = pi.`id`
                                WHERE 
                                i.`project_type_id` = ".$criteria['involvements']['project_type_id']."
                                AND i.`position_id` = ".$criteria['involvements']['position_id']."
                                AND i.`team_id` = ".$criteria['involvements']['team_id']."
                                AND pi.`year_start` = ".$criteria['involvements']['project_year'];     
                        break;
                        
                    }
                    
                    if(!empty($criteria['personal'])) {
                        $sql .= ' AND ';
                        if(array_key_exists('name', $criteria['personal'])) {
                            $sql .= "m.`name` LIKE '%".$this->db->escape($criteria['personal']['name'])."%'";
                            unset($criteria['personal']['name']);
                            if(!empty($criteria['personal'])) {
                                $sql .= " AND ";
                            }
                        } 
                        
                        $params_personal = array();
                        foreach($criteria['personal'] as $key => $value) {
                            $params_personal[] = "m.`{$key}` = '".$this->db->escape($value)."'";
                        }
                        $sql .= implode(' AND ',$params_personal);
                    }
                    
                    return $this->db->fetchAll($sql);
                    
                }
                return null;
                
            }
            return null;
        }
        
        public function getAllInvolvements($person_id = null, $active = false) {
            if(!empty($person_id)) {
                $sql = "SELECT i.*, p.`name` AS `project_name`, pi.*, f.`name` AS `team_name`, 
                            CASE i.`position_id`
                                WHEN 7 THEN
                                    IF(i2.`person_id` is null, 
                                        LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                        RIGHT(po.`name`, LOCATE('/',po.`name`)+1))
                                WHEN 4 THEN
                                    IF(i3.`person_id` is null, 
                                        LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                        RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                                ELSE po.`name`
                                
                            END 
                            AS `position_name`,
                            pi.`year_start` AS `project_year`,
                            CONCAT (
                                IF(pi.`same_start_end` = 'yes', 
                                    pi.`year_end`, 
                                    IF(pi.`write_two_years` = 'yes', 
                                        CONCAT(pi.`year_start`,' - ',pi.`year_end`),
                                        pi.`year_start`
                                    )
                                ),
                                IF(p.`wave` = 'yes',
                                    CONCAT(' (',w.`name`,')'), '')
                            )
                            AS `project_time`,
                            CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `participation_time`                            
                            FROM `{$this->_involvements}` `i` 
                            JOIN `{$this->_projects}` `pi` ON i.`project_id` = pi.`id`
                            JOIN `{$this->_project_types}` `p` ON i.`project_type_id` = p.`id`
                            JOIN `{$this->_teams}` f ON i.`team_id` = f.`id`
                            JOIN `{$this->_positions}` po ON i.`position_id` = po.`id`
                            LEFT JOIN `{$this->_waves}` w ON i.`project_type_id` = w.`project_type_id` AND i.`wave_id` = w.`id` 
                            LEFT JOIN `{$this->_involvements}` i2 
                                ON i2.`project_id` = i.`project_id`
                                AND i2.`team_id` = i.`team_id`
                                AND i2.`position_id` = i.`position_id`
                                AND i2.`position_id` = 7
                                AND i2.`person_id` <> i.`person_id`
                            LEFT JOIN `{$this->_involvements}` i3 
                                ON i3.`project_id` = i.`project_id`
                                AND i3.`team_id` = i.`team_id`
                                AND i3.`position_id` = i.`position_id`
                                AND i3.`position_id` = 4
                                AND i3.`person_id` <> i.`person_id`
                        WHERE i.`person_id` = '".$this->db->escape($person_id)."'";
                        if($active) {
                            $sql .= "AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) )";
                        }
                        $sql .= "ORDER BY i.`year_end` DESC, pi.`month_end` DESC";
                return $this->db->fetchAll($sql);
                                                
            }
        }
        
        
        //public function getActiveInvolvements($person_id = null) {
//            if(!empty($person_id)) {
//                $sql = "SELECT i.*, p.`name` AS `project_name`, pi.*, f.`name` AS `team_name`, po.`name` AS `position_name`, 
//                            pi.`year_start` AS `project_year`,
//                            IF(pi.`same_start_end` = 'yes', 
//                                pi.`year_end`, 
//                                IF(pi.`write_two_years` = 'yes', 
//                                    CONCAT(pi.`year_start`,' - ',pi.`year_end`),
//                                    pi.`year_start`
//                                )
//                            ) AS `project_time`,
//                            CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `participation_time`                            
//                            FROM `{$this->_involvements}` `i` 
//                            JOIN `{$this->_project_info}` `pi` ON i.`project_info_id` = pi.`id`
//                            JOIN `{$this->_projects}` `p` ON i.`project_id` = p.`id`
//                            JOIN `{$this->_teams}` f ON i.`team_id` = f.`id`
//                            JOIN `{$this->_positions}` po ON i.`position_id` = po.`id`
//                        WHERE `person_id` = '".$this->db->escape($person_id)."'
//                            AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) )
//                        ORDER BY i.`year_end` DESC, pi.`month_end` DESC";
//                return $this->db->fetchAll($sql);
//                                                
//            }
//        }
        
        public function getInvolvement($id = null) {
            if(!empty($id)) {
                $sql = "SELECT i.*, p.`name` AS `project_name`, pi.*, f.`name` AS `team_name`, po.`name` AS `position_name`, 
                            pi.`year_start` AS `project_year`,
                            IF(pi.`same_start_end` = 'yes', 
                                pi.`year_end`, 
                                IF(pi.`write_two_years` = 'yes', 
                                    CONCAT(pi.`year_start`,' - ',pi.`year_end`),
                                    pi.`year_start`
                                )
                            ) AS `project_time`,
                            CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `participation_time`                            
                            FROM `{$this->_involvements}` `i` 
                            JOIN `{$this->_projects}` `pi` ON i.`project_id` = pi.`id`
                            JOIN `{$this->_project_types}` `p` ON i.`project_type_id` = p.`id`
                            JOIN `{$this->_teams}` f ON i.`team_id` = f.`id`
                            JOIN `{$this->_positions}` po ON i.`position_id` = po.`id`
                            WHERE i.`id` = '".$this->db->escape($id)."'";
                return $this->db->fetchOne($sql);
                                                
            }
        }
        
        public function isFounder($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` WHERE `person_id` = '".$this->db->escape($id)."' AND `position_id` = 9 ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
                
        public function isAdmin($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` WHERE `person_id` = '".$this->db->escape($id)."' AND `position_id` = 10 ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isPresident($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `person_id` = '".$this->db->escape($id)."' 
                        AND `position_id` = 8 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);                               
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isEXCO($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `person_id` = '".$this->db->escape($id)."' 
                        AND `project_type_id` = 5 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);                
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isEXCOWelfare($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `person_id` = '".$this->db->escape($id)."' 
                        AND `project_type_id` = 5 AND `team_id` = 1 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isEXCOHead($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `person_id` = '".$this->db->escape($id)."' 
                        AND `project_type_id` = 5 AND `position_id` = 7 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isEXCOAssoc($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_involvements}` `i` 
                WHERE `person_id` = '".$this->db->escape($id)."' 
                AND `project_type_id` = 5 AND `position_id` = 6 
                AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                ORDER BY `year_end` DESC";
                $result = $this->db->fetchOne($sql);
                return empty($result) ? false : true;
            }
            return false;
        }
        
        public function isSameTeamEXCO($id1 = null, $id2 = null) {
            if(!empty($id1) && !empty($id2)) {
                
                $sql1 = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `person_id` = '".$this->db->escape($id1)."' 
                        AND `project_type_id` = 5 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result1 = $this->db->fetchOne($sql1);
                if(empty($result1)) return false;
                
                $sql2 = "SELECT * FROM `{$this->_involvements}` `i` 
                        WHERE `person_id` = '".$this->db->escape($id2)."' 
                        AND `project_type_id` = 5 
                        AND ( ( i.`year_end` > YEAR(CURDATE()) ) OR ( i.`year_end` = YEAR(CURDATE()) AND i.`month_end` >= MONTH(CURDATE()) ) ) 
                        ORDER BY `year_end` DESC";
                $result2 = $this->db->fetchOne($sql2);
                if(empty($result2)) return false;
                
                if($result1['team_id'] == $result2['team_id']) {
                    return true;
                } else {
                    return false;
                }
            }
        }
                
        public function canEditMember($editor = null, $target = null) {
            if(!empty($editor) && !empty($target)) {
                
                //ai cung co the sua profile cua ban than
                if($editor == $target) return true;
                
                //admin co the sua profile cua tat ca moi nguoi
                if($this->isAdmin($editor)) return true;
                
                ////exco welfare co the sua profile cua moi nguoi tru admin
//                if($this->isEXCOWelfare($editor) && (!$this->isPresident($target) || !$this->isAdmin($target))) return true;
//                
//                //exco head co the sua profile cua exco assoc cung team
//                if($this->isSameTeamEXCO($editor, $target) && $this->isEXCOHead($editor) && $this->isEXCOAssoc($target)) return true;
                
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
        
        public function canAddProject($id) {
            if($this->isPresident($id) || $this->isAdmin($id)) return true;
            return false;
        }
        
        public function canAddMember($id) {
            if($this->isEXCOWelfare($id) || $this->isPresident($id) || $this->isAdmin($id)) return true;
            return false;
        }
        
        public function addMember($array = null) {
            if(!empty($array) & is_array($array)) {
                
                $params = array(
                    "name" => $this->db->escape($array['name']),
                    "gender" => $this->db->escape($array['gender']),
                    "day" => $array['day'],
                    "month" => $array['month'],
                    "year" => $array['year'],
                    "personal_email" => $this->db->escape($array['personal_email']),
                    "phone" => $this->db->escape($array['phone']),
                    "facebook" => $this->db->escape($array['facebook']),
                    "skype" => $this->db->escape($array['skype']),
                    "high_school" => $this->db->escape($array['high_school']),
                    "grad_year_h" => $this->db->escape($array['grad_year_h']),
                    "uni" => $this->db->escape($array['uni']),
                    "grad_year_u" => $this->db->escape($array['grad_year_u']),
                    "password" => strtolower(str_replace(' ', '', $this->db->escape($array['name'])))
                );
                $this->db->prepareInsert($params); 
                $out = $this->db->insert($this->_members);
                $id = $this->db->_id; 
                
                if(!empty($array['high_school'])) {
                    $sql_h = "SELECT 1 FROM `{$this->_high_schools}` WHERE `name` = '".$this->db->escape($array['high_school'])."'"; 
                    $result_h = $this->db->fetchOne($sql_h);
                    if(empty($result_h)) {
                        $params_high_school = array('name' => $this->db->escape($array['high_school']));
                        $this->db->prepareInsert($params_high_school);
                        $this->db->insert($this->_high_schools);
                    }
                }
                
                if(!empty($array['uni'])) {
                    $sql_u = "SELECT 1 FROM `{$this->_universities}` WHERE `name` = '".$this->db->escape($array['uni'])."'"; 
                    $result_u = $this->db->fetchOne($sql_u);
                    if(empty($result_u)) {
                        $params_university = array('name' => $this->db->escape($array['uni']));
                        $this->db->prepareInsert($params_university);
                        $this->db->insert($this->_universities);
                    }
                }
                
                
                for($i=0;$i<count($array['project']);$i++) {
                    $params_2 = array(
                        "person_id" => $id,
                        "project_type_id" => $array['project'][$i],
                        "year_start" => $array['start'][$i],
                        "team_id" => $array['team'][$i],
                        "position_id" => $array['position'][$i]
                    );
                    $this->db->prepareInsert($params_2); 
                    $this->db->insert($this->_involvements);
                }
                return array('result' => true, 'id' => $id);
            }
            return array('result' => false, 'id' => null);
        }
        
        public function updateMember($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->db->prepareUpdate($params);
                
                if(isset($params['high_school'])) {
                    $sql_h = "SELECT 1 FROM `{$this->_high_schools}` WHERE `name` = '".$this->db->escape($params['high_school'])."'"; 
                    $result_h = $this->db->fetchOne($sql_h);
                    if(empty($result_h)) {
                        $params_high_school = array('name' => $this->db->escape($params['high_school']));
                        $this->db->prepareInsert($params_high_school);
                        $this->db->insert($this->_high_schools);
                    }
                }
                
                if(isset($params['uni'])) {
                    $sql_u = "SELECT 1 FROM `{$this->_universities}` WHERE `name` = '".$this->db->escape($params['uni'])."'"; 
                    $result_u = $this->db->fetchOne($sql_u);
                    if(empty($result_u)) {
                        $params_university = array('name' => $this->db->escape($params['uni']));
                        $this->db->prepareInsert($params_university);
                        $this->db->insert($this->_universities);
                    }
                }
                return $this->db->update($this->_members, $id);
            }
            return false;
        }
        
        public function removeMember($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_members}` WHERE `id` = '".$this->db->escape($id)."'";
                $this->db->query($sql);
                $sql2 = "DELETE FROM `{$this->_involvements}` WHERE `person_id` = '".$this->db->escape($id)."'";
                $this->db->query($sql2);
                return true;
            }
            return false;
        }
        
        public function updateInvolvement($id = null, $array = null) {
            if(!empty($id) && !empty($array) && is_array($array)) {
                $this->db->prepareUpdate($array); 
                return $this->db->update($this->_involvements, $id);
            }
        }
        
        public function removeInvolvement($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_involvements}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->query($sql);
            }
            return false;
        }
        
        public function isDuplicateEmail($email = null, $id = null) {
            if(!empty($email)) {
                if($email != 'N/A') {
                    $sql = "SELECT * FROM `{$this->_members}` WHERE `personal_email` = '".$this->db->escape($email)."'";
                    if(!empty($id)) {
                        $sql .= "AND `id` != '".$this->db->escape($id)."'";
                    }
                    $result = $this->db->fetchAll($sql);
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
                                WHERE `i`.`project_id` = '".$this->db->escape($projectId)."'
                                AND `i`.`person_id` = `m`.`id`) AS `in_project`
                            FROM `{$this->_members}` `m`
                            WHERE `name` LIKE '%".$this->db->escape($name)."%' ORDER BY `in_project` ASC LIMIT 4 ";
                    return $this->db->fetchAll($sql);
                } else {
                    $sql = "SELECT `name`, `id`, `personal_email`
                            FROM `{$this->_members}` `m`
                            WHERE `name` LIKE '%".$this->db->escape($name)."%' LIMIT 4 ";
                    return $this->db->fetchAll($sql);
                }
                
            } else {
                return null;
            }
        }
        
        public function addMemberToProject($array = null) {
            if(!empty($array) & is_array($array)) {
                $params = array(
                    "person_id" => $this->db->escape($array['person_id']),
                    "project_type_id" => $this->db->escape($array['project_type_id']),
                    "project_id" => $this->db->escape($array['project_id']),
                    "position_id" => $this->db->escape($array['position_id']),
                    "team_id" => $this->db->escape($array['team_id']),
                    "position_id" => $this->db->escape($array['position_id']),
                    "month_start" => $this->db->escape($array['month_start']),
                    "year_start" => $this->db->escape($array['year_start']),
                    "month_end" => $this->db->escape($array['month_end']),
                    "year_end" => $this->db->escape($array['year_end'])
                );
                $this->db->prepareInsert($params); 
                return $this->db->insert($this->_involvements);
            }
            return false;
        }
        
        public function checkNameExists($name = null) {
            if(!empty($name)) {
                $sql = "SELECT COUNT(1) as `result` FROM `{$this->_members}` WHERE `name` = '".$this->db->escape($name)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
    }
?>