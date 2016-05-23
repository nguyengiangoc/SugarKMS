    <?php
        $rows = $data['rows'];
        $current_user = $data['profile'];
        $objMember = $data['objMember'];
        $objPage = new Page();
    ?>
    <div id="searchResult">
        <div class="dev borderTop">&#160;</div>
        <div id="dialog" title="Dialog Title" style="display:none"></div> 
        <h2 style="clear:both">Member List
            <span class="h2rightlink">
                <a id="getAllEmail" href="#"><u>Get all emails</u></a>
            </span>
        </h2>
        <pre><?php //print_r($rows); ?></pre>
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list">
            <tr>
                <th>Name</th>
                <?php 
                    if($objMember->isAdmin($current_user['id'])) {
                        echo '<th>Remove</th>';
                        echo '<th>Edit</th>';
                    } 
                ?>
                <th>Sex</th>
                <th>School</th>
                <th>Email</th>
                
            </tr>
            <?php 
                    if(!empty($rows)) { 
                        foreach($rows as $member) { ?>
                <tr data-id="<?php echo $member['id']; ?>"> 
                    <td class=" name"><a class="link_btn" target="_blank" href="<?php echo $objPage->generateURL('member', array('id' => $member['id'])); ?>" ><?php echo $member['name']; ?></a></td>
                    <?php
                        if($objMember->isAdmin($current_user['id'])) { ?>
                            <td ><a class="link_btn confirmRemove" href="#" data-span="5">Remove</a></td>
                            <td ><a class="link_btn" target="_blank" href="<?php echo $objPage->generateURL('member', array('id' => $member['id'], 'action' => 'edit')); ?>">Edit</a></td>
                    <?php } ?>
                    <td ><?php if($member['gender'] == 'Male') { echo 'M'; } else { echo 'F'; } ?></td>
                    <td >
                    <?php 
                        if((!empty($member['high_school']) && !empty($member['grad_year_h'])) || (!empty($member['uni']) && !empty($member['grad_year_u']))) {
                            if(!empty($member['uni'])) {
                                echo $member['uni'].' \''.substr($member['grad_year_u'],2,2);
                            } else {
                                echo $member['high_school'].' \''.substr($member['grad_year_h'],2,2);
                            } 
                        }      
                    ?></td>
                    <td class=" email">
                        <?php 
                            if($objMember->canViewMemberContact($current_user['id'], $member['id'])) {
                                echo $member['personal_email']; 
                            } else {
                                echo '<span class="hidden">(Hidden)</span>';
                            }
                            
                        ?>
                    </td>
                    
                </tr>
                <?php 
                        } 
                    } else { ?>
                <tr> 
                    <td colspan="4" style="text-align:center;">
                        There are no members that match your criteria.
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>