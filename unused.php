<script>
//switch($(this).val()) {
//                        
//                        case '8':
//                        case '4':
//                            $('#selectTeam').removeAttr('disabled');
//                            $('.teamOption').each(function() {
//                                if($(this).val() == '10') {
//                                    $('#selectTeam').append(this);
//                                    $(this).removeClass('teamOptionHide');
//                                    $(this).attr('selected', 'selected');
//                                } else {
//                                    $(this).addClass('teamOptionHide');
//                                    $(this).insertBefore('#selectTeam');
//                                    $(this).removeAttr('selected');
//                                }
//                            });
//                        break;
//                        
//                        case '5':
//                            $('#selectTeam').removeAttr('disabled');
//                            $('.teamOption').each(function() {
//                                if($(this).val() == '13') {
//                                    $('#selectTeam').append(this);
//                                    $(this).removeClass('teamOptionHide');
//                                    $(this).attr('selected', 'selected');
//                                } else {
//                                    $(this).addClass('teamOptionHide');
//                                    $(this).insertBefore('#selectTeam');
//                                    $(this).removeAttr('selected');
//                                }
//                            });
//                        break;
//                        
//                        case '10':
//                            $('#selectTeam').removeAttr('disabled');
//                            $('.teamOption').each(function() {
//                                if($(this).val() == '6') {
//                                    $('#selectTeam').append(this);
//                                    $(this).removeClass('teamOptionHide');
//                                    $(this).attr('selected', 'selected');
//                                } else {
//                                    $(this).addClass('teamOptionHide');
//                                    $(this).insertBefore('#selectTeam');
//                                    $(this).removeAttr('selected');
//                                }
//                            });
//                        break;
//                        
//                        case '1':
//                            $('#selectTeam').removeAttr('disabled');
//                            $('.teamOption').each(function() {
//                                if($(this).val() != '10' && $(this).val() != '13') {
//                                    $('#selectTeam').append(this);
//                                    $(this).removeClass('teamOptionHide');
//                                    $(this).attr('selected', 'selected');
//                                } else {
//                                    $(this).addClass('teamOptionHide');
//                                    $(this).insertBefore('#selectTeam');
//                                    $(this).removeAttr('selected');
//                                }
//                            });
//                        break;
//                        
//                        case '6':
//                        case '7':
//                        case '3':
//                            //var val = $(this).val();
////                            if(($(this).val() == 6 && previous == 7) || ($(this).val() == 7 && previous == 6)) {
////                                
////                            } else {
//                                $('#selectTeam').removeAttr('disabled');
//                                $('.teamOption').each(function() {
//                                    if($(this).val() == '10' || $(this).val() == '6' || $(this).val() == '9' || $(this).val() == '13') {
//                                        $(this).addClass('teamOptionHide');
//                                        $(this).insertBefore('#selectTeam');
//                                        $(this).removeAttr('selected');
//                                    } else {
//                                        $('#selectTeam').append(this);
//                                        $(this).removeClass('teamOptionHide');
//                                    }
//                                });
//                            //}
//                        break;   
                    //};

</script>























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
                <span style="float:right;font-size:12px;cursor:pointer;"><a href="/sugarkms/projects/action/edit/id/<?php echo $id; ?>"><u>Edit this <?php echo $project['project_type_id'] != 5 ? 'project' : 'EXCO'; ?></u></a></span>
            <?php } ?>
        </h1>     
        <!--
<pre><?php //print_r($teams); ?></pre>
        <fieldset>
            <div style="margin-left:160px;">
                <?php 
                    if($project['project_type_id'] !=  5) { 
                        echo '<h2>Project '.$project['name'].' '.$project['project_time'].'</h2>'; 
                    } else { ?>
                        <h2>Executive Committee <?php echo $project['project_time']; ?></h2>
                        <h5><strong>Term length</strong>: 1 year</h5>
                        <h5><strong>Activitiy status</strong>: <?php echo $objProject->getActivityStatus($id, $project['project_type_id'], $project['month_end'], $project['year_end']); ?></h5>
                    <?php }                   
                ?>
            </div>            
        </fieldset>
        
-->
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">
                    <?php 
                    if($project['project_type_id'] !=  5) { 
                        echo 'Organizers'; 
                    } else { 
                        echo 'EXCO Members';
                    }
                ?>
                (<?php echo $objProject->_total; ?>)</a></li>
                <li><a href="#tabs-2">Volunteers (<?php echo $objProject->_total_volunteer; ?>)</a></li>
            </ul>
            <div id="tabs-1">
                <div class="dragDiv" style="overflow-x:scroll;white-space: nowrap;" id="containerDiv">
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
                        <tr style="background:#f2f2f2;">
                            <td colspan="9" style="border-top: dashed 1px #222;">
                                <strong><?php echo strtoupper($team['name']).' ['.count($team['members']).']'; ?>
                            </strong></td>
                        </tr>
                        <?php foreach($team['members'] as $member) { ?>
                        <tr class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"> 
                            <td class="br_td" >
                                <a class="link_btn "
                                href="/sugarkms/members/action/view/id/<?php echo $member['member_id']; ?>" target="_blank">
                                <?php echo $member['member_name']; ?></a>
                            </td>
                            <td class="br_td"><?php echo $member['gender'] == 'Male' ? 'M' : 'F'; ?></td>
                            <td class="br_td "><?php echo $member['position']; ?></td>
                            <td class="br_td "><?php echo $member['personal_email']; ?></td>
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
            </div>
            <div id="tabs-2">
                <div class="dragDiv" style="overflow-x:scroll;white-space: nowrap;" id="containerDiv">
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
                        foreach($volunteers as $team) { 
                            if(isset($team['members'])) {
                    
                    ?>
                        <tr style="background:#f2f2f2;">
                            <td colspan="9" style="border-top: dashed 1px #222;">
                                <strong><?php echo strtoupper($team['name']).' ['.count($team['members']).']'; ?>
                            </strong></td>
                        </tr>
                        <?php foreach($team['members'] as $member) { ?>
                        <tr class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"> 
                            <td class="br_td" >
                                <a class="link_btn "
                                href="/sugarkms/members/action/view/id/<?php echo $member['member_id']; ?>" target="_blank">
                                <?php echo $member['member_name']; ?></a>
                            </td>
                            <td class="br_td"><?php echo $member['gender'] == 'Male' ? 'M' : 'F'; ?></td>
                            <td class="br_td "><?php echo $member['position']; ?></td>
                            <td class="br_td "><?php echo $member['personal_email']; ?></td>
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
            </div>
        </div>
        <h2><?php 
                if($project['project_type_id'] !=  5) { 
                    echo 'Organizers'; 
                } else { 
                    echo 'EXCO Members';
                }
            ?>
            (<?php echo $objProject->_total; ?>)
        </h2>
        
        
        
        
        <?php if($project['project_type_id'] != 5 && $objProject->_total_volunteer != 0) { ?>
        <div style="display:block;height:25px;"></div>
            <h2>Volunteers (<?php echo $objProject->_total_volunteer; ?>)</h2>
            
            
            
        <?php }  ?>
       
                        
        
        <div style="height:25px;"></div>
<?php 
    require_once('_footer.php'); 
?>







































<div style="overflow-x:scroll;white-space: nowrap;" id="dragDiv">
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:0px;">
            <tr>
                <th>Position</th>
                <th>Email</th>
                <th>Phone</th>
                <th>FB</th>
                <th>Skype</th>
                <!--
<th>School</th>
                <th>Profile</th>
-->
            </tr>
        <?php 
            foreach($teams as $team) { 
                if(isset($team['members'])) {
        ?>

            <tr class="team_name">
                <td colspan="7" style="border-top: dashed 1px #222;"><span style="color:#f2f2f2;">a</span></td>
            </tr>
            <?php foreach($team['members'] as $member) { ?>
            <tr> 
                <td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php echo $member['position']; ?></td>
                <td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php echo $member['personal_email']; ?></td>
                <td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php echo $member['phone']; ?></td>
                <td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><a class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?> target="_blank" href="<?php echo $member['facebook']; ?>">Link</a></td>
                <td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php echo $member['skype']; ?></td>
                <!--
<td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>"><?php 
                    if(!empty($member['uni'])) {
                        echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                    } else {
                        if(!empty($member['high_school'])) {
                            echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                        }
                        
                    }       
                ?></td>
                
                <td class="br_td <?php if($member['withdrawn']) { echo 'withdrawn'; } ?>">
                    <?php if($objMember->canEditMember($profile['id'], $member['member_id'])) { ?>
                        <a class="<?php if($member['withdrawn']) { echo 'withdrawn'; } ?> href="/sugarkms/members/action/edit/id/<?php echo $member['member_id']; ?>" target="_blank">Edit</a>
                    <?php } else { ?><u class="greyed">Edit</u><?php } ?>
                </td>
-->
            </tr>
            <?php } ?> 
            
        <?php 
                }
            } 
        ?>
            <tr>
                <td colspan="7" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
            </tr>
        </table>



































<td class="br_td">
                        <?php if($project['same_start_end'] == 'yes') { ?>
                            <select name="month_start" id="month_start" style="width:75px;border:1px solid #aaa;">
                                <?php 
                                    for($i=$project['month_start'];$i<$project['month_start']+1;$i++) { 
                                        echo '<option value="'.$i.'_'.$project['year_start'].'" '.$objForm->stickySelect('month_start', $i, $member['month_start']).'>'.$i.'/'.$project['year_start'].'</option>';
                                    }
                                ?>
                            </select>
                        <?php } else { ?>
                            <select name="month_start" id="month_start" style="width:75px;border:1px solid #aaa;">
                                <?php 
                                    for($i=$project['month_start'];$i<13;$i++) { 
                                        echo '<option value="'.$i.'_'.$project['year_start'].'" '.
                                                $objForm->stickySelect('month_start', $i.'_'.$project['year_start'], $member['month_start']).'>'
                                                    .$i.'/'.$project['year_start']
                                            .'</option>';
                                    }
                                    for($i=1;$i<$project['month_end']+1;$i++) { 
                                        echo '<option value="'.$i.'_'.$project['year_end'].'" '.
                                                $objForm->stickySelect('month_start', $i.'_'.$project['year_end'], $member['month_start']).'>'
                                                    .$i.'/'.$project['year_end']
                                            .'</option>';
                                    }
                                ?>
                            </select>
                        <?php } ?>
                    </td>
                    
                    <td class="br_td">
                        <?php if($project['same_start_end'] == 'yes') { ?>
                            <select name="month_end" id="month_end" style="width:75px;border:1px solid #aaa;">
                                <?php 
                                    for($i=$project['month_start'];$i<$project['month_start']+1;$i++) { 
                                        echo '<option value="'.$i.'_'.$project['year_start'].'" '.$objForm->stickySelect('month_end', $i, $member['month_start']).'>'.$i.'/'.$project['year_start'].'</option>';
                                    }
                                ?>
                            </select>
                        <?php } else { ?>
                            <select name="month_end" id="month_end" style="width:75px;border:1px solid #aaa;">
                                <?php 
                                    for($i=$project['month_start'];$i<13;$i++) { 
                                        echo '<option value="'.$i.'_'.$project['year_start'].'" '.
                                                $objForm->stickySelect(
                                                    'month_end', 
                                                    $i.'_'.$project['year_start'], 
                                                    $member['month_end'].'_'.$member['year_end']
                                                ).'>'
                                                    .$i.'/'.$project['year_start']
                                            .'</option>';
                                    }
                                    for($i=1;$i<$project['month_end']+1;$i++) { 
                                        echo '<option value="'.$i.'_'.$project['year_end'].'" '.
                                                $objForm->stickySelect(
                                                    'month_end', 
                                                    $i.'_'.$project['year_end'], 
                                                    $member['month_end'].'_'.$member['year_end']
                                                ).'>'
                                                    .$i.'/'.$project['year_end']
                                            .'</option>';
                                    }
                                ?>
                            </select>
                        <?php } ?>
                    </td>
                    
<select name="position[]" style="width:140px;border:1px solid #aaa;" class="changePosition">
                            <?php 
                                $positions = $objPosition->getTeamPositions(true, $team['id'], $member['position'], $member['position_id']);
                            ?>
                            <?php foreach($positions as $position) {
                                echo '<option value="'.$position['id'].'"'.$objForm->stickySelect('position[]', $position['id'] ,$member['position_id']).'>'.$position['name'].'</option>';
                            } ?>
                        </select>
                        

change position in a team: associate -> head, associate -> president, head-> president, head-> associate, president -> head, president -> associate
change from one team to another, with the position in the other team: head, assoc, president


                        | &nbsp; <span style="width:95px;display:inline-block;"><?php echo $member['involvement_time']; ?></span>