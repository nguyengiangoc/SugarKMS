<?php $teams = $data['teams']; ?>


<div class="dragDiv" style="overflow-x:scroll;white-space: nowrap;">
                <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:0px;">
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>FB</th>
                        <th>Skype</th>
                        <th>Birthday</th>
                        <th>School</th>
                    </tr>
                <?php 
                    foreach($teams as $team) { 
                        if(isset($team['members'])) {
                
                ?>
                    <tr style="background:#f2f2f2;" class="teamRow">
                        <td colspan="3" style="border-top: dashed 1px #222;">
                            <strong><?php echo strtoupper($team['name']).' ['.count($team['members']).']'; ?>
                        </strong></td>
                        <td colspan="6" style="border-top: dashed 1px #222;">
                            <a href="#" class="getTeamEmail">Get emails</a>
                        </td>
                    </tr>
                    <?php foreach($team['members'] as $member) { ?>
                    <tr class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"> 
                        <td class="br_td" >
                            <a class="link_btn "
                            href="/sugarkms/members/id/<?php echo $member['member_id']; ?>" target="_blank">
                            <?php echo $member['member_name']; ?></a>
                        </td>
                        <td class="br_td"><?php echo $member['gender'] == 'Male' ? 'M' : 'F'; ?></td>
                        <td class="br_td "><?php echo $member['position']; ?></td>
                        <td class="br_td email"><?php echo $member['personal_email']; ?></td>
                        <td class="br_td "><?php echo $member['phone']; ?></td>
                        <td class="br_td "><a class=" target="_blank" href="<?php echo $member['facebook']; ?>">Link</a></td>
                        <td class="br_td "><?php echo $member['skype']; ?></td>
                        <td class="br_td ">
                            <?php 
                                if(empty($member['day']) || empty($member['month'])) {
                                    echo !empty($member['year']) ? $member['year'] : '';
                                } else {
                                    echo $member['day'].'/'.$member['month'].'/'.$member['year'];
                                }
                            ?>
                        </td>
                        <td class="br_td "><?php 
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
                    <tr>
                        <td style="height:0px;border-top: dashed 1px #aaa;padding:0;" colspan="6"></td>
                    </tr>
                </table>
            </div>  