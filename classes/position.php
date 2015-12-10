<?php
    class Position extends Application {
        
        private $_project_types = 'project_types';
        private $_projects = 'projects';
        private $_involvements = 'involvements';
        private $_members = 'members';
        private $_teams = 'teams';
        private $_positions = 'positions';
        private $_position_team = 'position_team';
        private $_waves = 'waves';
                
        public function getPositionById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_positions}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getPositionTeam($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "SELECT * FROM `{$this->_position_team}` WHERE `position_id` = '".$this->db->escape($position_id)."' AND `team_id` = ".$this->db->escape($team_id);
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getAllPositions($cofounder = false) {
            $sql = "SELECT * FROM `{$this->_positions}` ";
            if(!$cofounder) {
                $sql .= "WHERE `id` !=9 ";
            }
            $sql .= "ORDER BY `name` ASC";
            return $this->db->fetchAll($sql);
        }
        
        public function getAllPositionsInProject($project_type_id) {
            if(!empty($project_type_id)) {
                //if($project_type_id == 5) {
//                    $sql = "SELECT DISTINCT p.*
//                            FROM `positions` p 
//                            INNER JOIN (`position_team` pt, `teams` t1)  
//                                ON (p.`id` = pt.`position_id` AND t1.`id` = pt.`team_id` AND t1.`exco` = 'Yes')
//                            LEFT JOIN (`position_team` pt2 
//                                        INNER JOIN `teams` t2 
//                                            ON t2.`id` = pt2.`team_id` AND t2.`project` = 'Yes' AND t2.`exco` = 'No') 
//                                ON (p.`id` = pt2.`position_id`)
//                            WHERE t1.`exco` = 'Yes' AND t2.`id` IS NULL";
//                } else {
//                    $sql = "SELECT DISTINCT p.*
//                            FROM `positions` p 
//                            INNER JOIN (`position_team` pt, `teams` t1)  
//                                ON (p.`id` = pt.`position_id` AND t1.`id` = pt.`team_id` AND t1.`project` = 'Yes')
//                            LEFT JOIN (`position_team` pt2 
//                                        INNER JOIN `teams` t2 
//                                            ON t2.`id` = pt2.`team_id` AND t2.`exco` = 'Yes' AND t2.`project` = 'No') 
//                                ON (p.`id` = pt2.`position_id`)
//                            WHERE t1.`project` = 'Yes' AND t2.`id` IS NULL";
//                }
                if($project_type_id == 5) {
                    $sql = "SELECT * FROM `{$this->_positions}` WHERE `exco` = 'Yes' ORDER BY `exco_order` ASC";
                } else {
                    $sql = "SELECT * FROM `{$this->_positions}` WHERE `project` = 'Yes' ORDER BY `project_order` ASC";
                }
                return $this->db->fetchAll($sql);
            }
        }
        
        public function checkPositionExistsInInvolvements($id = null, $exco = false) {
            if(!empty($id)) {
                if($exco) {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `position_id` = ".$this->db->escape($id)." AND `project_type_id` = 5 LIMIT 1";
                } else {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `position_id` = ".$this->db->escape($id)." AND `project_type_id` != 5 LIMIT 1";
                }
                $result = $this->db->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function checkPositionTeamExistsInInvolvements($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `position_id` = ".$this->db->escape($position_id)." AND `team_id` = ".$this->db->escape($team_id)." LIMIT 1";
                $result = $this->db->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function checkPositionInTeam($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "SELECT 1 FROM `{$this->_position_team}` WHERE `position_id` = ".$this->db->escape($position_id)." AND `team_id` = ".$this->db->escape($team_id);   
                $result = $this->db->fetchOne($sql);
                if(!empty($result)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        public function addPosition($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->db->prepareInsert($params); 
                return $this->db->insert($this->_positions);
            }
        }
        
        public function addPositionTeam($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->db->prepareInsert($params); 
                return $this->db->insert($this->_position_team);
            }
        }
        
        public function removePosition($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_positions}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->query($sql);
            }
            return false;
        }
        
        public function removePositionTeam($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "DELETE FROM `{$this->_position_team}` WHERE `position_id` = '".$this->db->escape($position_id)."' AND `team_id` = ".$this->db->escape($team_id);
                return $this->db->query($sql);
            }
            return false;
        }
        
        public function updatePosition($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->db->prepareUpdate($params); 
                return $this->db->update($this->_positions, $id);
            }
        }
        
        

    }
?>