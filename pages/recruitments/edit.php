<?php 
    //var_dump($recruitment);
    $header = 'Recruitment - '.$recruitment['abbr'].', '.$recruitment['position'].', '.$recruitment['team'].' Team :: Edit';
    require_once('_header.php'); 
    $objPage = new Page();
    
?>
        <?php //var_dump($recruitment); ?>
        <h1>
            <?php echo $header; ?>
            <a class="h2rightlink" href="<?php echo $objPage->generateURL('recruitment', array('id' => $id));?>">View this recruitment</a>
        </h1>  
        
                   
        <div class="tabs">
            <ul>    
                <li><a href="#status">Settings</a></li>
                <li><a href="#description">Job Description</a></li>
                <li><a href="#questions">Questions</a></li>
            </ul>
            <div class="dev borderTop"></div>
            <div id="status">              
                <div class="reloadSection settingsSection" data-plugin="recruitment_settings">
                    <?php echo Plugin::get('recruitment_settings', array('id' => $id)); ?> 
                </div>                
            </div>
            <div id="description">
                <div class="reloadSection" data-plugin="recruitment_jd">
                    <?php echo Plugin::get('recruitment_jd', array('id' => $id)); ?> 
                </div>  
            </div>
            
            <div id="questions">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                    <tr>
                        <td style="width:45%;" class="alignTop">
                            
                            <div class="reloadSection questionListSection" data-plugin="recruitment_question_list">
                                <?php echo Plugin::get('recruitment_question_list', array('id' => $id));  ?>
                            </div>                    
                        </td>
                        <td style="width:0%;"></td>
                        <td class="alignTop" style="width:50%;"> 
                            <div class="reloadSection editQuestionSection" data-plugin="recruitment_question_edit">
                                <?php echo Plugin::get('recruitment_question_edit', array('params' => array('recruitment_id' => $id)));  ?>
                            </div>  
                        </td>
                        
                        
                        
                    </tr>
                    
                    
        
                </table>
            </div>
        </div>
        
        
        
        
        

<?php 
    require_once('_footer.php'); 
?>