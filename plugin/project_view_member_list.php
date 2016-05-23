<?php 
    $teams = $data['teams']; 
    $can_view = $data['can_view'];
    $objPage = new Page();
    //var_dump($teams);
?>
    <div>
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:0px;width:auto;float:left;">
                <tr>
                    <th  class="borderRight">Name</th>
                </tr>
            <?php 
                foreach($teams as $key => $team) { 
                    if(isset($team['members'])) {
            
            ?>
                <tr class="groupRow <?php //echo $key != 0 ? 'borderTop' : ''; ?>">
                    <td colspan="4" class="borderRight">
                        <strong><?php echo strtoupper($team['name']).' ['.count($team['members']).']'; ?>
                    </strong>
                </tr>
                <?php foreach($team['members'] as $member) { ?>
                <tr class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"> 
                    <td class=" borderRight">
                        <a class="link_btn" href="<?php echo $objPage->generateURL('member', array('id' => $member['member_id'])); ?>" target="_blank">
                            <?php echo $member['member_name']; ?>
                        </a>
                    </td>
                </tr>
                <?php } ?> 
            <?php 
                    }
                } 
            ?>
            </table>
    </div>
    
    <div class="dragDiv" style="overflow-x:scroll;white-space: nowrap;">
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:0px;">
            <tr>
                <th>Sex</th>
                <th>Position</th>
                <th>In charge</th>
                <th>Email</th>
                <th>Phone</th>
                <th>FB</th>
                <th>Skype</th>
                <th>Birthday</th>
                <th>School</th>
            </tr>
        <?php 
            foreach($teams as $key => $team) { 
                if(isset($team['members'])) {
        
        ?>
            <tr class="groupRow <?php //echo $key != 0 ? 'borderTop' : ''; ?>">
                <td colspan="3">
                </td>
                <td colspan="6">
                    <?php if($can_view) {?>
                        <a href="#" class="getTeamEmail">Get emails</a>
                    <?php } else {?>
                        <span style="visibility:hidden;">Filler</span>
                    <?php } ?>
                </td>
            </tr>
            <?php foreach($team['members'] as $member) { ?>
            <tr class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"> 
                <td ><?php echo $member['gender'] == 'Male' ? 'M' : 'F'; ?></td>
                <td class=" "><?php echo $member['position']; ?></td>
                <td ><?php echo $member['in_charge']; ?></td>
                <?php if($can_view) {?>
                <td class=" email"><?php echo $member['personal_email']; ?></td>
                <td class=" "><?php echo $member['phone']; ?></td>
                <td class=" "><a class=" target="_blank" href="<?php echo $member['facebook']; ?>">Link</a></td>
                <td class=" "><?php echo $member['skype']; ?></td>
                <?php } else {?>
                <td class=" email"><span class="hidden withdrawn">(Hidden)</span></td>
                <td class=" "><span class="hidden withdrawn">(Hidden)</span></td>
                <td class=" "><span class="hidden withdrawn">(Hidden)</span></td>
                <td class=" "><span class="hidden withdrawn">(Hidden)</span></td>
                <?php } ?>
                <td class=" ">
                    <?php 
                        if(empty($member['day']) || empty($member['month'])) {
                            echo !empty($member['year']) ? $member['year'] : '';
                        } else {
                            echo $member['day'].'/'.$member['month'].'/'.$member['year'];
                        }
                    ?>
                </td>
                <td class=" "><?php 
                if(!empty($member['uni'])) {
                    echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                } else {
                    if(!empty($member['high_school'])) {
                        echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                    }
                    
                }       
            ?></td>
            </tr>
            <?php } ?> 
            
        <?php 
                }
            } 
        ?>
        </table>
    </div>  