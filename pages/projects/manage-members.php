    <?php

            $objProject = new Project();
            $project_id = $this->cPage_params['id'];
            $project = $objProject->getProjectById($project_id);
            $type = $params['type'];
            if($type == 'Volunteer') {
                $volunteer = true;
            } else {
                $volunteer = false;
            }
            $teams = $objProject->getMemberListWithTeam($project_id, $project['project_type_id'], $volunteer);
            if($type != 'Volunteer') {
                $total = $objProject->_total;
            } else {
                $total = $objProject->_total_volunteer;
            }
            
        
        
        $objPage = new Page();
    ?>
    <div class="sectionParams" data-params="project_id=<?php echo $project['id']; ?>&type=<?php echo $type; ?>"></div>
    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
        <tr>
            <td style="padding-right:30px;width:220px;vertical-align:top;">
                <h2><?php echo $type; ?> List (<?php echo $total; ?>)</h2>
                <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:25px;width:220px;" data-object="involvement">
                    <tr>
                        <th>+</th>
                        <th >Name</th>
                    </tr>
                <?php 
                    foreach($teams as $team) { 
                        if(isset($team['members'])) {
                ?>
                    <tr class="groupRow">
                        <td colspan="6" style="border-top: dashed 1px #222;" >
                            <strong><?php echo strtoupper($team['name']).' ['.count($team['members']).']'; ?></strong>
                        </td>
                    </tr>
                    <tbody class="changeOrder" id="order">
                    <?php 
                        foreach($team['members'] as $member) { 
                    ?>
                    <tr data-id="<?php echo $member['involvement_id']; ?>" id="row-<?php echo $member['involvement_id']; ?>"> 
                        <td>
                            +
                        </td>
                        <td class="showInvolvement clickable" >
                            <a class="link_btn <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"
                            href="<?php echo $objPage->generateURL('member', array('id' => $member['member_id'])); ?>" target="_blank">
                            <?php echo $member['member_name']; ?></a>
                        </td>
                        
                
                    </tr>
                    <?php 
                            } 
                    ?>
                    </tbody>
                    <?php
                        }
                    } 
                    ?>
            
                
                
                            
                
                </table>
            </td>
            <td style="vertical-align:top;">
                <div class="reloadSection" data-plugin="involvement_details" >
                    <h2 class=" borderBottom">Change Involvement Details</h2>
                    <br />
                    Click the member's row to change the involvement details.
                </div>
            </td>
            
        </tr>
        
    </table>