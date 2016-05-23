<?php
    class Position extends Dbase {
                
        public function getPositionById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_positions}` WHERE `id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
        }
        
        public function getPositionTeam($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "SELECT * FROM `{$this->_position_team}` WHERE `position_id` = '".$this->escape($position_id)."' AND `team_id` = ".$this->escape($team_id);
                return $this->fetchOne($sql);
            }
        }
        
        public function getAllPositions($cofounder = false) {
            $sql = "SELECT * FROM `{$this->_positions}` ";
            if(!$cofounder) {
                $sql .= "WHERE `id` !=9 ";
            }
            $sql .= "ORDER BY `name` ASC";
            return $this->fetchAll($sql);
        }
        
        public function getAllPositionsInProject($project_type_id) {
            if(!empty($project_type_id)) {
                if($project_type_id == 5) {
                    $sql = "SELECT * FROM `{$this->_positions}` WHERE `exco` = '1' ORDER BY `exco_order` ASC";
                } else {
                    $sql = "SELECT * FROM `{$this->_positions}` WHERE `project` = '1' ORDER BY `project_order` ASC";
                }
                return $this->fetchAll($sql);
            }
        }
        
        public function checkPositionExistsInInvolvements($id = null, $exco = false) {
            if(!empty($id)) {
                if($exco) {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `position_id` = ".$this->escape($id)." AND `project_type_id` = 5 LIMIT 1";
                } else {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `position_id` = ".$this->escape($id)." AND `project_type_id` != 5 LIMIT 1";
                }
                $result = $this->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function checkPositionTeamExistsInInvolvements($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `position_id` = ".$this->escape($position_id)." AND `team_id` = ".$this->escape($team_id)." LIMIT 1";
                $result = $this->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function checkPositionInTeam($position_id = null, $team_id = null) {
            if(!empty($position_id) && !empty($team_id)) {
                $sql = "SELECT 1 FROM `{$this->_position_team}` WHERE `position_id` = ".$this->escape($position_id)." AND `team_id` = ".$this->escape($team_id);   
                $result = $this->fetchOne($sql);
                if(!empty($result)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
                
        public function addPositionTeam($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->prepareInsert($params); 
                return $this->insert($this->_position_team);
            }
        }
        
        public function updatePosition($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params); 
                return $this->update($this->_positions, $id);
            }
        }
        
        public function getLastPosition($exco = false) {
            if($exco) {
                $sql = "SELECT MAX(`exco_order`) AS `last` FROM `{$this->_positions}`";
            } else {
                $sql = "SELECT MAX(`project_order`) AS `last` FROM `{$this->_positions}`";
            }
            return $this->fetchOne($sql)['last'];
        }
        
        

    }
?>