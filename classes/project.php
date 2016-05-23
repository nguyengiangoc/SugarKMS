<?php
    class Project extends Dbase {
        
        public $_total = '';
        public $_total_volunteer = '';
        
        public function addProject($params = null) {
            if(!empty($params) & is_array($params)) {
                $this->prepareInsert($params); 
                $out = $this->insert($this->_projects);
                $id = $this->_id;
                if(!empty($id)) {
                    return array('result' => true, 'id' => $id);
                }
                return array('result' => false, 'id' => null);
            }                
            return array('result' => false, 'id' => null);        
        }
        
        public function getProjectById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT p.*, t.`same_start_end`,
                            CONCAT (
                                IF(t.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(t.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(t.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project_time`, 
                            t.`name`
                        FROM `{$this->_projects}` p 
                        JOIN `{$this->_project_types}` t ON p.`project_type_id` = t.`id`
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                        WHERE p.`id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
        }
        
        public function getProjectTypeById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
        }
        
        //public function getProjectInfo($id = null, $year_end = null) {
//            if(!empty($id) && (!empty($year))) {
//                $sql = "SELECT * FROM `{$this->_projects}` WHERE `id` = {$id} && `year_end` = {$year_end}";
//                return $this->fetchOne($sql);
//            }
//        }
        
        public function getAllProjectTypes() {
            $sql = "SELECT * FROM `{$this->_project_types}` ORDER BY `name`";
            return $this->fetchAll($sql);
        }
        
        public function getAllProjectsForList() {
            $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` = 5 ORDER BY `name`";
            //EXCO goes first
            $result = $this->fetchAll($sql);
            $sql2 = "SELECT * FROM `{$this->_project_types}` WHERE `id` != 5 ORDER BY `name`";
            //then the rest
            $temp = $this->fetchAll($sql2);
            foreach($temp as $t) {
                $result[] = $t;    
            }
            return $result;
        }
        
        public function getProjectsNoEXCO() {
            $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` NOT IN(11, 12, 5) ORDER BY `name`";
            return $this->fetchAll($sql);
        }
        
        public function getMemberListNoTeam($id = null, $year = null) {
            if(!empty($id) && !empty($year)) {
                $sql = "SELECT m.`id` AS `member_id`, m.`name` AS `member_name`,
                        po.`name` AS `position`, po.`id` AS `position_id`, f.`name` AS `team` 
                        FROM `{$this->_involvements}` i 
                        JOIN `{$this->_members}` m ON m.`id` = i.`member_id`
                        JOIN `{$this->_teams}` f ON f.`id` = i.`team_id`
                        JOIN `{$this->_positions}` po ON po.`id` = i.`position_id`
                    WHERE i.`project_type_id` = '".$this->escape($id)."' AND i.`year` = '".$this->escape($year)."'";
                return $this->fetchAll($sql);   
            }
        }
        
        public function getMemberListWithTeam($id = null, $project_type_id = null, $volunteer = false) {
            if(!empty($id) && !empty($project_type_id)) {
                $result = array();
                
                
                $sql = "SELECT `id`, `name` FROM `{$this->_teams}` WHERE ";
                if($project_type_id == 5) { 
                    $sql .= " `exco` = '1' ORDER BY `exco_order` ASC";
                    $this->_total = 0;
                } else { 
                    $sql .= " `project` = '1' ORDER BY `project_order` ASC";
                    if($volunteer) {
                        $this->_total_volunteer = 0;
                    } else {
                        $this->_total = 0;
                    }
                }
                $teams = $this->fetchAll($sql);
                
                foreach($teams as $key => $team) {
                    
                    $result[$key] = array('id' => $team['id'], 'name' => $team['name']);
                    
                    $sql = "SELECT 
                            m.`id` AS `member_id`, m.`name` AS `member_name`, m.`personal_email`, m.`gender`, m.`day`, m.`month`, m.`year`,
                            h.`name` AS `high_school`, u.`name` AS `uni`,
                            m.`grad_year_h`, m.`grad_year_u`,
                            m.`phone`, m.`skype`, m.`facebook`,
                            i.`month_start`, i.`year_start`, i.`month_end`, i.`year_end`, i.`id` AS `involvement_id`, i.in_charge,
                            CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `involvement_time`, ";
                            
                    if($project_type_id == 5) { 
                        $sql .= "
                            IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                                '(Withdrawn)',
                                IF(i.`position_id` = 7, 
                                    IF(i2.`member_id` is null, 
                                        LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                        RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                                    ,po.`name`) 
                                )
                                AS `position`, ";
                    } else {
                        $sql .= " 
                            IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                                '(Withdrawn)',
                                IF(`i`.position_id = 4, 
                                IF(i2.`member_id` is null, 
                                    LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                    RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                                ,po.`name`)
                            ) AS `position`, ";
                    }
                    
                    $sql .= "
                            IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                                1, 0)
                                AS `withdrawn`, 
                            po.`id` AS `position_id`, f.`name` AS `team` 
                                FROM `{$this->_involvements}` i 
                                INNER JOIN `{$this->_members}` m ON m.`id` = i.`member_id`
                                INNER JOIN `{$this->_teams}` f ON f.`id` = i.`team_id`
                                INNER JOIN `{$this->_positions}` po ON po.`id` = i.`position_id`
                                INNER JOIN `{$this->_projects}` t ON i.`project_id` = t.`id`
                                LEFT JOIN `{$this->_high_schools}` h ON m.`high_school` = h.`id`
                                LEFT JOIN `{$this->_universities}` u ON m.`uni` = u.`id`";
                                
                    if($project_type_id == 5) {
                        $sql .= "
                                LEFT JOIN `{$this->_involvements}` i2 
                                    ON i2.`project_id` = i.`project_id`
                                    AND i2.`team_id` = i.`team_id`
                                    AND i2.`position_id` = i.`position_id`
                                    AND i2.`position_id` = 7
                                    AND i2.`member_id` <> i.`member_id`
                            WHERE i.`project_id` = '".$this->escape($id)."' 
                            AND i.`team_id` = {$team['id']}
                            ORDER BY `withdrawn` ASC, po.`exco_order` ASC, i.`order` ASC";
                        
                        
                    } else {
                         
                        $sql .= "
                                LEFT JOIN `{$this->_involvements}` i2 
                                    ON i2.`project_id` = i.`project_id`
                                    AND i2.`team_id` = i.`team_id`
                                    AND i2.`position_id` = i.`position_id`
                                    AND i2.`position_id` = 4
                                    AND i2.`member_id` <> i.`member_id`
                            WHERE i.`project_id` = '".$this->escape($id)."' 
                            AND i.`team_id` = {$team['id']} ";
                            if($volunteer) {
                                $sql .= " AND i.`position_id` = 1 ";
                            } else {
                                $sql .= " AND i.`position_id` != 1 ";
                            }
                            $sql .= " ORDER BY `withdrawn` ASC, po.`project_order` ASC, i.`order` ASC";
                            
                    }
                    $fetch = $this->fetchAll($sql);
                    if(count($fetch) > 0) {
                        $result[$key]['members'] = $fetch;
                        if($volunteer) {
                            $this->_total_volunteer += count($fetch);
                        } else {
                            $this->_total += count($fetch);
                        }
                        
                    }
                    
                }
                return $result;
                
            }
        }
                
        public function getStatusLink ($id = null, $year = null) {
            if(!empty($id) && !empty($year)) {
                $sql = "SELECT `month_end` FROM `{$this->_project_types}` WHERE `id` = ".$id;
                $month = $this->fetchOne($sql)['month_end'];
                $active = "SELECT `active` FROM `{$this->_projects}` WHERE `id` = ".$id;
                if($year < date("Y") || ($year == date("Y") && $month < date("m"))) {
                    $result = '';
                } else {
                    
                }
                return $result;
            }
        }
        
        public function getAnnualProjects($id = null) {
            if(!empty($id)) {
                $sql = "SELECT p.`id`, p.`year_start`, p.`year_end`, p.`wave_id`,
                            CONCAT (
                                IF(t.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(t.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(p.`wave_id` <> 0,
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project_time`
                        FROM `{$this->_projects}` p
                        JOIN `{$this->_project_types}` t ON p.`project_type_id` = t.`id`
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                        WHERE p.`project_type_id` = {$id} ORDER BY `year_end` DESC, `wave_id` ASC";
                return $this->fetchAll($sql);
            }            
        }
        
        public function getUpcomingProjects() {
            $sql = "SELECT p.`id`, p.`project_type_id`, t.`name`,
                        CONCAT (
                            IF(t.`same_start_end` = '1', 
                                p.`year_end`, 
                                IF(t.`write_two_years` = '1', 
                                    CONCAT(p.`year_start`,' - ',p.`year_end`),
                                    p.`year_start`
                                )
                            ),
                            IF(p.`wave_id` <> 0,
                                CONCAT(' (',w.`name`,')'), '')
                        ) AS `project_time`
                    FROM `{$this->_projects}` p
                    JOIN `{$this->_project_types}` t ON p.`project_type_id` = t.`id`
                    LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                    WHERE (p.`year_start` > YEAR(CURDATE())) 
							OR (p.`year_start` = YEAR(CURDATE()) AND  p.`month_start` > MONTH(CURDATE()))
                    ORDER BY p.`year_start` ASC, p.`month_start` ASC";
            return $this->fetchAll($sql);
        }
        
        public function getUnaddedYears($id = null) {
            if(!empty($id)) {
                
                $sql = "SELECT `first_time` FROM `{$this->_project_types}` WHERE `id` = '".$this->escape($id)."'";
                    $first_time = $this->fetchOne($sql)['first_time'];
                    
                $sql = "SELECT `wave`
                        FROM `{$this->_project_types}` 
                        WHERE `id` = '".$this->escape($id)."'";
                $wave = $this->fetchOne($sql);
                
                if($wave['wave'] == '1') {
                    $sql = "SELECT * FROM `{$this->_project_waves}` WHERE `project_type_id` = '".$this->escape($id)."' ORDER BY `month_start`";
                    $project_waves = $this->fetchAll($sql);

                    $all_waves = array();
                    
                    for($i=$first_time;$i<=date("Y");$i++) {
                        foreach($project_waves as $project_wave) {
                            $all_waves[] = $i.' ('.$project_wave['name'].')&'.$i.'_'.$project_wave['id'];
                        }
                    }
                    
                    $sql = "SELECT 
                            CONCAT (p.`year_start` , ' (', w.`name` , ')', '&', p.`year_start`, '_', w.`id`) AS `project_time`
                        FROM `{$this->_projects}` p
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                        WHERE p.`project_type_id` = '".$this->escape($id)."' ORDER BY `year_end` DESC, `wave_id` ASC";
                    
                    $results = $this->fetchAll($sql);
                    $added_waves = array();
                    
                    foreach($results as $result) {
                        $added_waves[] = $result['project_time'];
                        //$key = array_search($added_wave, $added_waves);
//                        unset($added_waves[$key]);
                    }
                    
                    $unadded_waves = array_diff($all_waves, $added_waves);
                    
                    foreach($unadded_waves as $result) {
                        $split = explode('&', $result);
                        $value = $split[1];
                        $label = $split[0];
                        $unadd_waves[] = array("value" => $value, "label" => $label);
                        $key = array_search($result, $unadded_waves);
                        unset($unadded_waves[$key]);
                        //$key = array_search($added_wave, $added_waves);
//                        unset($added_waves[$key]);
                    }
                    
                    return $unadd_waves;
                    
                    
                } else {
                    $sql = "SELECT `year_start` FROM `{$this->_projects}` WHERE `project_type_id` = '".$this->escape($id)."' ORDER BY `year_start` ASC";
                    $added_years = $this->fetchAll($sql);
                    
                    $all_years = array();
                    
                    for($i=intval($first_time);$i<=date("Y");$i++) {
                        $all_years[] = $i;
                    }
                    
                    foreach($added_years as $added_year) {
                        $added_years[] = $added_year['year_start'];
                        $key = array_search($added_year, $added_years);
                        unset($added_years[$key]);
                    }
                    
                    $unadded_years = array_diff($all_years, $added_years);
                    
                    foreach($unadded_years as $unadded) {
                        $unadded_years[] = array("value" => $unadded, "label" => $unadded);
                        $key = array_search($unadded, $unadded_years);
                        unset($unadded_years[$key]);
                    }
                    
                    return $unadded_years;

                }
                
            }
        }
        
        public function updateProjectType($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params); 
                return $this->update($this->_project_types, $id);
            }
        }
        
        public function checkProjectTypeInInvolvements($project_type_id = null) {
            if(!empty($project_type_id)) {
                $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `project_type_id` = ".$this->escape($project_type_id);
                $result = $this->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
                
        public function getAllWaves() {
            $sql = "SELECT w.*, pt.`name` AS `type_name`, w.`name` AS `wave_name` FROM `{$this->_project_waves}` w JOIN `{$this->_project_types}` pt ON pt.`id` = w.`project_type_id` ORDER BY pt.`id`, w.`month_start` ASC";
            return $this->fetchAll($sql);
        }
        
        public function getWaves($params = null, $order = null) {
            $sql = "SELECT w.*, pt.`name` AS `type_name`, w.`name` AS `wave_name` FROM `{$this->_project_waves}` w JOIN `{$this->_project_types}` pt ON pt.`id` = w.`project_type_id` ";
            if(!empty($params) && is_array($params)) {
                $sql .= " WHERE ";
                $where = array();
                foreach($params as $key => $value) {
                    $where[] = "w.`".$this->escape($key)."` = '".$this->escape($value)."'";
                    
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
        
        
        
    }
?>