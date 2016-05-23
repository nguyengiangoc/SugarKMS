        <?php 
            if(isset($data['params']['question_id'])) {
                $params = $data['params'];
                $id = $params['question_id'];
                
                $objDbase = new dbase();
                $question = $objDbase->get('question', array('id' => $id));
                
                if(empty($question)) {
                    $record = false;
                } else {
                    $record = true;
                    $question = $question[0];
                }
            
            } else {
                
                $record = false;
            
            }
            
            if($record) {
            
                ?>
                    <h2 class=" borderBottom">Edit Question :: Question #<?php echo $id; ?> <a href="#" class="h2rightlink closeQuestion">Close</a></h2>
                    <div class="sectionParams" data-params="question_id=<?php echo $id; ?>"></div>
                    
                    <input type="hidden" value="<?php echo $question['recruitment_id']; ?>" name="recruitment_id" />
                    <input type="hidden" value="<?php echo $id; ?>" name="question_id" />
                    <br />

                    <form action="" method="" class="editQuestionForm">
                        
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%">
                            <tr>
                                <td><strong>Question Type *</strong></td>
                                <td>
                                    <select class="questionType" name="type">
                                        <?php
                                            if($question['type'] == 'text' || $question['type'] == 'paragraph') {
                                                ?>
                                                    <option value="text"  <?php echo $question['type'] == 'text' ? 'selected' : ''; ?>>Short Text</option>
                                                    <option value="paragraph"  <?php echo $question['type'] == 'paragraph' ? 'selected' : ''; ?>>Paragraph</option>
                                                <?php
                                            } else {
                                                ?>
                                                    <option value="dropdown"  <?php echo $question['type'] == 'dropdown' ? 'selected' : ''; ?>>Dropdown</option>
                                                    <option value="radio"  <?php echo $question['type'] == 'radio' ? 'selected' : ''; ?>>Radio Buttons</option>
                                                    <option value="checkbox"  <?php echo $question['type'] == 'checkbox' ? 'selected' : ''; ?>>Checkbox</option>
                                                <?php
                                            }
                                            
                                        ?>
                                        
                                        
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="alignTop"><strong>Question Label *</strong></td>
                                <td>
                                    <textarea style="width:100%;" name="label"><?php echo $question['label']; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Required * </strong></td>
                                <td>
                                    <input type="radio" value="1" name="required" <?php echo $question['required'] ? 'checked' : ''; ?> />Yes
                                    &nbsp; &nbsp;
                                    <input type="radio" value="0" name="required" <?php echo !$question['required'] ? 'checked' : ''; ?> />No
                                </td>
                            </tr>
                            <?php 
                                if($question['type'] == 'text' || $question['type'] == 'paragraph') {
                                    ?>
                                        <tr>
                                            <td style="width:32%"><strong>Max Word Limit * </strong></td>
                                            <td>                        
                                                <select name="max">
                                                    <?php for($i=50;$i<=300;$i+=50) {
                                                        echo '<option value="'.$i.'"';
                                                        echo $question['max'] == $i ? 'selected' : '';
                                                        echo '>'.$i.'</option>';
                                                    } ?>
                                                </select>                                                                                         
                                            </td>
                                        </tr>
                                    <?php
                                } else {
                                    $objRecruitment = new Recruitment();
                                    $choices = $objRecruitment->getQuestionChoices($question['id']);
                                    ?>
                                        <tr>
                                            <td style="width:32%" class=" alignTop"><strong>Question Choices *</strong></td>
                                            <td>                                               
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%" class="choices" data-object="choice">
                                                    <tr>
                                                        <th style="width:5px;"  class="borderRight">+</th>
                                                        <th colspan="2">Content</th>
                                                    </tr>
                                                    <tbody class="choiceList changeOrder">
                                                <?php
                                                foreach($choices as $choice) {
                                                    ?>
                                                        <tr id="choice-<?php echo $choice['id']; ?>" data-id="<?php echo $choice['id']; ?>">
                                                            <td class="borderRight">+</td>
                                                            <td><input type="text" style="width:100%;" value="<?php echo $choice['label']; ?>" class="existingChoice" /></td><td style="width:5px;"><span href="#" class="confirmRemove clickable <?php echo count($choices) > 2 ? '' : 'disabled'; ?> "><strong>X</strong></span></td>
                                                        </tr>
                                                    <?php
                                                }
                                                ?>
                                                    </tbody>
                                                    <tr>
                                                        <td colspan="3" style="text-align:center;"><a href="#" class="addChoice">Add choice</a></td>
                                                    </tr>
                                                </table>                                                                                                            
                                            </td>
                                        </tr>
                                    <?php
                                } 
                            ?>
                            
                        </table>
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn editQuestionBtn" value="Save changes" />
                        </label>
                        <br /><br />
                    </form>

                
                <?php
            
            } else {
            
                
                
                ?>
                
                    <h2 class=" borderBottom">Add Question</h2>
                    
                    
                    <div class="instruction">
                        <span class="instructionTitle">INSTRUCTION</span>
                        - <strong>Only</strong> add questions related to the position, the team, or the project.<br />
                        - <strong>Do not</strong> add questions related to applicant's basic information, contacts, education, or past experiences.
                    </div>
                    
                    <form action="" method="" class="addQuestionForm">
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%">
                            <tr>
                                <td><strong>Question Type *</strong></td>
                                <td>
                                    <select class="questionType" name="type">
                                        <option value="text">Short Text</option>
                                        <option value="paragraph">Paragraph</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="radio">Radio Buttons</option>
                                        <option value="checkbox">Checkbox</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="alignTop"><strong>Question Label *</strong></td>
                                <td>
                                    <textarea style="width:100%;" name="label"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Required * </strong></td>
                                <td>
                                    <input type="radio" value="1" name="required" checked=""  />Yes
                                    &nbsp; &nbsp;
                                    <input type="radio" value="0" name="required" />No
                                </td>
                            </tr>
                            <tr class="withLimit">
                                <td style="width:32%"><strong>Max Word Limit *</strong></td>
                                <td>                                    
                                    <select name="max">
                                        <?php for($i=50;$i<=300;$i+=50) {
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        } ?>
                                    </select>                                   
                                </td>
                            </tr>
                            <tr class="withChoice" style="display:none;" >
                                <td style="width:32%" class=" alignTop"><strong>Question Choices *</strong></td>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%" class="choices">
                                        <tr>
                                            <th style="width:5px;"  class="borderRight">+</th>
                                            <th colspan="2">Content</th>
                                        </tr>
                                        <tbody class="choiceList changeOrder">

                                            <tr>
                                                <td class="borderRight">+</td>
                                                <td><input type="text" style="width:100%;" /></td><td style="width:5px;"><span href="#" class="removeChoice clickable disabled"><strong>X</strong></span></td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="borderRight">+</td>
                                                <td><input type="text" style="width:100%;" /></td><td style="width:5px;"><span href="#" class="removeChoice clickable disabled"><strong>X</strong></span></td>
                                            </tr>
                                            
                                            
                                        </tbody>
                                        
                                        <tr>
                                            <td colspan="3" style="text-align:center;"><a href="#" class="addChoice">Add choice</a></td>
                                        </tr>
                                    </table>
                                   
                                </td>
                            </tr>
                        </table>
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn addQuestion" value="Add question" />
                        </label>
                        <br /><br />
                    </form>
                
                <?php
            }
            
            
            
        ?>
        