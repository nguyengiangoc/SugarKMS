    <?php
        $objRecruitment = new Recruitment();
    ?>

    <h2>Applications for Current Recruitments</h2>
    
    <?php 
        $applications = $objRecruitment->getApplicationsForCurrentRecruitment();
        //print_r($applications);
    ?>
     
     
    
    <?php
        
        foreach($applications as $project) {
            ?>
            <h6><?php echo strtoupper($project['project']); ?></h6>
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%">
                <tr>
                    <th class="borderRight"></th>
                    <th>Pending</th>
                    <th>Selected For Interview</th>
                    <th class="borderRight">Accepted</th>
                    <th>Rejected</th>
                </tr>
                <?php
                    foreach($project['teams'] as $team) {
                        ?>
                        <tr class="groupRow">
                            <td colspan="5"><strong><?php echo strtoupper($team['team']); ?> TEAM</strong></td>
                        </tr>
                        <?php
                        foreach($team['positions'] as $position) {
                            ?>
                            <tr>
                                <td class="borderRight"><strong><?php echo $position['position']; ?></strong></td>
                                <?php
                                    foreach($position['status'] as $status) {
                                        ?>
                                        <td style="width:140px;" <?php echo $status['id'] == 3 ? 'class="borderRight"' : ''; ?>><a href="#">List (<?php echo $status['count']; ?>)</a></td>
                                        <?php
                                    }
                                ?>
                            </tr>
                            <?php
                        }
                    }
                ?>
                
            </table>
            <?php
        }
    
    ?>