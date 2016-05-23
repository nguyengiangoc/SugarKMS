<?php 
    
    $header = 'Application for '.$application['position'].', '.$application['team'].' Team, '.$application['project'].' :: View';
    require_once('_header.php'); 
    $questions = $objApplication->getQuestionsForApplication($id);
    
?>
        <?php //var_dump($application); ?>
        <h1>
            <?php echo $header; ?>
            <span class="h2rightlink">
                <a href="<?php echo $this->objPage->generateURL('application', array('id' => $application['id'], 'action' => 'edit')); ?>">Edit this application</a>
            </span>
        </h1>     
        <div class="tabs">
            <ul>
                <li><a href="#submissions">Submissions</a></li>
                <li><a href="#details">Details</a></li>
            </ul>
            <div class="dev borderTop"></div>

            <div id="submissions" >
                <?php if(!$application['published']) { ?>
                    This application is not accepting any submission because it has not been published.
                <?php } else { ?>
                    
                <?php } ?>
            
            </div>

            <div id="details">
                <div class="fl_l">
                    <h2>Description</h2>
                    <table cellpadding="0" cellspacing="0" border="0" >
                        <tr>
                            <td><strong>Position</strong></td>
                            <td><?php echo $application['position']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Team</strong></td>
                            <td><?php echo $application['team']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Project</strong></td>
                            <td><?php echo $application['project']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Number of vacancies</strong></td>
                            <td><?php echo $application['number_of_vacancies']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Deadline</strong></td>
                            <td><?php echo date('H:i:s, d-m-Y', strtotime($application['deadline'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <?php if(!$application['published']) {
                                    ?>
                                    Unpublished
                                    <?php
                                } else {
                                    if(strtotime($application['deadline']) < time()) {
                                        ?>
                                        Published, Opening
                                        <?php
                                    } else {
                                        ?>
                                        Published, Closed
                                        <?php
                                    }
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Requirements</strong></td>
                            <td><?php echo $application['requirements']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Duties</strong></td>
                            <td><?php echo $application['duties']; ?></td>
                        </tr>
                    </table>
                </div>
                
                <div style="margin-left:40%;">
                    <h2>Questions</h2>
                    <?php if(empty($questions)) { ?>
                        No question added to this application yet.
                    <?php } else { ?>
                    
                    <?php } ?>  
                </div>
                                        
            </div>

            
            

        </div>
<?php 
    require_once('_footer.php'); 
?>