<?php 
    //var_dump($recruitment);
    $header = 'Recruitment - '.$recruitment['abbr'].', '.$recruitment['position'].', '.$recruitment['team'].' Team :: View';
    require_once('_header.php'); 
    $questions = $objRecruitment->getQuestionsForRecruitment($id);
    $objPage = new Page();
    
?>
        <h1>
            <?php echo $header; ?>
            <a class="h2rightlink" href="<?php echo $objPage->generateURL('recruitment', array('id' => $id, 'action' => 'edit'));?>">Edit this recruitment</a>
        </h1>  
        
        
        
        <table cellpadding="0" cellspacing="0" border="0"  style="width:100%;">
            <tr>
                <td colspan="3" style="width:100%;" class="borderBottom">
                    <h2>Settings</h2>
                   
                    <table cellpadding="0" cellspacing="0" border="0" >
                    
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><?php echo $recruitment['published'] ? 'Published' : '<span class="red">Unpublished</span>'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Deadline</strong></td>
                            <td><?php echo date('d-m-Y', strtotime($recruitment['deadline'])); ?></td>
                            
                        </tr>                               
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:47%;" class="alignTop">
                <br />
                    <h2>Job Description</h2>
        
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >        
                    
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
                <td  class="alignTop" style="width:47%;">
                <br />
                    <h2>Questions</h2>
        
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                        
                        <?php 
                            if(count($questions) > 0) {
                                foreach($questions as $question) {
                                    ?>
                                        <tr id="row-<?php echo $question['id']; ?>" data-id="<?php echo $question['id']; ?>"> 
                                            
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
                                                            echo '<input type="text" style="width:95%;" />';
                                                        break;
                                                        
                                                        case 'paragraph':
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
                </table>
                </td>
            </tr>
                                          
        </table>
        
        
        
        
        
        
        
        
        
        

<?php 
    require_once('_footer.php'); 
?>