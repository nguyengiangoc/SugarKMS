        <?php
            if(!isset($data['params'])) {
                $teamsEXCO = $data['teamsEXCO'];
                $teamsProject = $data['teamsProject'];
            } else {
                $objTeam = new Team();
                $teamsProject = $objTeam->getAllTeamsInProject(6);
                $teamsEXCO = $objTeam->getAllTeamsInProject(5);
            }
            
        ?>
        
        
        
        <h2>Display Order</h2>
        
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="margin-bottom:0px;display:inline;" data-object="team" >
            
            <tr>
                <th class=" borderRight">Name</th>
                <th class="">EXCO Order</th>                
            </tr>
            <tbody id="exco_order" class="changeOrder" >
        <?php foreach($teamsEXCO as $teamEXCO) { ?>
                <tr id="exco-<?php echo $teamEXCO['id']; ?>"> 
                    <td class=" borderRight" style="font-weight:bold;"><?php echo $teamEXCO['name']; ?></td>
                    <td class=""><?php echo $teamEXCO['exco_order']; ?></td>
                </tr>
        <?php } ?>
            </tbody>
        </table>
        
        <div style="width:15px;display:inline-block;" ></div>
        
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat"  style="margin-bottom:0px;display:inline;" data-object="team">
            <tr>
                <th class=" borderRight">Name</th>
                <th class="">Project Order</th>                
            </tr>
            <tbody id="project_order" class="changeOrder"> 
        <?php foreach($teamsProject as $teamProject) { ?>
            <tr id="project-<?php echo $teamProject['id']; ?>"> 
                <td class=" borderRight" style="font-weight:bold;"><?php echo $teamProject['name']; ?></td>
                <td class=""><?php echo $teamProject['project_order']; ?></td>
            </tr>
        <?php } ?>
            </tbody>
        </table>
        <div style="height:25px;"></div> 