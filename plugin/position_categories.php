    <?php
        if(!isset($data['params'])) {
            $positions = $data['positions'];
            $objPosition = $data['objPosition'];
        } else {
            $objPosition = new Position();
            $positions = $objPosition->getAllPositions();
        }
        
    ?>
        
        
        <h2>Catergories</h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" data-object="position" >
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
                if($position['exco'] == '1') {
                    $disabledEXCO = $objPosition->checkPositionExistsInInvolvements($position['id'],true);
                    
                }
                if($position['project'] == '1') {
                    $disabledProject = $objPosition->checkPositionExistsInInvolvements($position['id']);
                }
                if(!empty($disabledEXCO) || !empty($disabledProject)) {
                    $disabled = 'disabled';
                }
                
        ?>
            <tr data-id="<?php echo $position['id']; ?>"> 
                <td class=" borderRight clickable showInputField" data-field="name">
                    <span style="font-weight:bold;"><?php echo $position['name']; ?></span>
                </td>
                
                <td ><a href="#" class="toggleYesNo <?php echo $disabledEXCO; ?>"  data-field="exco" data-value="<?php echo intval($position['exco']); ?>"><?php echo $position['exco'] ? 'Yes' : 'No'; ?></a></td>
                <td ><a href="#" class="toggleYesNo <?php echo $disabledProject; ?>"  data-field="project" data-value="<?php echo intval($position['project']); ?>"><?php echo $position['project'] ? 'Yes' : 'No'; ?></a></td>
                <td >
                    <a href="#" class="confirmRemove <?php echo $disabled; ?>">Remove</a></td>
            </tr>
        <?php 
                
            } 
        ?>
            <tr id="addPositionForm" class="formRow" >
                <td >
                    <input id="positionNameField" name="name" type="text" />
                </td>
                <td >
                    <select style="width:100%;" id="EXCOSelect" name="exco">
                        <option id="EXCOOptionBlank"></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td >
                    <select style="width:100%;" id="projectSelect" name="project">
                        <option id="projectOptionBlank"></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td >
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn" value="Add" id="addPositionBtn" style="padding:0 10px;"/>
                    </label>
                </td>
            </tr>
        </table>
        <div style="height:25px;"></div>