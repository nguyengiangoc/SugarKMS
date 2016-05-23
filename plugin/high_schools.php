    <?php
        if(!isset($data['params'])) {
            $schools = $data['schools'];
            $objSchool = $data['objSchool'];
        } else {
            $objSchool = new School();
            $schools = $objSchool->getAllHighSchools();
        }
        
    ?>
        
        
            
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" data-object="high_school">
            <tr>
                <th class="borderRight">Name</th>
                <th>Abbreviations</th>
                
            </tr>
        <?php 
            foreach($schools as $school) {
                $disabled = $objSchool->checkSchoolInMembers($school['id'], true);
                $abbr = $objSchool->getSchoolAbbr($school['id'], true);
        ?>
            <tr data-id="<?php echo $school['id']; ?>"> 
                <td class=" borderRight showInputField clickable" data-field="name">
                    <span style="font-weight:bold;"><?php echo $school['name']; ?></span>
                </td>
                
                <td >
                    <a href="#"></a>
                    <?php foreach($abbr as $a) { ?>
                        <div class="inline_tabs"><?php echo $a['abbr']; ?> 
                            <div style="margin-left:3px;display:inline-block;cursor:pointer;" class="removeAbbr" data-id="<?php echo $a['id']; ?>">
                                <img src="/sugarkms/images/x-button2.png" style="height:9px;"/>
                            </div>
                        </div>&nbsp;                        
                    <?php } ?>
                    <a href="#" class="addAbbrForm">Add</a>
                </td>
                
            </tr>
        <?php 
                
            } 
        ?>
            <!--
<tr >
            <form id="addHighSchoolForm">
                <td >
                    <input id="name" name="name" type="text" style="width:100%;box-sizing: border-box;"/>
                </td>
                <td >
                    
                </td>
                <td >
                    <label for="btn" class="sbm sbm_blue">
                        <input type="submit" class="btn" value="Add" id="addHighSchoolBtn" style="padding:0 10px;" data-url="/sugarkms/mod/addProjectType.php"/>
                    </label>
                </td>
            </form>
            </tr>
-->
            <tr>
                <td colspan="8" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
            </tr>
        </table>
        <div style="height:25px;"></div>