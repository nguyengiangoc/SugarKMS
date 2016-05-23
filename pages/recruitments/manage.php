<?php 
        
        $header = 'Recruitment :: Manage';
        require_once('_header.php'); 
        $objProject = new Project();
        
        $projects = $objProject->getUpcomingProjects();
?>
    <h1><?php echo $header; ?></h1>

    <h2>Current Recruitment</h2>          

                    
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" data-object="recruitment">
            <tr>
                <th>Project</th>
                <th>Position</th>
                <th>Team</th>
                <th>Deadline</th>
                <th>Status</th>
                <th colspan="2">Action</th>
            </tr>
            <tbody class="recruitmentList reloadSection" data-plugin="recruitment_current">
                <?php echo Plugin::get('recruitment_current');   ?>
                
            </tbody>
            

            
            
        </table>


        
        

    
    <form class="addPositionForm" action="" method="">            

                    
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" data-object="recruitment">
            <tr>
                <th>Project</th>
                <th>Position</th>
                <th>Team</th>
                <th>Deadline</th>
                <th colspan="2">Action</th>
            </tr>
            <tr>
                <td>
                    <select class="selectProject" style="width:205px;" name="project_id">
                        <option class="projectOptionBlank"></option>
                        <?php foreach($projects as $project) { ?>
                            <option value="<?php echo $project['id'] ?>" data-type="<?php echo $project['project_type_id'] ?>" >
                                <?php echo $project['name'].' '.$project['project_time']; ?>
                            </option>
                        <?php } ?>
                    </select>                                              
                </td>
                <td>
                    <select class="selectPosition" style="width:140px;" name="position_id" disabled="">
                        <option></option>
                    </select>
                </td>
                <td>
                    <select class="selectTeam" disabled="" style="width:120px;" name="team_id">
                        <option></option>
                        
                    </select>
                </td>
                <td>
                    <input readonly type="text" class="datepicker position_deadline" style="width:70px;" name="deadline"/>
                </td>
                <td >
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn addPositionToRecruitment" value="Add" />
                    </label>
                </td>
                <td colspan="2">
                
                </td>
            </tr>

            
            
        </table>

        
        

        
        
    </form>
    
    
    
    
    

<?php 
        require_once('_footer.php');
    
?>