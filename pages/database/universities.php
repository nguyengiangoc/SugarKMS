<?php
    $objSchool = new School();
    $schools = $objSchool->getAllUni();
    
    require_once('_header.php');
?>
    <h1><?php echo $header; ?></h1>
        <div data-plugin="universities" class="reloadSection">
            <?php echo Plugin::get('universities', array('schools' => $schools, 'objSchool' => $objSchool)); ?>  
        </div>
        <div style="height:25px;"></div>
                    
            
<?php
    require_once('_footer.php');
?>