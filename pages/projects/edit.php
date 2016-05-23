<?php 
    
    $teams = $objProject->getMemberListWithTeam($id, $project['project_type_id']);
    $exco = $this->cPage == 'exco' ? 1 : 0;
    $project_type = $this->cPage == 'exco' ? 'exco' : 'project';
    if(!$exco) { 
        $volunteers = $objProject->getMemberListWithTeam($id, $project['project_type_id'],true);
    }
    
    $header = $project['name'].' '.$project['project_time'].' :: Edit';
    require_once('_header.php'); 
    
?>
        <h1>
            <?php echo $header; ?> 
                <span class="h2rightlink"><a href="<?php echo $this->objPage->generateURL($project_type, array('id' => $id)) ?>"><u>View this <?php echo $exco ? 'EXCO' : 'project'; ?></u></a></span>
        </h1>
<!--
        <div 
            id="projectInfo"
            data-project_id=<?php echo $project['id']; ?>
            data-project_type_id=<?php echo $project['project_type_id']; ?>
        >
        </div>
-->
        <div id="links" 
            data-get_team="/sugarkms/mod/getTeamsForPosition.php"
        ></div>  
        
        
        <div class="tabs">
            <ul>
                <?php if($exco) { ?>
                    <li><a href="#members">Members</a></li>
                <?php } else { ?>
                    <li><a href="#organizers">Organizers</a></li>
                    <li><a href="#volunteers">Volunteers</a></li>
                <?php } ?>
                <?php if(!$exco) { ?>
                    <li><a href="#manage_application">Application</a></li>
                <?php } ?>
                
                <li><a href="#add_member">Add Member</a></li>
            </ul>
            <div class="dev borderTop"></div>
            <?php if($exco) { ?>
            <div id="members" >
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                    <tr>
                        <td style="padding-right:20px;width:220px;vertical-align:top;overflow-y:scroll;">
                            <div class="reloadSection" data-plugin="project_manage_member">
                                <?php echo Plugin::get('project_manage_member', array('teams' => $teams, 'project' => $project, 'type' => 'Member', 'total' => $objProject->_total)); ?> 
                            </div>                                             
                        </td>
                        <td style="vertical-align:top;">
                            <div class="reloadSection" data-plugin="involvement_details" >
                                <?php echo Plugin::get('involvement_details', array()); ?>
                            </div>
                        </td>
                     </tr>
                </table>            
            
            </div>
            <?php } else { ?>
            <div id="organizers">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                    <tr>
                        <td style="padding-right:20px;width:220px;vertical-align:top;">
                            <div class="reloadSection" data-plugin="project_manage_member">
                                <?php echo Plugin::get('project_manage_member', array('teams' => $teams, 'project' => $project, 'type' => 'Organizer', 'total' => $objProject->_total)); ?>             
                            </div>
                        </td>
                        <td style="vertical-align:top;">
                            <div class="reloadSection" data-plugin="involvement_details" >
                                <?php echo Plugin::get('involvement_details', array()); ?>
                            </div>
                        </td>
                     </tr>
                </table>  
            </div>
            
            <div id="volunteers">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                    <tr>
                        <td style="padding-right:20px;width:220px;vertical-align:top;"> 
                            <div class="reloadSection" data-plugin="project_manage_member">
                                <?php echo Plugin::get('project_manage_member', array('teams' => $volunteers, 'project' => $project, 'type' => 'Volunteer', 'total' => $objProject->_total_volunteer)); ?>                 
                            </div>
                        </td>
                        <td style="vertical-align:top;">
                            <div class="reloadSection" data-plugin="involvement_details" >
                                <?php echo Plugin::get('involvement_details', array()); ?>
                            </div>
                        </td>
                     </tr>
                </table>  
                
            </div>
            <?php } ?>
            
            <div id="add_member" class="reloadSection" data-plugin="project_add_member">
                <?php echo Plugin::get('project_add_member', array('project' => $project)); ?> 
            </div>
            
            <?php if(!$exco) { ?>
                <div id="manage_application">
                    <div class="fl_l">
                        <h2>Add Application</h2>
                        <div class="reloadSection" data-plugin="project_add_application" >
                             <?php echo Plugin::get('project_add_application', array('project' => $project)); ?> 
                        </div>
                        
                    </div>
                    <div style="margin-left:35%;">
                        <h2>Manage Applications</h2>
                        <div class="reloadSection" data-plugin="project_manage_application"  >      
                            <?php echo Plugin::get('project_manage_application', array('project' => $project)); ?> 
                        </div>  
                    </div>
                    
                </div>
            <?php } ?>
            

        </div>
        

<!--
        <div id="list">
            <?php if($exco) { ?>
                <h4><strong>Member List (<?php echo $objProject->_total; ?>)</strong></h4>
                <?php echo Plugin::get('project_edit_member', array('teams' => $teams, 'project' => $project)); ?>
            <?php } else { ?>
                <div class="tabs">
                    <ul>
                        <li><a href="#organizers">Organizers (<?php echo $objProject->_total; ?>)</a></li>
                        <li><a href="#volunteers">Volunteers (<?php echo $objProject->_total_volunteer; ?>)</a></li>
                    </ul>
                    <div id="organizers">
                        <?php echo Plugin::get('project_edit_member', array('teams' => $teams, 'project' => $project)); ?>                  
                    </div>
                    <div id="volunteers" style="display:none;">
                        <?php echo Plugin::get('project_edit_member', array('teams' => $volunteers, 'project' => $project)); ?> 
                    </div>
                </div>
            <?php }  ?>
            
        </div>
-->


<?php 
    require_once('_footer.php'); 
?>