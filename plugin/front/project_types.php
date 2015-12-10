    <?php
        $project_types = $data['project_types'];
        $objProject = $data['objProject'];
    ?>
        
        
        <h2>Project Types</h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
            <tr>
                <th class="br_right">Name</th>
                <th>In Waves</th>
                <th>Month Start</th>
                <th>Month End</th>
                <th>In Same Year</th>
                <th>Write Two Years</th>
                <th>First Time</th>
                <th>Action</th>
            </tr>
        <?php 
            foreach($project_types as $type) {
                $disabled = $objProject->checkProjectTypeInInvolvements($type['id']);
        ?>
            <tr> 
                <td class="br_td br_right showInputField">
                    <span class="position-<?php echo $type['id']; ?>" data-id="#position-<?php echo $type['id']; ?>" style="font-weight:bold;"><?php echo $type['name']; ?></span>
                </td>
                <td class="br_td">
                    <a href="#"></a><?php echo $type['wave']; ?>
                </td>
                <td class="br_td  showSelect">
                    <span><?php echo $type['month_start']; ?></span>
                </td>
                <td class="br_td  showSelect">
                    <span><?php echo $type['month_end']; ?></span>
                </td>
                <td class="br_td">
                
                    <a href="#"></a><?php echo $type['same_start_end']; ?>
                </td>
                <td class="br_td">
                
                    <a href="#"></a><?php echo $type['write_two_years']; ?>
                </td>
                <td class="br_td  showSelect">
                    <span><?php echo $type['first_time']; ?></span>
                </td>
                <td class="br_td">
                    <a class="removeType <?php echo $disabled; ?>" href="#" data-id="<?php echo $type['id']; ?>">Remove</a>
                </td>
            </tr>
        <?php 
                
            } 
        ?>
            <tr >
            <form id="addTypeForm">
                <td class="br_td">
                    <input id="name" name="name" type="text" style="width:100%;box-sizing: border-box;"/>
                </td>
                <td class="br_td">
                    <select style="width:100%;" name="wave" id="wave">
                        <option id="inWavesOptionBlank"></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </td>
                <td class="br_td">
                    <select id="month_start" style="width:100%;" name="month_start" >
                        <option id="monthStartOptionBlank"></option>
                        <?php for($i=1;$i<13;$i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="br_td">
                    <select id="month_end" style="width:100%;" name="month_end">
                        <option id="monthEndOptionBlank"></option>
                        <?php for($i=1;$i<13;$i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="br_td">
                    <select style="width:100%;" id="same_start_end" name="same_start_end">
                        <option id="sameYearOptionBlank"></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </td>
                <td class="br_td">
                    <select style="width:100%;" id="write_two_years" name="write_two_years" disabled=""> 
                        <option id="writeTwoOptionBlank"></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </td>
                <td class="br_td">
                    <select id="first_time" style="width:100%;" name="first_time">
                        <option id="firstTimeOptionBlank"></option>
                        <?php for($i=date("Y");$i>2008;$i--) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="br_td">
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn" value="Add" id="addProjectTypeBtn" style="padding:0 10px;" data-url="/sugarkms/mod/addProjectType.php"/>
                    </label>
                </td>
            </form>
            </tr>
            <tr>
                <td colspan="8" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
            </tr>
        </table>
        <div style="height:25px;"></div>