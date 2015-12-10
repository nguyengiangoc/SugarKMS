    <?php
        $rows = $data['rows'];
        $profile = $data['profile'];
        $objMember = $data['objMember'];
    ?>
    <div id="searchResult">
        <div class="dev br_td">&#160;</div>
        <div id="dialog" title="Dialog Title" style="display:none"></div> 
        <h2 style="clear:both">Member List
            <span class="h2rightlink">
                <a id="getAllEmail" href="#"><u>Get all emails</u></a>
            </span>
        </h2>
        <pre><?php //print_r($rows); ?></pre>
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list">
            <tr>
                <!--<th>Avatar</th>-->
                <th>Name</th>
                <th>School</th>
                <th>Email</th>
                <?php 
                    if($profile['level'] == 4 || $profile['level'] == 5) {
                        echo '<th>Remove</th>';
                        echo '<th>Edit</th>';
                    } elseif ($objMember->isEXCOWelfare($profile['id'])) {
                        echo '<th>Edit</th>';
                    }
                ?>
            </tr>
            <?php 
                    if(!empty($rows)) { 
                        foreach($rows as $member) { ?>
                <tr id="row-<?php echo $member['id']; ?>"> 
                    <td class="br_td name"><a class="link_btn" target="_blank" href="/sugarkms/members/id/<?php echo $member['id']; ?>" ><?php echo $member['name']; ?></a></td>
                    <td class="br_td">
                    <?php 
                        if((!empty($member['high_school']) && !empty($member['grad_year_h'])) || (!empty($member['uni']) && !empty($member['grad_year_u']))) {
                            if(!empty($member['uni'])) {
                                echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                            } else {
                                echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                            } 
                        }      
                    ?></td>
                    <td class="br_td email"><?php echo $member['personal_email']; ?></td>
                    <?php
                        if($objMember->isAdmin($profile['id'])) { ?>
                            <td class="br_td"><a class="link_btn clickAddRowConfirm" href="#" data-url="/sugarkms/members/id/<?php echo $member['id']; ?>/action/edit" 
                                     data-span="5">Remove</a></td>
                            
                            <td class="br_td"><a class="link_btn" target="_blank" href="/sugarkms/members/id/<?php echo $member['id']; ?>/action/edit">Edit</a></td>
                    <?php } ?>
                </tr>
                <?php 
                        } 
                    } else { ?>
                <tr> 
                    <td colspan="4">
                        There are no members that match your criteria.
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>