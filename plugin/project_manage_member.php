    <?php
        if(!isset($data['params'])) {
            $teams = $data['teams'];
            $project = $data['project'];        
            $type = $data['type'];
            $total = $data['total'];
        } else {
            $params = $data['params'];
            $objProject = new Project();
            $project_id = $params['project_id'];
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
            
        }
        
        
        $objPage = new Page();
    ?>
        <div class="sectionParams" data-params="project_id=<?php echo $project['id']; ?>&type=<?php echo $type; ?>"></div>
        <h2><?php echo $type; ?> List (<?php echo $total; ?>)</h2>
        <div style="display:block;width:220px;height:550px;overflow-y:scroll;border-top:1px dashed #AAA;" >
            
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" data-object="involvement">
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
        </div>
        <div style="height:25px;clear:both;"></div>
        
            
            
