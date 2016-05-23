        <?php 
            if(isset($data['params'])) {
                $params = $data['params'];
                $id = $params['id'];
            } else {
                $id = $data['id'];
            }
            
            
            
        ?>
        <div class="sectionParams" data-params="id=<?php echo $id; ?>"></div>
    
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:45%;" class="alignTop">
                    <h2>Add Question</h2>
                    
                    <p><strong>GUIDE</strong>:
                    <br />- <strong>DO NOT</strong> add questions related to applicant's basic information.</p>
                    
                    <form action="" method="" class="addQuestionForm">
                        <input type="hidden" value="<?php echo $id; ?>" name="recruitment_id" />
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
                            <tr>
                                <td><strong>Max Word Limit </strong></td>
                                <td>
                                    <div class="noLimit" style="display:none;" >
                                        <select disabled="">
                                            <option></option>
                                            <option>300</option>
                                        </select>
                                    </div>
                                    <div class="withLimit">
                                        <select name="max">
                                            <?php for($i=50;$i<=300;$i+=50) {
                                                echo '<option value="'.$i.'">'.$i.'</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td style="width:32%" class=" alignTop"><strong>Question Choices</strong></td>
                                <td class="questionChoices">
                                    <div class="noChoice">Not Available</div>
                                    <div class="withChoice" style="display:none;" >
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%" class="choices">
                                            <tr>
                                                <th style="width:5px;"  class="borderRight">+</th>
                                                <th colspan="2">Content</th>
                                            </tr>
                                            <tbody class="choiceList changeOrder">

                                                <tr>
                                                    <td class="borderRight">+</td>
                                                    <td><input type="text" style="width:100%;" /></td><td style="width:5px;"><span href="#" class="removeChoiceAdd clickable"><strong>X</strong></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="borderRight">+</td>
                                                    <td><input type="text" style="width:100%;" /></td><td style="width:5px;"><span href="#" class="removeChoiceAdd clickable"><strong>X</strong></span></td>
                                                </tr>
                                            </tbody>
                                            
                                            <tr>
                                                <td colspan="3" style="text-align:center;"><a href="#" class="addChoiceAdd">Add choice</a></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn addQuestion" value="Add question" />
                        </label>
                        <br /><br />
                    </form>
                    
                </td>
                <td style="width:0%;"></td>
                <td class="alignTop" style="width:50%;"> 
                    <h2>Current Questions</h2>
                    <div class="reloadSection questionList" data-plugin="recruitment_question_list">
                        <?php echo Plugin::get('recruitment_question_list', array('id' => $id));  ?>
                    </div>
                </td>
                
                
                
            </tr>
            
            

        </table>