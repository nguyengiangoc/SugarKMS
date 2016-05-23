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
                <td style="width:45%;margin:right:5%;" class="alignTop" >
                    <h2>Current Job Description</h2>
                    <table cellpadding="0" cellspacing="0" border="0" >
                        <tr>
                            <td><strong>Available Positions</strong></td>
                            <td><?php echo empty($recruitment['positions']) ? '(Not Set)' : $recruitment['positions']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Requirements</strong></td>
                            <td><?php echo empty($recruitment['requirements']) ? '(Not Set)' : $recruitment['requirements']; ; ?></td>                            
                        </tr>                               
                        <tr>
                            <td><strong>Responsibilities</strong></td>
                            <td><?php echo empty($recruitment['responsibilities']) ? '(Not Set)' : $recruitment['responsibilities']; ?></td>                            
                        </tr> 
    
                    </table>
                </td>
                <td></td>
                <td class="alignTop" style="width:50%;"> 
                    <h2>Change Job Description</h2>
                    <form method="" action="" id="changeJDForm">
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                            <tr>
                                <td><strong>Available Positions</strong></td>
                                <td>                                  
                                    <select name="positions">
                                        <?php for($i=1;$i<=10;$i++) {
                                            ?>
                                            <option value="<?php echo $i; ?>" <?php echo $recruitment['positions'] == $i ? 'selected="selected"' : ''; ?> ><?php echo $i; ?></option>
                                            <?php
                                        } ?>
                                    </select>                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="alignTop"><strong>Requirements</strong></td>
                                <td style="width:65%;">
                                    <textarea name="requirements" style="width:100%;" rows="7"><?php echo $recruitment['requirements']; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="alignTop"><strong>Responsibilities</strong></td>
                                <td style="width:65%;">
                                    <textarea name="responsibilities" style="width:100%;" rows="7"><?php echo $recruitment['responsibilities']; ?></textarea>
                                </td>
                            </tr>
        
                        </table>
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn changeRecruitmentJD" value="Save changes" />
                        </label>
                    </form>
                    <br />
                </td>
                
                
                
            </tr>
            
            

        </table>