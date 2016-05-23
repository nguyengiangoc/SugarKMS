<?php
    class Team extends Dbase {
        
        public function getTeamById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_teams}` WHERE `id` = '".$this->escape($id)."'";
                return $this->fetchOne($sql);
            }
        }
        
        public function getMemberList($id = null, $year = null) {
            if(!empty($id) && !empty($year)) {
                $sql = "SELECT * FROM `{$this->_involvements}` WHERE `project_type_id` = '".$this->escape($id)."' AND `year` = '".$this->escape($year)."'";
                return $this->fetchAll($sql);
            }
        }
        
        public function getAllTeams($exclude = null) {
            $sql = "SELECT * FROM `{$this->_teams}` ORDER BY `name` ASC ";
            if(!empty($exclude)) {
                $sql .= "WHERE `id` != ".$this->escape($exclude);
            }
            return $this->fetchAll($sql);
        }
        
        public function getTeamsForSearch() {
            $sql = "SELECT * FROM `{$this->_teams}` WHERE `id` NOT IN(7, 8, 9, 10) ORDER BY `name` ASC";
            return $this->fetchAll($sql);
        }
        
        public function getAllTeamsInProject($project_type_id = null) {
            if(!empty($project_type_id)) {
                if($project_type_id == 5) {
                    $sql = "SELECT * FROM `{$this->_teams}` WHERE `exco` = '1' ORDER BY `exco_order` ASC ";
                } else {
                    $sql = "SELECT * FROM `{$this->_teams}` WHERE `project` = '1' ORDER BY `project_order` ASC";
                }
                return $this->fetchAll($sql);
            }
        }
        
        public function getTeamsForPosition($position_id = null, $project_type_id = null) {
            if(!empty($position_id) && !empty($project_type_id)) {
                $sql = "SELECT t.`id`, t.`name`
                        FROM `{$this->_position_team}` pt
                        JOIN `{$this->_teams}` t
                        ON pt.`team_id` = t.`id`
                        WHERE pt.`position_id` = ".$this->escape($position_id);
                if($project_type_id == 5) {
                    $sql .= " ORDER BY t.`exco_order`";
                } else {
                    $sql .= " ORDER BY t.`project_order`";
                }
                return $this->fetchAll($sql);
            }
        }
        
        public function getYears($id = null) {
            if(!empty($id)) {
                $sql = "SELECT DISTINCT `year` FROM `{$this->_involvements}` WHERE `project_type_id` = ".$this->escape($id)." ORDER BY `year` ASC";
                return $this->fetchAll($sql);
            }            
        }
        
        public function checkTeamExistsInInvolvements($id = null, $exco = false) {
            if(!empty($id)) {
                if($exco) {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `team_id` = ".$this->escape($id)." AND `project_type_id` = 5 LIMIT 1";
                } else {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `team_id` = ".$this->escape($id)." AND `project_type_id` != 5 LIMIT 1";
                }
                $result = $this->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function updateTeam($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->prepareUpdate($params); 
                return $this->update($this->_teams, $id);
            }
        }
        
        public function getLastPosition($exco = false) {
            if($exco) {
                $sql = "SELECT MAX(`exco_order`) AS `last` FROM `{$this->_teams}`";
            } else {
                $sql = "SELECT MAX(`project_order`) AS `last` FROM `{$this->_teams}`";
            }
            return $this->fetchOne($sql)['last'];
        }
        
        public function resetOrder($type = null) {
            switch($type) {
                case 'exco':
                $sql = "SET @order = 0";
                $this->query($sql);
                $sql = "UPDATE `{$this->_teams}` t SET t.`exco_order` = (@order:=@order+1) WHERE t.`exco` = '1' ORDER BY t.`exco_order` ASC";
                return $this->query($sql);
                break;
                
                case 'project':
                $sql = "SET @order = 0";
                $this->query($sql);
                $sql = "UPDATE `{$this->_teams}` t SET t.`project_order` = (@order:=@order+1) WHERE t.`project`= '1' ORDER BY t.`project_order` ASC";
                return $this->query($sql);
                break;
            }
        }
        
    }
?>
