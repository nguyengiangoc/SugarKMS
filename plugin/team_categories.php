    <?php
        if(!isset($data['params'])) {
            $teams = $data['teams'];
            $objTeam = $data['objTeam'];
        } else {
            $objTeam = new Team();
            $teams = $objTeam->getAllTeams();
        }
        
    ?>
        
        
        <h2>Catergories 
            <!--
<span style="float:right;font-size:12px;cursor:pointer;"><a href="#" id="addTeam"><u>Add Team</u></a></span>
-->
        </h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" data-object="team">
            <tr>
                <th style="border-right:dashed 1px #222;width:120px;">Name</th>
                <th style="width:50px;">EXCO</th>
                <th style="width:50px;">Project</th>
                <th>Action</th>
            </tr>
        <?php 
            foreach($teams as $team) { 
                $disabledEXCO = '';
                $disabledProject = '';
                $disabled = '';
                if($team['exco'] == '1') {
                    $disabledEXCO = $objTeam->checkTeamExistsInInvolvements($team['id'],true);
                    
                }
                if($team['project'] == '1') {
                    $disabledProject = $objTeam->checkTeamExistsInInvolvements($team['id']);
                }
                if(!empty($disabledEXCO) || !empty($disabledProject)) {
                    $disabled = 'disabled';
                }
                
        ?>
            <tr data-id="<?php echo $team['id']; ?>"> 
                <td class=" borderRight clickable showInputField" data-field="name">
                    <span style="font-weight:bold;"><?php echo $team['name']; ?></span>
                </td>
                
                <td ><a href="#" class="clickYesNo <?php echo $disabledEXCO; ?>"  data-type="exco" data-value="<?php echo intval($team['exco']); ?>"><?php echo $team['exco'] ? 'Yes' : 'No'; ?></a></td>
                <td ><a href="#" class="clickYesNo <?php echo $disabledProject; ?>"  data-type="project" data-value="<?php echo intval($team['project']); ?>"><?php echo $team['project']? 'Yes' : 'No'; ?></a></td>
                <td >
                    <a href="#" class="confirmRemove <?php echo $disabled; ?>">Remove</a></td>
            </tr>
        <?php 
                
            } 
        ?>
            <tr id="addTeamForm" class="formRow" >
                <td >
                    <input id="teamNameField" name="name" type="text" style="width:100%;box-sizing: border-box;"/>
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
                        <input type="submit" class="btn" value="Add" id="addTeamBtn" style="padding:0 10px;"/>
                    </label>
                </td>
            </tr>
        </table>
        <div style="height:25px;"></div>