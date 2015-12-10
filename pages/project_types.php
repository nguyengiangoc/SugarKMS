<?php
    $header = 'Project Types :: Manage';
    $objProject = new Project();
    $project_types = $objProject->getAllProjectsForList();
    $waves = $objProject->getAllWaves();
       
    if($this->objURL->get('reload') == 'project_types') {
        echo Plugin::get('front'.DS.'project_types', array('project_types' => $project_types, 'objProject' => $objProject));
        exit();
    }
//    
//    if($this->objURL->get('reload') == 'order') {
//        echo Plugin::get('front'.DS.'team_order', array('teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject));
//        exit();
//    }
    
    require_once('_header.php');
?>
    <h1>Project Types :: Manage</h1>
        <div id="project_types" 
            data-name="/sugarkms/mod/changeTypeName.php" 
            data-reload="/sugarkms/project_types/reload/project_types"
            data-remove="/sugarkms/mod/removeProjectType.php"
            >
            <?php echo Plugin::get('front'.DS.'project_types', array('project_types' => $project_types, 'objProject' => $objProject)); ?>  
        </div>
        
        <h2>Project Waves</h2>    
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="categoriesTable" style="margin-bottom:0px;" >
            <tr>
                <th>Project Name</th>
                <th>Wave Name</th>
                <th>Month Start</th>
                <th>Month End</th>
                <th>In Same Year</th>
                <th>Write Two Years</th>
                <th>Action</th>
            </tr>
        <?php 
            foreach($waves as $wave) {
        ?>
            <tr> 
                <td class="br_td">
                    <?php echo $wave['type_name']; ?>
                </td>
                <td class="br_td">
                    <?php echo $wave['wave_name']; ?>
                </td>
                <td class="br_td  showSelect">
                    <span><?php echo $wave['month_start']; ?></span>
                </td>
                <td class="br_td  showSelect">
                    <span><?php echo $wave['month_end']; ?></span>
                </td>
                <td class="br_td">
                
                </td>
                <td class="br_td">
                
                </td>

                <td class="br_td">
                    
                </td>
            </tr>
        <?php 
                
            } 
        ?>
        </table>
        <div style="height:25px;"></div>
                    
            
<?php
    require_once('_footer.php');
?>