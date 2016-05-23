        <?php 
            if(isset($data['params'])) {
                $params = $data['params'];
                $id = $params['id'];
            } else {
                $id = $data['id'];
            }
        
            $objRecruitment = new Recruitment();            
            $questions = $objRecruitment->getQuestionsForRecruitment($id);
            
            
        ?>
        <h2>Current Questions</h2>
        <div class="sectionParams" data-params="id=<?php echo $id; ?>"></div>
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" data-object="question">
            <tr>
                <th class="borderRight">+</th>
                <th>Question Display</th>
                <th colspan="2">Action</th>
            </tr>
            <tbody class="changeOrder" id="order">
                <?php 
                    if(count($questions) > 0) {
                        foreach($questions as $question) {
                            ?>
                                <tr id="row-<?php echo $question['id']; ?>" data-id="<?php echo $question['id']; ?>"> 
                                    <td style="width:5px;" class="borderRight">+</td>
                                    <td>
                                        <?php 
                                        
                                            echo '<strong>'.$question['label'].'</strong>';
                                            echo $question['required'] ? ' *' : '';
                                            echo '<br />';
                                            if($question['type'] == 'dropdown' || $question['type'] == 'radio' || $question['type'] == 'checkbox') {
                                                $choices = $objRecruitment->getQuestionChoices($question['id']);
                                            }
                                            
                                            switch($question['type']) {
                                                
                                                case 'text':    
                                                    echo '<span class="wordLimit">'.$question['min'].' - '.$question['max'].' words</span>';      
                                                    echo '<br />';                                  
                                                    echo '<input type="text" style="width:95%;" />';
                                                    
                                                    
                                                break;
                                                
                                                case 'paragraph':
                                                    echo '<span class="wordLimit">'.$question['min'].' - '.$question['max'].' words</span>';      
                                                    echo '<br />'; 
                                                    echo '<textarea style="width:95%;" row="2"></textarea>';
                                                break;
                                                
                                                case 'dropdown':
                                                    echo '<select >';
                                                    foreach($choices as $choice) {
                                                        echo '<option>'.$choice['label'].'</option>';
                                                    }
                                                    echo '</select>';
                                                break;
                                                
                                                case 'radio':
                                                    foreach($choices as $choice) {
                                                        echo '<input type="radio"> '.$choice['label'].'<br />';
                                                    }
                                                break;
                                                
                                                case 'checkbox':
                                                    foreach($choices as $choice) {
                                                        echo '<input type="checkbox"> '.$choice['label'].'<br />';
                                                    }
                                                break;
                                             }
                                        
                                        ?>
                                        
                                    </td>
                                    <td style="width:75px;"><a href="#" class="editQuestion">Edit</a> &nbsp;<a href="#" class="confirmRemove">Remove</a></td>
                                    
                                </tr>
                            <?php
                        }
                    } else {
                        ?>
                            <tr>
                                <td colspan="3" style="text-align:center;">No question added to this recruitment yet.</td>
                            </tr>
                        <?php
                    }
                    
                ?>
            </tbody>
            
        </table>
        
        