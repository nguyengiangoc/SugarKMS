<?php
    $header = 'Positions: :: Manage';
    $objPosition = new Position();
    $positions = $objPosition->getAllPositions();
    $positionsEXCO = $objPosition->getAllPositionsInProject(5);
    $positionsProject = $objPosition->getAllPositionsInProject(6);
    
    $objTeam = new Team();
    $teamsProject = $objTeam->getAllTeamsInProject(6);
    $teamsEXCO = $objTeam->getAllTeamsInProject(5);
    
       
    if($this->objURL->get('reload') == 'categories') {
        echo Plugin::get('front'.DS.'position_categories', array('positions' => $positions, 'objPosition' => $objPosition));
        exit();
    }
    
    if($this->objURL->get('reload') == 'position_team') {
        echo Plugin::get('front'.DS.'position_team', array('positionsEXCO' => $positionsEXCO, 'positionsProject' => $positionsProject, 'teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject, 'objPosition' => $objPosition));
        exit();
    }
    
    require_once('_header.php');
?>
    <h1>Positions :: Manage</h1>
        <div id="categories" style="display:inline-block; float: left;" 
            data-name="/sugarkms/mod/changePositionName.php" 
            data-type="/sugarkms/mod/changePositionType.php"
            data-reload="/sugarkms/positions/reload/categories"
            data-remove="/sugarkms/mod/removePosition.php"
            >
            <?php echo Plugin::get('front'.DS.'position_categories', array('positions' => $positions, 'objPosition' => $objPosition)); ?>  
        </div>     
        <div id="order" style="display:inline-block;float:right;" 
            data-reload="/sugarkms/teams/reload/order"
            data-order="/sugarkms/mod/changeTeamOrder.php"
            >        
            <?php echo Plugin::get('front'.DS.'position_order', array('positionsEXCO' => $positionsEXCO, 'positionsProject' => $positionsProject)); ?>
        </div>       
        <div id="position_team" style="clear:both;display:block;" 
            data-reload="/sugarkms/positions/reload/position_team"
            data-team="/sugarkms/mod/changePositionTeam.php"
            >        
            <?php echo Plugin::get('front'.DS.'position_team', array('positionsEXCO' => $positionsEXCO, 'positionsProject' => $positionsProject, 'teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject, 'objPosition' => $objPosition)); ?>
        </div>        
        <div style="height:25px;clear:both;"></div>
        
            
<?php
    require_once('_footer.php');
?>