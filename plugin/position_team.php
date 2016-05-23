    <?php
        if(!isset($data['params'])) {
            $teamsEXCO = $data['teamsEXCO'];
            $teamsProject = $data['teamsProject'];
            $positionsEXCO = $data['positionsEXCO'];
            $positionsProject = $data['positionsProject'];
            $objPosition = $data['objPosition'];
        } else {
            $objPosition = new Position();
            $positionsEXCO = $objPosition->getAllPositionsInProject(5);
            $positionsProject = $objPosition->getAllPositionsInProject(6);
            
            $objTeam = new Team();
            $teamsProject = $objTeam->getAllTeamsInProject(6);
            $teamsEXCO = $objTeam->getAllTeamsInProject(5);
        }
        
    ?>
        
        
        <h2>Positions In Teams</h2>  
        <div style="overflow-x:scroll;white-space: nowrap">
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
                <tr>
                    <th style="border-right:dashed 1px #222;width:120px;">Name</th>
                    <?php foreach($teamsEXCO as $teamEXCO) { ?>
                        <th>
                           <?php echo $teamEXCO['name']; ?> 
                        </th>
                    <?php } ?>
                </tr>
            <?php 
                foreach($positionsEXCO as $positionEXCO) { 
                    
            ?>
                <tr> 
                    <td class=" borderRight">
                        <input id="team-<?php echo $positionEXCO['id']; ?>" type="text" value="<?php echo $positionEXCO['name']; ?>" style="display:none;width:100%;box-sizing: border-box;padding:0;" class="hideInputField" />
                        <span class="showInputField team-<?php echo $positionEXCO['id']; ?>" data-id="#team-<?php echo $positionEXCO['id']; ?>" style="font-weight:bold;"><?php echo $positionEXCO['name']; ?></span>
                    </td>
                    <?php foreach($teamsEXCO as $teamEXCO) { ?>
                        <td  id="<?php echo $positionEXCO['id'].'-'.$teamEXCO['id']; ?>">
                            <?php if($objPosition->checkPositionInTeam($positionEXCO['id'], $teamEXCO['id'])) { 
                                    $disabled = $objPosition->checkPositionTeamExistsInInvolvements($positionEXCO['id'], $teamEXCO['id']);
                            ?>
                                <a href="#" class="changePositionInTeam <?php echo $disabled; ?>" data-value="1">Yes</a>
                            <?php } else { ?>
                                <a href="#" class="changePositionInTeam" data-value="0">No</a>
                            <?php } ?>
                        </td>
                    <?php }?>
                </tr>
            <?php 
                    
                } 
            ?>
            </table>
        </div>
        
        <div style="height:25px;"></div>
         
        <div style="overflow-x:scroll;white-space: nowrap">
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
                <tr>
                    <th style="border-right:dashed 1px #222;width:120px;">Name</th>
                    <?php foreach($teamsProject as $teamProject) { ?>
                        <th>
                           <?php echo $teamProject['name']; ?> 
                        </th>
                    <?php } ?>
                </tr>
            <?php 
                foreach($positionsProject as $positionProject) { 
                    
            ?>
                <tr> 
                    <td class=" borderRight">
                        <input id="team-<?php echo $positionProject['id']; ?>" type="text" value="<?php echo $positionProject['name']; ?>" style="display:none;width:100%;box-sizing: border-box;padding:0;" class="hideInputField" />
                        <span class="showInputField team-<?php echo $positionProject['id']; ?>" data-id="#team-<?php echo $positionProject['id']; ?>" style="font-weight:bold;"><?php echo $positionProject['name']; ?></span>
                    </td>
                    <?php foreach($teamsProject as $teamProject) { ?>
                        <td  id="<?php echo $positionProject['id'].'-'.$teamProject['id']; ?>">
                            <?php 
                                if($objPosition->checkPositionInTeam($positionProject['id'], $teamProject['id'])) { 
                                    $disabled = $objPosition->checkPositionTeamExistsInInvolvements($positionProject['id'], $teamProject['id']);
                            ?>
                                <a href="#" class="changePositionInTeam <?php echo $disabled; ?>" data-value="1">Yes</a>
                            <?php } else { ?>
                                <a href="#" class="changePositionInTeam" data-value="0">No</a>
                            <?php } ?>
                        </td>
                    <?php }?>
                </tr>
            <?php 
                    
                } 
            ?>
            </table>
        </div> 
        