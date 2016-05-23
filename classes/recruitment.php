<?php
    class Recruitment extends Dbase {
        
        public function getCurrentRecruitments() {
            $sql = "SELECT r.*, t.`name` AS `team`, po.`name` AS `position`, 
                        CONCAT (pt.`name`,' ',
                                IF(pt.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(pt.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(pt.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project`,
                        CONCAT (IF(pt.`abbr` = '', pt.`name`, pt.`abbr`),' ',
                                IF(pt.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(pt.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(pt.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `abbr` 
                    FROM `{$this->_recruitments}` r
                        INNER JOIN `{$this->_teams}` t ON t.`id` = r.`team_id`
                        INNER JOIN `{$this->_positions}` po ON po.`id` = r.`position_id`
                        INNER JOIN `{$this->_projects}` p ON p.`id` = r.`project_id`
                        INNER JOIN `{$this->_project_types}` pt ON p.`project_type_id` = pt.`id`
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                    WHERE `deadline` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) 
                    ORDER BY `project` ASC, `deadline` ASC,  IF(p.`project_type_id` = 5, po.`exco_order`, po.`project_order`) ASC, IF(p.`project_type_id` = 5, t.`exco_order`, t.`project_order`) ASC";
            return $this->fetchAll($sql);
            
        }
        
        public function getRecruitmentById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT r.*, t.`name` AS `team`, po.`name` AS `position`, 
                        CONCAT (pt.`name`,' ',
                                IF(pt.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(pt.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(pt.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project`,
                        CONCAT (IF(pt.`abbr` = '', pt.`name`, pt.`abbr`),' ',
                                IF(pt.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(pt.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(pt.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `abbr` 
                    FROM `{$this->_recruitments}` r
                        INNER JOIN `{$this->_teams}` t ON t.`id` = r.`team_id`
                        INNER JOIN `{$this->_positions}` po ON po.`id` = r.`position_id`
                        INNER JOIN `{$this->_projects}` p ON p.`id` = r.`project_id`
                        INNER JOIN `{$this->_project_types}` pt ON p.`project_type_id` = pt.`id`
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                    WHERE r.`id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
            
        }
        
        public function getQuestionsForRecruitment($id = null) {
            if(!empty($id)) {
                $sql = "SELECT q.*               
                        FROM `{$this->_questions}` q 
                        WHERE q.`recruitment_id` = '".$this->escape($id)."'
                        ORDER BY `order`";
                return $this->fetchAll($sql);
            }
        }
        
        public function getQuestionChoices($id = null) {
            if(!empty($id)) {
                $sql = "SELECT *               
                        FROM `{$this->_choices}`
                        WHERE `question_id` = '".$this->escape($id)."'
                        ORDER BY `order`";
                return $this->fetchAll($sql);
            }
        }
        
        public function getQuestionLastPosition($id = null) {
            if(!empty($id)) {
                $sql = "SELECT MAX(`order`) AS `last` FROM `{$this->_questions}` WHERE `recruitment_id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql)['last'];
            }
        }
        
        public function getChoiceLastPosition($id = null) {
            if(!empty($id)) {
                $sql = "SELECT IF(MAX(`order`) IS NULL, 0,  MAX(`order`)) AS `last` FROM `{$this->_choices}` WHERE `question_id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql)['last'];
            }
        }
        
        public function getApplicationsForCurrentRecruitment() {
            $sql = "SELECT DISTINCT r.`project_id`, 
                        CONCAT (pt.`name`,' ',
                                IF(pt.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(pt.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(pt.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project` 
                    FROM `{$this->_recruitments}` r
                        INNER JOIN `{$this->_projects}` p ON p.`id` = r.`project_id`
                        INNER JOIN `{$this->_project_types}` pt ON p.`project_type_id` = pt.`id`
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                    WHERE r.`deadline` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) 
                    ORDER BY `project` ASC";
            $projects = $this->fetchAll($sql);
            
            $result = array();
            
            foreach($projects as $key1 => $project) {
                                
                $sql = "SELECT DISTINCT r.`team_id`, t.`name` AS `team`
                        FROM `{$this->_recruitments}` r
                            INNER JOIN `{$this->_teams}` t ON t.`id` = r.`team_id`
                        WHERE r.`project_id` = '".$project['project_id']."'";
                $projects[$key1]['teams'] = $this->fetchAll($sql);
                
                foreach($projects[$key1]['teams'] as $key2 => $team) {
                    
                    
                    $sql = "SELECT r.`position_id`, p.`name` AS `position`, r.`id` AS `recruitment_id`
                            FROM `{$this->_recruitments}` r
                                INNER JOIN `{$this->_positions}` p ON p.`id` = r.`position_id`
                            WHERE r.`project_id` = '".$project['project_id']."' AND r.`team_id` = '".$team['team_id']."'";
                    $projects[$key1]['teams'][$key2]['positions'] = $this->fetchAll($sql);
                    
                    foreach($projects[$key1]['teams'][$key2]['positions'] as $key3 => $position) {
                        
                        $sql = "SELECT id
                            FROM `{$this->_application_status}` 
                            ORDER BY `order`";
                        $projects[$key1]['teams'][$key2]['positions'][$key3]['status'] = $this->fetchAll($sql);
                        
                        foreach($projects[$key1]['teams'][$key2]['positions'][$key3]['status'] as $key4 => $status) {
                            $sql = "SELECT COUNT(*) as `count`
                                FROM `{$this->_applications}` 
                                WHERE `recruitment_id` = '".$position['recruitment_id']."' AND `status` = '".$status['id']."'";
                            $projects[$key1]['teams'][$key2]['positions'][$key3]['status'][$key4]['count'] = $this->fetchAll($sql)[0]['count'];
                            
                        }
                        
                    }
                }
                
                //return $projects;

            }
            
            return $projects;
            
        }
        
        public function getRecruitmentByCriteria($criteria) {
            if(!empty($criteria)) {
                $sql = "SELECT r.*, t.`name` AS `team`, po.`name` AS `position`, 
                        CONCAT (pt.`name`,' ',
                                IF(pt.`same_start_end` = '1', 
                                    p.`year_end`, 
                                    IF(pt.`write_two_years` = '1', 
                                        CONCAT(p.`year_start`,' - ',p.`year_end`),
                                        p.`year_start`
                                    )
                                ),
                                IF(pt.`wave` = '1',
                                    CONCAT(' (',w.`name`,')'), '')
                            ) AS `project` 
                    FROM `{$this->_recruitments}` r
                        INNER JOIN `{$this->_teams}` t ON t.`id` = r.`team_id`
                        INNER JOIN `{$this->_positions}` po ON po.`id` = r.`position_id`
                        INNER JOIN `{$this->_projects}` p ON p.`id` = r.`project_id`
                        INNER JOIN `{$this->_project_types}` pt ON p.`project_type_id` = pt.`id`
                        LEFT JOIN `{$this->_project_waves}` w ON p.`wave_id` = w.`id`
                    WHERE ";
            }
            
            
            foreach ($criteria as $key  => $value) {
                switch($key) {
                    
                    case 'position_id': 
                        $criteria[] = "  po.`id` = '".$this->escape($value)."' ";
                    break;
                    
                    case 'team_id':
                        $criteria[] = "t.`id = '".$this->escape($value)."' ";
                    break;
                    
                    case 'project_type_id':
                        $criteria[] = " pt.`id` = '".$this->escape($value)."' ";
                    break; 
                    
                    case 'project_wave':
                        $criteria[] = " w.`id` = '".$this->escape($value)." '";
                    break;
                    
                    case 'project_year':
                        $criteria[] = " (p.`year_start` = ".$this->escape($value)." 
                                        OR p.`year_end` = ".$this->escape($value).")";
                    break;
                    
                }
                                
                unset($criteria[$key]);
            }
            
            $sql .= implode(' AND ', $criteria);
            return $this->fetchAll($sql);
        }
        
    }
?>