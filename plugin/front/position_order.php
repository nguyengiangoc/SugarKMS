        <?php
            $positionsEXCO = $data['positionsEXCO'];
            $positionsProject = $data['positionsProject'];
        ?>
        
        
        
        <h2>Display Order</h2>
        
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="margin-bottom:0px;display:inline;" >
            
            <tr>
                <th class="br_btm br_right">Name</th>
                <th class="br_btm">EXCO Order</th>                
            </tr>
            <tbody id="orderEXCO" data-type="exco" >
        <?php foreach($positionsEXCO as $positionEXCO) { ?>
                <tr id="exco-<?php echo $positionEXCO['id']; ?>"> 
                    <td class="br_btm br_right" style="font-weight:bold;"><?php echo $positionEXCO['name']; ?></td>
                    <td class="br_btm"><?php echo $positionEXCO['exco_order']; ?></td>
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
        <?php foreach($positionsProject as $positionProject) { ?>
            <tr id="project-<?php echo $positionProject['id']; ?>"> 
                <td class="br_btm br_right" style="font-weight:bold;"><?php echo $positionProject['name']; ?></td>
                <td class="br_btm"><?php echo $positionProject['project_order']; ?></td>
            </tr>
        <?php } ?>
            </tbody>
        </table>
        <div style="height:25px;"></div> 