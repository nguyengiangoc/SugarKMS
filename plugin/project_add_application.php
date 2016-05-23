        <?php
            
            $objPage = new Page();
            $objProject = new Project();
                
            if(!isset($data['params'])) {
                $project = $data['project'];                
                $id = $project['id'];
            } else {
                $id = $data['params']['id'];
                $project = $objProject->getProjectById($id);
            }

            $objPosition = new Position();
            $allPositions = $objPosition->getAllPositionsInProject($project['project_type_id']);
        
        ?>
        
        <div class="sectionParams" data-params="id=<?php echo $id; ?>">
        </div>


        <form id="addApplicationForm">
             <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:15px;">
                <tr>                    
                    <td>Position</td>
                    <td>
                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $project['id']; ?>" />
                        <select class="selectPosition" style="width:140px;" name="position_id" data-url="/sugarkms/mod/getTeamsForPosition.php">
                            <option value="" class="positionOptionBlank"></option>
                            <?php foreach($allPositions as $position) {
                                echo '<option value="'.$position['id'].'" >'.$position['name'].'</option>';
                            } ?>
                        </select>                    
                    </td>
                </tr>
                <tr >
                    <td>Team</td>
                    <td>
                        <select class="selectTeam" disabled="" style="width:120px;" name="team_id" disabled="">
                            <option value="" class="teamOptionBlank"></option>
                            
                        </select>
                    </td>
                </tr>
                <!--
<tr>
                    <td>Number of vacancies</td>
                    <td>
                        <select class="" style="width:50px;" name="number_of_positions">
                            <option></option>
                            <?php for($i=1;$i<10;$i++) { ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php } ?>
                        </select>
                    
                    </td>
                </tr>
-->
                <tr>
                    <td>Deadline</td>
                    <td>
                        23:59:59,
                        <strong></strong> &nbsp;
                        <input readonly type="text" class="datepicker" style="width:80px;margin-top:-1px;" name="deadline"/>
                    </td>
                </tr>
                <!--
<tr>
                    <td>Requirements</td>
                    <td>
                        <textarea rows="4" cols="30" name="requirements"></textarea>
                    
                    </td>
                </tr>
                <tr>
                    <td>Duties</td>
                    <td>
                        <textarea rows="4" cols="30" name="duties"></textarea>
                    
                    </td>
                </tr>
-->
             </table>
             <label for="btn" class="sbm sbm_blue">
                <input type="submit" class="btn" value="Add" id="addApplicationBtn" />
            </label>
         </form>

        
