<?php 

    $involvements = $objMember->getInvolvements(array('member_id' => $id));

    if($member['gender'] == 'Male') {
        $address = 'Mr.';
    } else {
        $address =  'Ms.';
    }            
    $currently = $objMember->getInvolvements(array('member_id' => $id), true);
    
    $header = $member['name'].' :: View';
    require_once('_header.php'); 
    
?>
        <h1><?php echo $member['name']; ?> :: View
            <?php //echo $objMember->generateURLentity($member['name']); ?>
            <?php if($this->objPage->canAccess(array('action' => 'edit', 'id' => $id), $current_user['id'], '', 'member')) { ?>
                <span class="h2rightlink"><a href="<?php echo $this->objPage->generateURL($cPage, array('id' => $id, 'action' => 'edit')); ?>"><u>Edit this profile</u></a></span>
            <?php } ?>
        </h1>      
        <?php //print_r($currently); ?>
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
                <h5><?php 
                    if(!empty($member['uni'])) {
                        echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                    } else {
                        if(!empty($member['high_school'])) {
                            echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                        }
                    } ?>
                </h5>
                <?php } ?>
                <h5><?php 
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
                            if($current['project_type_id'] == 5) {
                                switch ($current['position_id']) {
                                    
                                    case '8':
                                    echo $current['position_name'].' of Sugar, EXCO '.$current['project_time'];
                                    break;
                                    
                                    case '10':
                                    echo 'IT Administrator of Sugar, EXCO '.$current['project_time'];
                                    break;
                                    
                                    default:
                                    echo $current['position_name'].', '.$current['team_name'].', EXCO '.$current['project_time'];
                                    break;
                                }
                            } else {
                                switch($current['position_id']) {
                                    
                                    
                                    
                                    case '1':
                                    if($current['team_id'] == 9) {
                                        echo $current['position_name'].', '.$current['project_name'].' '.$current['project_time'];
                                    } else {
                                        echo $current['position_name'].', '.$current['team_name'].', '.$current['project_name'].' '.$current['project_time'];
                                    }
                                    
                                    break;
                                    
                                    case '3':
                                    echo $current['position_name'].', '.$current['team_name'].', '.$current['project_name'].' '.$current['project_time'];
                                    break;
                                    
                                    
                                    
                                    case '4':
                                    case '5':
                                    echo ' '.$current['position_name'].', '.$current['project_name'].' '.$current['project_time'];
                                    break;
                                }
                            }
                            echo "<br />";
                        }
                    }
                    ?>
                </h5>
            </div>            
        </fieldset>
        <br />
        
        <div class="fl_l" >  
            <h2>Profile</h2>
            <table cellpadding="0" cellspacing="0" border="0" >
                <tr>
                    <td><strong>Name</strong></td>
                    <td><?php echo $member['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Date of Birth</strong></td>
                    <td>
                        <?php 
                            if(empty($member['day']) || empty($member['month'])) {
                                echo !empty($member['year']) ? $member['year'] : '';
                            } else {
                                echo $member['day'].'/'.$member['month'].'/'.$member['year'];
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>High School</strong></td>
                    <td>
                        <?php if(!empty($member['high_school'])) {
                            echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>University</strong></td>
                    <td>
                        <?php if(!empty($member['uni'])) {
                            echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                        } ?>
                    </td>
                </tr>
            </table>
            
            <!--
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
            <strong>High School:</strong> <?php if(!empty($member['high_school'])) {
                                                    echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                                                } ?><br />
            <strong>University:</strong> <?php if(!empty($member['uni'])) {
                                                    echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                                                } ?>
-->
        </div>
        
        
        <div style="margin-left:50%;">  
            <h2>Contacts</h2>
            <?php if($objMember->canViewMemberContact($current_user['id'], $member['id'])) {?>
                <table cellpadding="0" cellspacing="0" border="0" >
                    <tr>
                        <td><strong>Email</strong></td>
                        <td><?php echo $member['personal_email']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Facebook</strong></td>
                        <td><a target="_blank" href="<?php echo $member['facebook']; ?>"><?php echo $member['facebook']; ?></a></td>
                    </tr>
                    <tr>
                        <td><strong>Skype</strong></td>
                        <td><?php echo $member['skype']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td><?php echo $member['phone']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Residence District</strong></td>
                        <td><?php echo $member['district_name']; ?></td>
                    </tr>
                </table>
                <!--
                <strong>Email:</strong> <?php echo $member['personal_email']; ?> <br />
                <strong>Facebook:</strong> <a target="_blank" href="<?php echo $member['facebook']; ?>"><?php echo $member['facebook']; ?></a><br />
                <strong>Skype:</strong> <?php echo $member['skype']; ?> <br />
                <strong>Phone:</strong> <?php echo $member['phone']; ?> <br />
                <strong>District of residence:</strong> <?php echo $member['district_name']; ?> <br />
                -->
            <?php } else {
                $given_name = substr($member['name'], strrpos($member['name'], ' ') + 1);
            ?>
                You can't view <?php echo $given_name ?>'s contacts because:<br />
                - You haven't officially worked with <?php echo $given_name ?> in any project, and<br />
                - You are not a member of Sugar's current EXCO.
            <?php } ?>
        </div>
       
        
        
        <!--<p><strong>Sugar email:</strong> <?php echo $member['sugar_email']; ?></p>
        <p><strong>Personal email:</strong> <?php echo $member['personal_email']; ?></p>
        <p><strong>Facebook:</strong> <?php echo $member['facebook']; ?></p>
        <p><strong>Skype:</strong> <?php echo $member['skype']; ?></p>
        <p><strong>Phone:</strong> <?php echo $member['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $member['district'].', TP HCM'; ?></p>-->

        <br />
        
        <div style="clear:both;margin-top:15px;">
        
        <h2>Involvements with Sugar</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
                <tr>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Project / EXCO</th>
                    <th>Time</th>
                    <th>In Charge</th>
                </tr>
                <?php 
                    if(!empty($involvements)) {
                        foreach($involvements as $involvement) { ?>
                <tr>
                    <td><?php echo $involvement['position_name']; ?></td>
                    <td><?php echo $involvement['team_name']; ?></td>                
                    <td><a href="<?php $type = $involvement['project_type_id'] == 5 ? 'exco' : 'project'; echo $this->objPage->generateURL($type, array('id' => $involvement['project_id'])); ?>" target="_blank"><?php echo $involvement['project_name'].' '.$involvement['project_time']; ?></a></td>
                    <td><?php echo $involvement['participation_time'] ?></td>
                    <td><?php echo $involvement['in_charge']; ?></td>
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
        </div>
<?php 
    require_once('_footer.php'); 
?>