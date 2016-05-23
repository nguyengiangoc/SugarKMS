<?php
    $objMember = new Member();
    $rights = $objMember->getAllContactAccessRights();
    require_once('_header.php');
?>
    <h1><?php echo $header; ?></h1>
    <?php //echo $objMember->canViewMemberContact(6,41) ? "true" : "false"; ?>
    <h2>Contact Access Rights</h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
            <tr>
                
                <th>Position</th>
                <th>Can View Contacts Of</th>
                <th>Action</th>
            </tr>
       <?php
            foreach($rights as $right) {?>
       
            <tr> 
                <td >
                    <?php 
                        if($right['current'] == 1) {
                            $result = 'Current ';
                        } else if($right['current'] == 0) {
                            $result = 'Former ';
                        }
                        if($right['exco'] == 1) {
                            $result .= 'EXCO members';
                        } else if($right['exco'] == 0) {
                            $result .= 'project organizers & volunteers';
                        } 
                        echo $result;
                    ?>
                </td>
                <td >
                    <?php 
                        switch($right['view_right']) {
                            case 1:
                            echo 'Everyone';
                            break;
                            
                            case 2:
                                if($right['exco'] == 1) {
                                    echo 'Only people from the same EXCO';
                                } else if($right['exco'] == 0) {
                                    echo 'Only people from the same Project';
                                }
                            
                            break;
                        }
                        
                    ?>
                </td>
                <td >
                    
                </td>

            </tr>
        <?php }
        ?>
            <tr >
            <form id="addTypeForm">
                <td >
                    
                </td>
                <td >
                    
                </td>
                <td >
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn" value="Add" id="addProjectTypeBtn" style="padding:0 10px;" data-url="/sugarkms/mod/addProjectType.php"/>
                    </label>
                </td>
            </form>
            </tr>
            <tr>
                <td colspan="3" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
            </tr>
        </table>
        <div style="height:25px;"></div>


<?php
    require_once('_footer.php');
?>