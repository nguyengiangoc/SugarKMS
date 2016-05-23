    <?php 
        $objRecruitment = new Recruitment();
        $objPage = new Page();
        
        $recruitments = $objRecruitment->getCurrentRecruitments();
        
        if(count($recruitments) > 0) {
            foreach($recruitments as $recruitment) {
                ?>
                    <tr data-id="<?php echo $recruitment['id']; ?>">
                        <td>
                            <?php echo $recruitment['project']; ?>
                        </td>
                        <td>
                            <?php echo $recruitment['position']; ?>
                        </td>
                        <td>
                            <?php echo $recruitment['team']; ?>
                        </td>
                        <td>
                            <?php echo date('d-m-Y', strtotime($recruitment['deadline'])); ?>
                        </td>
                        <td>
                            <?php echo $recruitment['published'] ? '<span class="green">Published</span>' : '<span class="red">Unpublished</span>'; ?>
                        </td>
                        <td>
                            <a href="<?php echo $objPage->generateURL('recruitment', array('id' => $recruitment['id'], 'action' => 'edit'))  ?>" target="_blank">
                                Edit</a>
                        </td>
                        <td>
                            <?php
                                if($recruitment['published']) {
                                    $objApplication = new Application();
                                    $applications = $objApplication->getApplications(array('recruitment_id' => $recruitment['id']));
                                    if(empty($applications)) {
                                        ?>
                                            <a href="#" class="confirmRemove">Remove</a> 
                                        <?php
                                    } else {
                                        echo '<a href=# class="disabled">Remove</a>';
                                    }
                                } else {
                                    ?>
                                       <a href="#" class="confirmRemove">Remove</a> 
                                    <?php
                                }
                            ?>
                            
                        </td>
                    </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="8" style="text-align:center">There is no recruitment going on at the moment.</td>
            </tr>
            <?php
        }
        
 
    
    ?>

    