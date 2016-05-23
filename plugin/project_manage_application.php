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
            
            $objApplication = new Application();
            $organizer_applications = $objApplication->getApplicationForProject($id);
            $volunteer_applications = $objApplication->getApplicationForProject($id, true);
            $objPosition = new Position();
            $allPositions = $objPosition->getAllPositionsInProject($project['project_type_id']);
        
        ?>
        
        <div class="sectionParams" data-params="id=<?php echo $id; ?>">
        </div>
        <h4>Organizer Application</h4>
        <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:25px;width:100%;" data-object="application">
            <tr>               
                <th>Position</th>
                <th>Team</th>
                <th>Deadline</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            <?php if(!empty($organizer_applications)) {
                foreach($organizer_applications as $application) {
                ?>
                    <tr data-id="<?php echo $application['id']; ?>">
                        <td><?php echo $application['position']; ?></td>
                        <td><?php echo $application['team']; ?></td>
                        <td><?php echo '23:59:59, '.date('d/m/Y', strtotime($application['deadline'])); ?></td>
                        <td><?php echo $application['published'] ? 'Published' : 'Unpublished';  ?></td>
                        <td><a href="<?php echo $objPage->generateURL('application', array('id' => $application['id'])); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo $objPage->generateURL('application', array('id' => $application['id'], 'action' => 'edit')); ?>" target="_blank">Edit</a></td>
                        <td><a href="#" class="confirmRemove">Remove</a></td>
                    </tr>
                <?php                    
                }
                
            } else { ?>
                <tr>               
                    <td colspan="6" style="text-align:center;">No application added to this project yet.</td>
                </tr>
            <?php } ?>
            
        </table>

        <h4>Volunteer Application</h4>
        <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:15px;width:100%;" data-object="application">
            <tr>               
                <th>Position</th>
                <th>Team</th>
                <th>Deadline</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            <?php if(!empty($volunteer_applications)) {
                foreach($volunteer_applications as $application) {
                ?>
                    <tr data-id="<?php echo $application['id']; ?>">
                        <td><?php echo $application['position']; ?></td>
                        <td><?php echo $application['team']; ?></td>
                        <td><?php echo '23:59:59, '.date('d/m/Y', strtotime($application['deadline'])); ?></td>
                        <td><?php echo $application['published'] ? 'Published' : 'Unpublished';  ?></td>
                        <td><a href="<?php echo $objPage->generateURL('application', array('id' => $application['id'])); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo $objPage->generateURL('application', array('id' => $application['id'], 'action' => 'edit')); ?>" target="_blank">Edit</a></td>
                        <td><a href="#" class="confirmRemove">Remove</a></td>
                    </tr>
                <?php                    
                }
                
            } else { ?>
                <tr>               
                    <td colspan="6" style="text-align:center;">No application added to this project yet.</td>
                </tr>
            <?php } ?>
            
        </table>

        
