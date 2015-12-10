<?php
    class Team extends Application {
        
        private $_members = 'members';
        private $_project_types = 'project_types';
        private $_projects = 'projects';
        private $_involvements = 'involvements';
        private $_teams = 'teams';
        private $_positions = 'positions';
        private $_waves = 'waves';
        private $_position_team = 'position_team';
        
        public function getTeamById($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_teams}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getMemberList($id = null, $year = null) {
            if(!empty($id) && !empty($year)) {
                $sql = "SELECT * FROM `{$this->_involvements}` WHERE `project_type_id` = '".$this->db->escape($id)."' AND `year` = '".$this->db->escape($year)."'";
                return $this->db->fetchAll($sql);
            }
        }
        
        public function getAllTeams($exclude = null) {
            $sql = "SELECT * FROM `{$this->_teams}` ORDER BY `name` ASC ";
            if(!empty($exclude)) {
                $sql .= "WHERE `id` != ".$this->db->escape($exclude);
            }
            return $this->db->fetchAll($sql);
        }
        
        public function getOtherTeams($exclude = null, $exco = null) {
            $sql = "SELECT * FROM `{$this->_teams}` ";
            if($exco) {
                $sql .= "WHERE `exco` = 'yes' ";
            }
            if(!empty($exclude)) {
                $sql .= "AND `id` != ".$this->db->escape($exclude);
            }
            $sql .= " ORDER BY `name` ASC";
            return $this->db->fetchAll($sql);
        }
        
        public function getTeamsForSearch() {
            $sql = "SELECT * FROM `{$this->_teams}` WHERE `id` NOT IN(7, 8, 9, 10) ORDER BY `name` ASC";
            return $this->db->fetchAll($sql);
        }
        
        public function getAllTeamsInProject($project_type_id = null) {
            if(!empty($project_type_id)) {
                if($project_type_id == 5) {
                    $sql = "SELECT * FROM `{$this->_teams}` WHERE `exco` = 'yes' ORDER BY `exco_order` ASC ";
                } else {
                    $sql = "SELECT * FROM `{$this->_teams}` WHERE `project` = 'yes' ORDER BY `project_order` ASC";
                }
                return $this->db->fetchAll($sql);
            }
        }
        
        public function getTeamsForPosition($position_id = null, $project_type_id = null) {
            if(!empty($position_id) && !empty($project_type_id)) {
                $sql = "SELECT t.`id`, t.`name`
                        FROM `{$this->_position_team}` pt
                        JOIN `{$this->_teams}` t
                        ON pt.`team_id` = t.`id`
                        WHERE pt.`position_id` = ".$this->db->escape($position_id);
                if($project_type_id == 5) {
                    $sql .= " ORDER BY t.`exco_order`";
                } else {
                    $sql .= " ORDER BY t.`project_order`";
                }
                return $this->db->fetchAll($sql);
            }
        }
        
        public function getYears($id = null) {
            if(!empty($id)) {
                $sql = "SELECT DISTINCT `year` FROM `{$this->_involvements}` WHERE `project_type_id` = ".$this->db->escape($id)." ORDER BY `year` ASC";
                return $this->db->fetchAll($sql);
            }            
        }
        
        public function checkTeamExistsInInvolvements($id = null, $exco = false) {
            if(!empty($id)) {
                if($exco) {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `team_id` = ".$this->db->escape($id)." AND `project_type_id` = 5 LIMIT 1";
                } else {
                    $sql = "SELECT 1 FROM `{$this->_involvements}` WHERE `team_id` = ".$this->db->escape($id)." AND `project_type_id` != 5 LIMIT 1";
                }
                $result = $this->db->fetchAll($sql);
                if(!empty($result)) {
                    return 'disabled';
                }
            }
        }
        
        public function updateTeam($params = null, $id = null) {
            if(!empty($params) && is_array($params) && !empty($id)) {
                $this->db->prepareUpdate($params); 
                return $this->db->update($this->_teams, $id);
            }
        }
        
        public function getLastPosition($exco = false) {
            if($exco) {
                $sql = "SELECT MAX(`exco_order`) AS `last` FROM `{$this->_teams}`";
            } else {
                $sql = "SELECT MAX(`project_order`) AS `last` FROM `{$this->_teams}`";
            }
            return $this->db->fetchOne($sql)['last'];
        }
        
        public function addTeam($params = null) {
            if(!empty($params) && is_array($params)) {
                $this->db->prepareInsert($params); 
                return $this->db->insert($this->_teams);
            }
        }
        
        public function removeTeam($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_teams}` WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->query($sql);
            }
            return false;
        }
        
        public function resetOrder($type = null) {
            switch($type) {
                case 'exco':
                $sql = "SET @order = 0";
                $this->db->query($sql);
                $sql = "UPDATE `{$this->_teams}` t SET t.`exco_order` = (@order:=@order+1) WHERE t.`exco` = 'Yes' ORDER BY t.`exco_order` ASC";
                return $this->db->query($sql);
                break;
                
                case 'project':
                $sql = "SET @order = 0";
                $this->db->query($sql);
                $sql = "UPDATE `{$this->_teams}` t SET t.`project_order` = (@order:=@order+1) WHERE t.`project`= 'Yes' ORDER BY t.`project_order` ASC";
                return $this->db->query($sql);
                break;
            }
        }
        
    }
?>
