    <?php 
        if(isset($data['params'])) {
            $params = $data['params'];
            $id = $params['id'];
        } else {
            $id = $data['id'];
        }
    
        $objRecruitment = new Recruitment();
        $recruitment = $objRecruitment->getRecruitmentById($id);
        
        $questions = $objRecruitment->getQuestionsForRecruitment($recruitment['id']);
        if(count($questions) == 0) {
            $can_published = false;
        } else {
            if(empty($recruitment['positions']) && empty($recruitment['requirements']) && empty($recruitment['responsibilities'])) {
                $can_published = false;
            } else {
                $can_published = true;
            }
        }
        
        
    ?>
        <div class="sectionParams" data-params="id=<?php echo $id; ?>"></div>
    
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;" class="alignTop">
                    <h2>Current Settings</h2>
                    <table cellpadding="0" cellspacing="0" border="0" >
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><?php echo $recruitment['published'] ? '<span class="green">Published</span>' : '<span class="red">Unpublished</span>'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Deadline</strong></td>
                            <td><?php echo date('d-m-Y', strtotime($recruitment['deadline'])); ?></td>
                            
                        </tr>                               
                                              
    
                    </table>
                </td>
                <td class="alignTop"> 
                    <h2>Change Settings</h2>
                    <form method="" action="" id="changeSettingsForm">
                        <table cellpadding="0" cellspacing="0" border="0" >
                            <tr>
                                <td class="alignTop"><strong>Status</strong></td>
                                <td>
                                    
                                     
                                    <?php 
                                        if(!$can_published) {
                                            ?>
                                                <!--
                                                <input type="radio" value="0" name="published" <?php echo $recruitment['published'] == 0 ? 'checked="checked"' : '' ; ?> /> Unpublished
                                                <br />
                                                <input type="radio" value="1" name="published" 
                                                    <?php 
                                                        if($can_published) {
                                                            echo $recruitment['published'] == 1 ? 'checked="checked"' : '' ;                                                                     
                                                            
                                                        } else {
                                                            echo 'disabled="disabled"';
                                                                                                                            
                                                        }
                                                    
                                                    ?> 
                                                /> Published
                                                <br /><br />
                                                -->
                                            <?php   
                                            echo 'This recruitment <strong>can not</strong> be published because:<br />' ;
                                            if(count($questions) == 0) {
                                                echo '- No question has been added.<br />';
                                            }
                                            if(empty($recruitment['positions']) && empty($recruitment['requirements']) && empty($recruitment['responsibilities'])) {
                                                echo '- The job description is empty.<br />';
                                            }
                                            echo '<br />';
                                        } else {
                                            ?>
                                                <input type="radio" value="0" name="published" <?php echo $recruitment['published'] == 0 ? 'checked="checked"' : '' ; ?> /> Unpublished
                                                <br />
                                                <input type="radio" value="1" name="published" <?php echo $recruitment['published'] == 1 ? 'checked="checked"' : '' ; ?> /> Published
                                            <?php
                                        }
                                    ?>
                                    
                                    
                                    
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Deadline</strong></td>
                                <td><input readonly type="text" class="datepicker position_deadline" style="width:70px;" name="deadline" value="<?php echo date('d-m-Y', strtotime($recruitment['deadline'])); ?>" /></td>
                                
                            </tr>
                            
        
                        </table>
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn changeRecruitmentSettings" value="Save changes" />
                        </label>
                    </form>
                    <br />
                </td>
                
                
                
            </tr>
            
            

        </table>