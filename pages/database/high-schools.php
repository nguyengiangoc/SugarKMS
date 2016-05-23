<?php
    $header = 'High Schools :: Manage';
    $objSchool = new School();
    $schools = $objSchool->getAllHighSchools();
    
    require_once('_header.php');
?>
    <h1><?php echo $header; ?></h1>
        <div data-plugin="high_schools" class="reloadSection">
            <?php echo Plugin::get('high_schools', array('schools' => $schools, 'objSchool' => $objSchool)); ?>  
        </div>
        <div style="height:25px;"></div>
                    
            
<?php
    require_once('_footer.php');
?>