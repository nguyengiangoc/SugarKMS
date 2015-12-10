    <?php
        $positions = $data['positions'];
        $objPosition = $data['objPosition'];
    ?>
        
        
        <h2>Catergories</h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
            <tr>
                <th style="border-right:dashed 1px #222;width:120px;">Name</th>
                <th style="width:50px;">EXCO</th>
                <th style="width:50px;">Project</th>
                <th>Action</th>
            </tr>
        <?php 
            foreach($positions as $position) { 
                $disabledEXCO = '';
                $disabledProject = '';
                $disabled = '';
                if($position['exco'] == 'Yes') {
                    $disabledEXCO = $objPosition->checkPositionExistsInInvolvements($position['id'],true);
                    
                }
                if($position['project'] == 'Yes') {
                    $disabledProject = $objPosition->checkPositionExistsInInvolvements($position['id']);
                }
                if(!empty($disabledEXCO) || !empty($disabledProject)) {
                    $disabled = 'disabled';
                }
                
        ?>
            <tr> 
                <td class="br_td br_right clickable showInputField">
                    <input id="position-<?php echo $position['id']; ?>" type="text" value="<?php echo $position['name']; ?>" style="display:none;width:100%;box-sizing: border-box;padding:0;" class="hideInputField" />
                    <span class="position-<?php echo $position['id']; ?>" data-id="#position-<?php echo $position['id']; ?>" style="font-weight:bold;"><?php echo $position['name']; ?></span>
                </td>
                <td class="br_td"><a href="#" class="changeCategory <?php echo $disabledEXCO; ?>"  data-type="exco"><?php echo $position['exco']; ?></a></td>
                <td class="br_td"><a href="#" class="changeCategory <?php echo $disabledProject; ?>"  data-type="project"><?php echo $position['project']; ?></a></td>
                <td class="br_td">
                    <a href="#" class="removePosition <?php echo $disabled; ?>">Remove</a></td>
            </tr>
        <?php 
                
            } 
        ?>
            <tr id="addPositionForm" >
                <td class="br_td">
                    <input id="positionNameField" name="name" type="text" style="width:100%;box-sizing: border-box;"/>
                </td>
                <td class="br_td">
                    <select style="width:100%;" id="EXCOSelect" name="exco">
                        <option id="EXCOOptionBlank"></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </td>
                <td class="br_td">
                    <select style="width:100%;" id="projectSelect" name="project">
                        <option id="projectOptionBlank"></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </td>
                <td class="br_td">
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn" value="Add" id="addPositionBtn" style="padding:0 10px;" data-url="/sugarkms/mod/addPosition.php"/>
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
            </tr>
        </table>
        <div style="height:25px;"></div>