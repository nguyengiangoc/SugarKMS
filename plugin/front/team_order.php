        <?php
            $teamsEXCO = $data['teamsEXCO'];
            $teamsProject = $data['teamsProject'];
        ?>
        
        
        
        <h2>Display Order</h2>
        
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="margin-bottom:0px;display:inline;" >
            
            <tr>
                <th class="br_btm br_right">Name</th>
                <th class="br_btm">EXCO Order</th>                
            </tr>
            <tbody id="orderEXCO" data-type="exco" >
        <?php foreach($teamsEXCO as $teamEXCO) { ?>
                <tr id="exco-<?php echo $teamEXCO['id']; ?>"> 
                    <td class="br_btm br_right" style="font-weight:bold;"><?php echo $teamEXCO['name']; ?></td>
                    <td class="br_btm"><?php echo $teamEXCO['exco_order']; ?></td>
                </tr>
        <?php } ?>
            </tbody>
        </table>
        
        <div style="width:15px;display:inline-block;" ></div>
        
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat"  style="margin-bottom:0px;display:inline;">
            <tr>
                <th class="br_btm br_right">Name</th>
                <th class="br_btm">Project Order</th>                
            </tr>
            <tbody id="orderProject" data-type="project"> 
        <?php foreach($teamsProject as $teamProject) { ?>
            <tr id="project-<?php echo $teamProject['id']; ?>"> 
                <td class="br_btm br_right" style="font-weight:bold;"><?php echo $teamProject['name']; ?></td>
                <td class="br_btm"><?php echo $teamProject['project_order']; ?></td>
            </tr>
        <?php } ?>
            </tbody>
        </table>
        <div style="height:25px;"></div> 