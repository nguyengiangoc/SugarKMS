<?php 
    $teams = $objProject->getMemberListWithTeam($id, $project['project_type_id']);
    if($project['project_type_id'] !=  5) { 
        $volunteers = $objProject->getVolunteerListWithTeam($id);
    }     
    require_once('_header.php'); 
    
    
?>
        
        <h1>
            <?php echo $project['name'].' '.$project['project_time'].' :: View'; ?>
            <?php if($objMember->canEditProject($profile['id'], $id)) { ?>
                <span class="h2rightlink"><a href="/sugarkms/projects/id/<?php echo $id; ?>/action/edit"><u>Edit this <?php echo $project['project_type_id'] != 5 ? 'project' : 'EXCO'; ?></u></a></span>
            <?php } ?>
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
                echo Plugin::get('front'.DS.'project_member_list', array('teams' => $teams));
            } else { 
        ?>
            <div id="tabs">
                <ul>
                    <li><a href="#organizers">
                        <?php 
                        if($project['project_type_id'] !=  5) { 
                            echo 'Organizers'; 
                        } else { 
                            echo 'EXCO Members';
                        }
                    ?>
                    (<?php echo $objProject->_total; ?>)</a></li>
                    <li><a href="#volunteers">Volunteers (<?php echo $objProject->_total_volunteer; ?>)</a></li>
                </ul>
                <div id="organizers">
                    <?php echo Plugin::get('front'.DS.'project_member_list', array('teams' => $teams)); ?>                  
                </div>
                <div id="volunteers" style="display:none;">
                    <?php echo Plugin::get('front'.DS.'project_member_list', array('teams' => $volunteers)); ?> 
                </div>
            </div>
        <?php
            }
        ?>        
        <div style="height:25px;"></div>
<?php 
    require_once('_footer.php'); 
?>