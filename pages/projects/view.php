<?php 
    $teams = $objProject->getMemberListWithTeam($id, $project['project_type_id']);
    if($project['project_type_id'] !=  5) { 
        $volunteers = $objProject->getMemberListWithTeam($id, $project['project_type_id'], true);
    }     
    $objMember = new Member();
    $can_view = $objMember->canViewMemberContact($current_user['id'],'',$project['id']);
    $exco = $this->cPage == 'exco' ? 1 : 0;    
    
    $header = $project['name'].' '.$project['project_time'].' :: View';
    require_once('_header.php'); 
    
    
    
?>
        
        <h1>
            <?php echo $project['name'].' '.$project['project_time'].' :: View'; ?>
            <?php 
                $project_type = $exco ? 'exco' : 'project';
                if($this->objPage->canAccess(array('action' => 'edit', 'id' => $id), $current_user['id'], '', $project_type)) { 
            ?>
                <span class="h2rightlink"><a href="<?php echo $this->objPage->generateURL($project_type, array('id' => $id, 'action' => 'edit')); ?>"><u>Edit this <?php echo $exco ? 'EXCO' : 'project'; ?></u></a></span>
            <?php 
                } 
            ?>
        </h1>     
         <div id="dialog" title="Dialog Title" style="display:none"></div>  

        <h2>Members
            <?php
                if($project['project_type_id'] ==  5) { 
                    echo '('.$objProject->_total.')';
                } else {
                    $total = $objProject->_total + $objProject->_total_volunteer;
                    echo '('.$total.')';
                }
            ?>
            <span class="h2rightlink"><a href="#" id="getAllEmail"><u>Get all emails</u></a></span>
        </h2>
        <?php 
            
            if($project['project_type_id'] ==  5) { 
                echo Plugin::get('project_view_member_list', array('teams' => $teams, 'can_view' => $can_view));
            } else { 
        ?>
            <div id="tabs">
                <ul>
                    <li><a href="#organizers">Organizers (<?php echo $objProject->_total; ?>)</a></li>
                    <li><a href="#volunteers">Volunteers (<?php echo $objProject->_total_volunteer; ?>)</a></li>
                </ul>
                <div id="organizers">
                    <?php echo Plugin::get('project_view_member_list', array('teams' => $teams, 'can_view' => $can_view)); ?>                  
                </div>
                <div id="volunteers">
                    <?php echo Plugin::get('project_view_member_list', array('teams' => $volunteers, 'can_view' => $can_view)); ?> 
                </div>
            </div>
        <?php
            }
        ?>        
        <div style="height:25px;"></div>
<?php 
    require_once('_footer.php'); 
?>