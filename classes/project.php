<?php
    class Project extends Application {
        
        private $_project_types = 'project_types';
        private $_projects = 'projects';
        private $_involvements = 'involvements';
        private $_members = 'members';
        private $_teams = 'teams';
        private $_positions = 'positions';
        private $_waves = 'waves';
        public $_total = '';
        public $_total_volunteer = '';
        
        public function addProject($params = null) {
            if(!empty($params) & is_array($params)) {
                $this->db->prepareInsert($params); 
                $out = $this->db->insert($this->_projects);
                $id = $this->db->_id;
                if(!empty($id)) {
                    return array('result' => true, 'id' => $id);
                }
                return array('result' => false, 'id' => null);
            }                
            return array('result' => false, 'id' => null);        
        }
        
        public function getProjectById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT p.*, 
                            CONCAT (
                                IF(p.`same_start_end` = 'yes', 
                                    p.`year_end`, 
                                    IF(p.`write_two_years` = 'yes', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(t.`wave` = 'yes',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project_time`, 
                            t.`name`
                        FROM `{$this->_projects}` p 
                        JOIN `{$this->_project_types}` t ON p.`project_type_id` = t.`id`
                        LEFT JOIN `{$this->_waves}` w ON p.`wave_id` = w.`id`
                        WHERE p.`id` = '".$this->db->escape($id)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getProjectTypeById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
        //public function getProjectInfo($id = null, $year_end = null) {
//            if(!empty($id) && (!empty($year))) {
//                $sql = "SELECT * FROM `{$this->_projects}` WHERE `id` = {$id} && `year_end` = {$year_end}";
//                return $this->db->fetchOne($sql);
//            }
//        }
        
        public function getAllProjects() {
            $sql = "SELECT * FROM `{$this->_project_types}` ORDER BY `name`";
            return $this->db->fetchAll($sql);
        }
        
        public function getAllProjectsForList() {
            $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` = 5 ORDER BY `name`";
            //EXCO goes first
            $result = $this->db->fetchAll($sql);
            $sql2 = "SELECT * FROM `{$this->_project_types}` WHERE `id` NOT IN(12, 5) ORDER BY `name`";
            //then the rest
            $temp = $this->db->fetchAll($sql2);
            foreach($temp as $t) {
                $result[] = $t;    
            }
            return $result;
        }
        
        public function getProjectsForSearch() {
            $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` NOT IN(11, 12) ORDER BY `name`";
            return $this->db->fetchAll($sql);
        }
        
        public function getProjectsNoEXCO() {
            $sql = "SELECT * FROM `{$this->_project_types}` WHERE `id` NOT IN(11, 12, 5) ORDER BY `name`";
            return $this->db->fetchAll($sql);
        }
        
        public function getMemberListNoTeam($id = null, $year = null) {
            if(!empty($id) && !empty($year)) {
                $sql = "SELECT m.`id` AS `member_id`, m.`name` AS `member_name`,
                        po.`name` AS `position`, po.`id` AS `position_id`, f.`name` AS `team` 
                        FROM `{$this->_involvements}` i 
                        JOIN `{$this->_members}` m ON m.`id` = i.`person_id`
                        JOIN `{$this->_teams}` f ON f.`id` = i.`team_id`
                        JOIN `{$this->_positions}` po ON po.`id` = i.`position_id`
                    WHERE i.`project_type_id` = '".$this->db->escape($id)."' AND i.`year` = '".$this->db->escape($year)."'";
                return $this->db->fetchAll($sql);   
            }
        }
        
        public function getMemberListWithTeam($id = null, $project_type_id = null, $volunteer = false) {
            if(!empty($id) && !empty($project_type_id)) {
                $result = array();
                $this->_total = 0;
                if($project_type_id == 5) {
                    
                    $sql = "SELECT `id`, `name` FROM `{$this->_teams}` WHERE `exco` = 'yes' ORDER BY `exco_order` ASC";
                    $teams = $this->db->fetchAll($sql);
                    foreach($teams as $team) {
                        $result[$team['id']] = array('id' => $team['id'] ,'name' => $team['name']);
                        $sql = "SELECT 
                        m.`id` AS `member_id`, m.`name` AS `member_name`, m.`personal_email`, m.`gender`, m.`day`, m.`month`, m.`year`,
                        m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`,
                        m.`phone`, m.`skype`, m.`facebook`,
                        i.`month_start`, i.`year_start`, i.`month_end`, i.`year_end`, i.`id` AS `involvement_id`,
                        CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `involvement_time`,
                        IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                            '(Withdrawn)',
                            IF(i.`position_id` = 7, 
                                IF(i2.`person_id` is null, 
                                    LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                    RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                                ,po.`name`) 
                            )
                            AS `position`, 
                        IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                            1, 0)
                            AS `withdrawn`, 
                        
                        po.`id` AS `position_id`, f.`name` AS `team` 
                            FROM `{$this->_involvements}` i 
                            INNER JOIN `{$this->_members}` m ON m.`id` = i.`person_id`
                            INNER JOIN `{$this->_teams}` f ON f.`id` = i.`team_id`
                            INNER JOIN `{$this->_positions}` po ON po.`id` = i.`position_id`
                            INNER JOIN `{$this->_projects}` t ON i.`project_id` = t.`id`
                            LEFT JOIN `{$this->_involvements}` i2 
                                ON i2.`project_id` = i.`project_id`
                                AND i2.`team_id` = i.`team_id`
                                AND i2.`position_id` = i.`position_id`
                                AND i2.`position_id` = 7
                                AND i2.`person_id` <> i.`person_id`
                        WHERE i.`project_id` = '".$this->db->escape($id)."' 
                        AND i.`team_id` = {$team['id']}
                        ORDER BY `withdrawn` ASC, po.`exco_order` ASC";
                        $fetch = $this->db->fetchAll($sql);
                        if(count($fetch) > 0) {
                            $result[$team['id']]['members'] = $fetch;
                            $this->_total += count($result[$team['id']]['members']);
                        }
                    }
                    
                } else {
                    $sql = "SELECT `id`, `name` FROM `{$this->_teams}` WHERE `project` = 'yes' ORDER BY `project_order` ASC";
                    $teams = $this->db->fetchAll($sql);
                    foreach($teams as $team) {
                        $result[$team['id']] = array('id' => $team['id'], 'name' => $team['name']);
                        $sql = "SELECT 
                        m.`id` AS `member_id`, m.`name` AS `member_name`, m.`personal_email`, m.`gender`, m.`day`, m.`month`, m.`year`,
                        m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`,
                        m.`phone`, m.`skype`, m.`facebook`,
                        i.`month_start`, i.`year_start`, i.`month_end`, i.`year_end`, i.`id` AS `involvement_id`,
                        CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `involvement_time`, 
                        IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                            '(Withdrawn)',
                            IF(`i`.position_id = 4, 
                            IF(i2.`person_id` is null, 
                                LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                            ,po.`name`)
                        ) AS `position`,
                        IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                            1, 0)
                            AS `withdrawn`,
                        po.`id` AS `position_id`, f.`name` AS `team` 
                            FROM `{$this->_involvements}` i 
                            JOIN `{$this->_members}` m ON m.`id` = i.`person_id`
                            JOIN `{$this->_teams}` f ON f.`id` = i.`team_id`
                            JOIN `{$this->_positions}` po ON po.`id` = i.`position_id`
                            INNER JOIN `{$this->_projects}` t ON i.`project_id` = t.`id`
                            LEFT JOIN `{$this->_involvements}` i2 
                                ON i2.`project_id` = i.`project_id`
                                AND i2.`team_id` = i.`team_id`
                                AND i2.`position_id` = i.`position_id`
                                AND i2.`position_id` = 4
                                AND i2.`person_id` <> i.`person_id`
                        WHERE i.`project_id` = '".$this->db->escape($id)."' 
                        AND i.`team_id` = {$team['id']} ";
                        if(!$volunteer) {
                            $sql .= " AND i.`position_id` != 1 ";
                        }
                        $sql .= " ORDER BY `withdrawn` ASC, po.`project_order` ASC";
                        $fetch = $this->db->fetchAll($sql);
                        if(count($fetch) > 0) {
                            $result[$team['id']]['members'] = $fetch;
                            $this->_total += count($result[$team['id']]['members']);
                        }
                        
                        
                    }
                }
                return $result;
                
            }
        }
        
        public function getVolunteerListWithTeam($id = null) {
            if(!empty($id)) {
                $result = array();
                $this->_total_volunteer = 0;
                
                    $sql = "SELECT `id`, `name` FROM `{$this->_teams}` WHERE `project` = 'yes' ORDER BY `project_order` ASC";
                    $teams = $this->db->fetchAll($sql);
                    foreach($teams as $team) {
                        $result[$team['id']] = array('id' => $team['id']);
                        switch($team['id']) {
                            case 10:
                            $result[$team['id']]['name'] = 'Leader';
                            break;
                            
                            case 13:
                            $result[$team['id']]['name'] = 'Mentor';
                            break;
                            
                            default:
                            $result[$team['id']]['name'] = $team['name'];
                        }
                        //$result[$team['id']]['name'] = $team['id'] == 10 ? 'Leader' : $team['name'];
//                        $result[$team['id']]['name'] = $team['id'] == 13 ? 'Mentor' : $team['name'];
                        $sql = "SELECT 
                        m.`id` AS `member_id`, m.`name` AS `member_name`, m.`personal_email`, m.`gender`, m.`day`, m.`month`, m.`year`,
                        m.`high_school`, m.`grad_year_h`, m.`uni`, m.`grad_year_u`,
                        m.`phone`, m.`skype`, m.`facebook`,
                        i.`month_start`, i.`year_start`, i.`month_end`, i.`year_end`, i.`id` AS `involvement_id`,
                        CONCAT(i.`month_start`,'/',i.`year_start`,' - ',i.`month_end`,'/',i.`year_end`) AS `involvement_time`, 
                        IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                            '(Withdrawn)',
                            IF(`i`.position_id = 4, 
                            IF(i2.`person_id` is null, 
                                LEFT(po.`name`, LOCATE('/',po.`name`)-2), 
                                RIGHT(po.`name`, LOCATE('/',po.`name`)+1)) 
                            ,po.`name`)
                        ) AS `position`,
                        IF(((i.`year_end` < t.`year_end`) OR ((i.`year_end` = t.`year_end`) AND (i.`month_end` < t.`month_end`))), 
                            1, 0)
                            AS `withdrawn`,
                        po.`id` AS `position_id`, f.`name` AS `team` 
                            FROM `{$this->_involvements}` i 
                            JOIN `{$this->_members}` m ON m.`id` = i.`person_id`
                            JOIN `{$this->_teams}` f ON f.`id` = i.`team_id`
                            JOIN `{$this->_positions}` po ON po.`id` = i.`position_id`
                            INNER JOIN `{$this->_projects}` t ON i.`project_id` = t.`id`
                            LEFT JOIN `{$this->_involvements}` i2 
                                ON i2.`project_id` = i.`project_id`
                                AND i2.`team_id` = i.`team_id`
                                AND i2.`position_id` = i.`position_id`
                                AND i2.`position_id` = 4
                                AND i2.`person_id` <> i.`person_id`
                        WHERE i.`project_id` = '".$this->db->escape($id)."' 
                        AND i.`team_id` = {$team['id']}
                        AND i.`position_id` = 1";
                        $fetch = $this->db->fetchAll($sql);
                        if(count($fetch) > 0) {
                            $result[$team['id']]['members'] = $fetch;
                            $this->_total_volunteer += count($result[$team['id']]['members']);
                        }
                        
                        
                    }
                
                return $result;
                
            }
        }
        
        public function getActivityStatus ($id = null, $project_type_id = null, $month_end = null, $year_end = null) {
            if(!empty($id) && !empty($project_type_id) && !empty($month_end) && !empty($year_end)) {
                $sql = "SELECT `active` FROM `{$this->_projects}` WHERE `id` = {$id}";
                $active_db = $this->db->fetchOne($sql)['active'];
                $month = date("m");
                $year = date("Y");
                
                if($year_end < $year || ($year_end == $year && $month_end < $month)) {
                    $active_real = 'no';
                } else {
                    $active_real = 'yes';
                }
                
                if($project_type_id == 5) {
                    if($active_real == 'no') {
                        $result = "Term ended";
                    } else {
                        $result = "Active";
                    }
                } else {
                    if($active_real == 'no') {
                        $result = "Project ended";
                    } else {
                        $result = "Running";
                    }
                }
                
                if($active_real != $active_db) {
                    $this->updateActive($id, $active_real);
                }
                return $result;
            }
        }
        
        public function updateActive($id = null, $active_real = null) {
            if(!empty($id) && !empty($active_real)) {
                $this->db->prepareUpdate(array('active' => $active_real)); 
                $this->db->update($this->_projects, $id);
                $sql = "UPDATE `{$this->_involvements}` SET `active` = '{$active_real}' WHERE `project_id` = {$id}";
                $this->db->query($sql);
            }
        }
        
        public function getStatusLink ($id = null, $year = null) {
            if(!empty($id) && !empty($year)) {
                $sql = "SELECT `month_end` FROM `{$this->_project_types}` WHERE `id` = ".$id;
                $month = $this->db->fetchOne($sql)['month_end'];
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
                                IF(p.`same_start_end` = 'yes', 
                                    p.`year_end`, 
                                    IF(p.`write_two_years` = 'yes', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(p.`wave_id` <> 0,
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project_time`
                        FROM `{$this->_projects}` p
                        LEFT JOIN `{$this->_waves}` w ON p.`wave_id` = w.`id`
                        WHERE p.`project_type_id` = {$id} ORDER BY `year_end` DESC, `wave_id` ASC";
                return $this->db->fetchAll($sql);
            }            
        }
        
        public function getUnaddedYears($id = null) {
            if(!empty($id)) {
                
                $sql = "SELECT `first_time` FROM `{$this->_project_types}` WHERE `id` = '".$this->db->escape($id)."'";
                    $first_time = $this->db->fetchOne($sql)['first_time'];
                    
                $sql = "SELECT `wave`
                        FROM `{$this->_project_types}` 
                        WHERE `id` = '".$this->db->escape($id)."'";
                $wave = $this->db->fetchOne($sql);
                
                if($wave['wave'] == 'Yes') {
                    $sql = "SELECT * FROM `{$this->_waves}` WHERE `project_type_id` = '".$this->db->escape($id)."' ORDER BY `month_start`";
                    $project_waves = $this->db->fetchAll($sql);

                    $all_waves = array();
                    
                    for($i=$first_time;$i<=date("Y");$i++) {
                        foreach($project_waves as $project_wave) {
                            $all_waves[] = $i.' ('.$project_wave['name'].')&'.$i.'_'.$project_wave['id'];
                        }
                    }
                    
                    $sql = "SELECT 
                            CONCAT (p.`year_start` , ' (', w.`name` , ')', '&', p.`year_start`, '_', w.`id`) AS `project_time`
                        FROM `{$this->_projects}` p
                        LEFT JOIN `{$this->_waves}` w ON p.`wave_id` = w.`id`
                        WHERE p.`project_type_id` = '".$this->db->escape($id)."' ORDER BY `year_end` DESC, `wave_id` ASC";
                    
                    $results = $this->db->fetchAll($sql);
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
                    $sql = "SELECT `year_start` FROM `{$this->_projects}` WHERE `project_type_id` = '".$this->db->escape($id)."' ORDER BY `year_start` ASC";
                    $added_years = $this->db->fetchAll($sql);
                    
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
                $this->db->prepareUpdate($params); 
                return $this->db->update($this->_project_types, $id);
            }
        }
        
        public function checkProjectTypeInInvolvements($project_type_id = null) {
            if(!empty($project_type_id)) {
                $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `project_type_id` = ".$this->db->escape($project_type_id);
                $result = $this->db->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function removeProjectType($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_project_types}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->query($sql);
            }
            return false;
        }
        
        public function addProjectType($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->db->prepareInsert($params); 
                return $this->db->insert($this->_project_types);
            }
        }
        
        public function getAllWaves() {
            $sql = "SELECT w.*, pt.`name` AS `type_name`, w.`name` AS `wave_name` FROM `{$this->_waves}` w JOIN `{$this->_project_types}` pt ON pt.`id` = w.`project_type_id` ORDER BY pt.`id`, w.`month_start` ASC";
            return $this->db->fetchAll($sql);
        }
        
        
    }
?>