    <?php
        $teams = $data['teams'];
        $objTeam = $data['objTeam'];
    ?>
        
        
        <h2>Catergories 
            <!--
<span style="float:right;font-size:12px;cursor:pointer;"><a href="#" id="addTeam"><u>Add Team</u></a></span>
-->
        </h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
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
                if($team['exco'] == 'Yes') {
                    $disabledEXCO = $objTeam->checkTeamExistsInInvolvements($team['id'],true);
                    
                }
                if($team['project'] == 'Yes') {
                    $disabledProject = $objTeam->checkTeamExistsInInvolvements($team['id']);
                }
                if(!empty($disabledEXCO) || !empty($disabledProject)) {
                    $disabled = 'disabled';
                }
                
        ?>
            <tr> 
                <td class="br_td br_right clickable showInputField">
                    <input id="team-<?php echo $team['id']; ?>" type="text" value="<?php echo $team['name']; ?>" style="display:none;width:100%;box-sizing: border-box;padding:0;" class="hideInputField" />
                    <span class=" team-<?php echo $team['id']; ?>" data-id="#team-<?php echo $team['id']; ?>" style="font-weight:bold;"><?php echo $team['name']; ?></span>
                </td>
                <td class="br_td"><a href="#" class="clickYesNo <?php echo $disabledEXCO; ?>"  data-type="exco"><?php echo $team['exco']; ?></a></td>
                <td class="br_td"><a href="#" class="clickYesNo <?php echo $disabledProject; ?>"  data-type="project"><?php echo $team['project']; ?></a></td>
                <td class="br_td">
                    <a href="#" class="removeTeam <?php echo $disabled; ?>">Remove</a></td>
            </tr>
        <?php 
                
            } 
        ?>
            <tr id="addTeamForm" >
                <td class="br_td">
                    <input id="teamNameField" name="name" type="text" style="width:100%;box-sizing: border-box;"/>
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
                        <input type="submit" class="btn" value="Add" id="addTeamBtn" style="padding:0 10px;" data-url="/sugarkms/mod/addTeam.php"/>
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
            </tr>
        </table>
        <div style="height:25px;"></div>