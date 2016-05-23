<?php
    $objProject = new Project();
    $project_types = $objProject->getAllProjectsForList();
    $waves = $objProject->getWaves();
    
    require_once('_header.php');
?>
    <h1><?php echo $header; ?></h1>
        <div id="project_types" >
            <?php echo Plugin::get('project_types', array('project_types' => $project_types, 'objProject' => $objProject)); ?>  
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
                <td >
                    <?php echo $wave['type_name']; ?>
                </td>
                <td >
                    <?php echo $wave['wave_name']; ?>
                </td>
                <td class="  showSelect">
                    <span><?php echo $wave['month_start']; ?></span>
                </td>
                <td class="  showSelect">
                    <span><?php echo $wave['month_end']; ?></span>
                </td>
                <td >
                
                </td>
                <td >
                
                </td>

                <td >
                    
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