    <?php
        $teams = $data['teams'];
        $project = $data['project'];
        $total = $data['total'];
    ?>
    <h4><strong>Member List (<?php echo $total; ?>)</strong></h4>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:25px;">
        <tr>
            <th>Name</th>
            <th>Position</th>
            
        </tr>
    <?php 
        foreach($teams as $team) { 
            if(isset($team['members'])) {
    ?>
        <tr class="team_name">
            <td colspan="6" style="border-top: dashed 1px #222;">
                <strong><?php echo strtoupper($team['name']).' ['.count($team['members']).']'; ?></strong>
            </td>
        </tr>
        <?php 
             
                
               
                foreach($team['members'] as $member) { 
        ?>
        <tr> 
            <td class="br_td">
                <a class="link_btn <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"
                href="/sugarkms/members/id/<?php echo $member['member_id']; ?>" target="_blank">
                <?php echo $member['member_name']; ?></a>
            </td>
            <td class="br_td" >
                <span style="width:95px;display:inline-block;" 
                    class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php echo $member['position']; ?></span>
                | &nbsp; <span style="width:95px;display:inline-block;"
                            class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php echo $member['involvement_time']; ?></span>
                | &nbsp; <a href="#" class="changePosition <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>" 
                            
                            data-exco="<?php if($project['project_type_id'] == 5) {echo 'yes'; } ?>"
                            data-monthStart="<?php echo $project['month_start']; ?>"
                            data-yearStart="<?php echo $project['year_start']; ?>"
                            data-monthEnd="<?php echo $project['month_end']; ?>"
                            data-yearEnd="<?php echo $project['year_end']; ?>"
                            
                            data-monthStartM="<?php echo $member['month_start']; ?>"
                            data-yearStartM="<?php echo $member['year_start']; ?>"
                            data-monthEndM="<?php echo $member['month_end']; ?>"
                            data-yearEndM="<?php echo $member['year_end']; ?>"
                            data-invId="<?php echo $member['involvement_id']; ?>"
                            data-invChange="/sugarkms/mod/changeInvTime.php"
                            data-invRemove="/sugarkms/mod/removeInv.php"
                            data-projectReload="/sugarkms/projects/id/<?php echo $project['id']; ?>/action/edit/reload/yes"
                                >Change</a>
            </td>

            
    
        </tr>
        <?php 
                } 
            }
        } 
        ?>
        <tr>
            <td colspan="3" style="height:0px;border-top: dashed 1px #aaa;padding:0;"></td>
        </tr>
    
    
                
    
    </table>