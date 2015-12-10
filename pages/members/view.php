<?php 

    $involvements = $objMember->getAllInvolvements($id);

    if($member['gender'] == 'Male') {
        $address = 'Mr.';
    } else {
        $address =  'Ms.';
    }            
    $currently = $objMember->getAllInvolvements($id, true);
    require_once('_header.php'); 
    
?>
        <h1><?php echo $member['name']; ?> :: View
            <?php if($objMember->canEditMember($profile['id'], $id)) { ?>
                <span class="h2rightlink"><a href="/sugarkms/members/id/<?php echo $id; ?>/action/edit"><u>Edit this profile</u></a></span>
            <?php } ?>
        </h1>      
        <fieldset>
            <div style="float:left;">
                <img style="margin-left:10px;" class="avatar" src="/sugarkms/images/<?php echo (empty($member['avatar']) ? "no_avatar.jpg" : $member['avatar']); ?>" alt="avatar" />
            </div>
            <div style="margin-left:160px;">
                <h2><?php 
                    echo $member['name'].' ('.$address.')'; 
                    if(!empty($member['year'])) {
                        echo ', '.(date('Y') - $member['year']);
                    }
                ?></h2>
                <?php if((!empty($member['high_school']) && !empty($member['grad_year_h'])) || (!empty($member['uni']) && !empty($member['grad_year_u']))) { ?>
                <h4><?php 
                    if(!empty($member['uni'])) {
                        echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                    } else {
                        if(!empty($member['high_school'])) {
                            echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                        }
                    } ?>
                </h4>
                <?php } ?>
                <h4><?php 
                    if($objMember->isFounder($id)) {
                        echo 'Co-Founder of Sugar, ';
                    }
                    if(empty($currently)) {
                        echo 'Currently Inactive';
                    } else {
                        echo 'Currently: ';
                        echo '<br />';
                        foreach($currently as $current) {
                            echo "- ";
                            switch ($current['position_id']) {
                                case '1':
                                echo $current['position_name'].', '.$current['project_name'].' '.$current['project_time'];
                                break;
                                
                                case '3':
                                echo $current['position_name'].', '.$current['team_name'].', '.$current['project_name'].' '.$current['project_time'];
                                break;
                                
                                case '6':
                                case '7':
                                echo $current['position_name'].', '.$current['team_name'].', EXCO '.$current['project_time'];
                                break;
                                
                                case '4':
                                case '5':
                                if($current['team_name'] == '------') {
                                    echo 'Project';
                                } else {
                                    echo $current['team_name'];
                                }
                                echo ' '.$current['position_name'].', '.$current['project_name'].' '.$current['project_time'];
                                break;
                                
                                case '8':
                                echo $current['position_name'].' of Sugar, EXCO '.$current['project_time'];
                                break;
                                
                                case '10':
                                echo 'IT Administrator of Sugar, EXCO '.$current['project_time'];
                                break;
                            }
                            echo "<br />";
                        }
                    }
                    ?>
                </h4>
            </div>            
        </fieldset>
        <br />
        <h2>Profile</h2>
        <div>  
            <strong>Name:</strong> <?php echo $member['name']; ?><br />
            <strong>Date of Birth:</strong> 
                <?php 
                    if(empty($member['day']) || empty($member['month'])) {
                        echo !empty($member['year']) ? $member['year'] : '';
                    } else {
                        echo $member['day'].'/'.$member['month'].'/'.$member['year'];
                    }
                ?>
            <br />
            <strong>Personal email:</strong> <?php echo $member['personal_email']; ?> <br />
            <strong>Facebook:</strong> <a target="_blank" href="<?php echo $member['facebook']; ?>"><?php echo $member['facebook']; ?></a><br />
            <strong>Skype:</strong> <?php echo $member['skype']; ?> <br />
            <strong>Phone:</strong> <?php echo $member['phone']; ?> <br />
            <strong>High School:</strong> <?php if(!empty($member['high_school'])) {
                                                    echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                                                } ?><br />
            <strong>University:</strong> <?php if(!empty($member['uni'])) {
                                                    echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                                                } ?>
        </div>

       
        
        
        <!--<p><strong>Sugar email:</strong> <?php echo $member['sugar_email']; ?></p>
        <p><strong>Personal email:</strong> <?php echo $member['personal_email']; ?></p>
        <p><strong>Facebook:</strong> <?php echo $member['facebook']; ?></p>
        <p><strong>Skype:</strong> <?php echo $member['skype']; ?></p>
        <p><strong>Phone:</strong> <?php echo $member['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $member['district'].', TP HCM'; ?></p>-->

        <br />
        <h2>Involvements with Sugar</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
                <tr>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Project / Group</th>
                    <th>Involvement Time</th>
                </tr>
                <?php 
                    if(!empty($involvements)) {
                        foreach($involvements as $row) { ?>
                <tr>
                    <td class="br_td "><?php echo $row['position_name']; ?></td>
                    <td class="br_td "><?php echo $row['team_name']; ?></td>                
                    <td class="br_td "><a href="/sugarkms/projects/id/<?php echo $row['project_id']; ?>" target="_blank"><?php echo $row['project_name'].' '.$row['project_time']; ?></a></td>
                    <td class="br_td "><?php echo $row['participation_time']; ?></td>
                </tr>
                <?php 
                        } 
                    } else { ?>
                <tr>
                    <td colspan="4"><center>No involvements have been recorded.</center></td>
                </tr>
                <?php
                    }
                ?>
            </table>
<?php 
    require_once('_footer.php'); 
?>