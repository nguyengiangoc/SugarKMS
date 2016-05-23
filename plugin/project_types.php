    <?php
        if(!empty($data)) {
            $project_types = $data['project_types'];
            $objProject = $data['objProject'];
        } else {
            $objProject = new Project();
            $project_types = $objProject->getAllProjectsForList();
        }
        
    ?>
        
        
        <h2>Project Types</h2>
        <form id="addTypeForm">    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
            <tr>
                <th class="borderRight">Name</th>
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
            <tr data-id="<?php echo $type['id']; ?>" > 
                <td class=" borderRight showInputField">
                    <span class="position-<?php echo $type['id']; ?>" data-id="#position-<?php echo $type['id']; ?>" style="font-weight:bold;"><?php echo $type['name']; ?></span>
                </td>
                <td >
                    <a href="#"></a><?php echo $type['wave'] ? 'Yes' : 'No'; ?>
                </td>
                <td class="  showSelect">
                    <span><?php echo $type['month_start'] ; ?></span>
                </td>
                <td class="  showSelect">
                    <span><?php echo $type['month_end']; ?></span>
                </td>
                <td >
                
                    <a href="#"></a>
                        <?php 
                            switch($type['same_start_end']) {
                                case '':
                                echo '';
                                break;
                                
                                case '1':
                                echo 'Yes';
                                break;
                                
                                case '0':
                                echo 'No';
                                break;       
                            } 
                        
                        ?>
                </td>
                <td >
                
                    <a href="#"></a>
                        <?php 
                            switch($type['write_two_years']) {
                                case '':
                                echo '';
                                break;
                                
                                case '1':
                                echo 'Yes';
                                break;
                                
                                case '0':
                                echo 'No';
                                break;       
                            } 
                        
                        ?>
                        
                </td>
                <td class="  showSelect">
                    <span><?php echo $type['first_time']; ?></span>
                </td>
                <td >
                    <a class="confirmRemove <?php echo $disabled; ?>" href="#">Remove</a>
                </td>
            </tr>
        <?php 
                
            } 
        ?>
            <tr class="formRow" >
                <td >
                    <input id="name" name="name" type="text" style="width:100%;box-sizing: border-box;"/>
                </td>
                <td >
                    <select style="width:100%;" name="wave" id="wave">
                        <option id="inWavesOptionBlank"></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td >
                    <select id="month_start" style="width:100%;" name="month_start" >
                        <option id="monthStartOptionBlank"></option>
                        <?php for($i=1;$i<13;$i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td >
                    <select id="month_end" style="width:100%;" name="month_end">
                        <option id="monthEndOptionBlank"></option>
                        <?php for($i=1;$i<13;$i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td >
                    <select style="width:100%;" id="same_start_end" name="same_start_end">
                        <option id="sameYearOptionBlank"></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td >
                    <select style="width:100%;" id="write_two_years" name="write_two_years" disabled=""> 
                        <option id="writeTwoOptionBlank"></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td >
                    <select id="first_time" style="width:100%;" name="first_time">
                        <option id="firstTimeOptionBlank"></option>
                        <?php for($i=date("Y");$i>2008;$i--) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td >
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn" value="Add" id="addProjectTypeBtn" style="padding:0 10px;"/>
                    </label>
                </td>
            
            </tr>
        </table>
        </form>
        <div style="height:25px;"></div>