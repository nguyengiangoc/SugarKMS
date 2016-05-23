<?php
    $objPosition = new Position();
    $positions = $objPosition->getAllPositions();
    $positionsEXCO = $objPosition->getAllPositionsInProject(5);
    $positionsProject = $objPosition->getAllPositionsInProject(6);
    
    $objTeam = new Team();
    $teamsProject = $objTeam->getAllTeamsInProject(6);
    $teamsEXCO = $objTeam->getAllTeamsInProject(5);
    
    require_once('_header.php');
?>
    <h1><?php echo $header; ?></h1>
        <div data-plugin="position_categories" style="display:inline-block; float: left;" class="reloadSection">
            <?php echo Plugin::get('position_categories', array('positions' => $positions, 'objPosition' => $objPosition)); ?>  
        </div>     
        <div data-plugin="position_order" style="display:inline-block;float:right;" class="reloadSection">        
            <?php echo Plugin::get('position_order', array('positionsEXCO' => $positionsEXCO, 'positionsProject' => $positionsProject)); ?>
        </div>       
        <div data-plugin="position_team" style="clear:both;display:block;" class="reloadSection">        
            <?php echo Plugin::get('position_team', array('positionsEXCO' => $positionsEXCO, 'positionsProject' => $positionsProject, 'teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject, 'objPosition' => $objPosition)); ?>
        </div>        
        <div style="height:25px;clear:both;"></div>
        
            
<?php
    require_once('_footer.php');
?>