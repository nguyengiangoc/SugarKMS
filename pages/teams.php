<?php
    $header = 'Teams :: Manage';
    $objTeam = new Team();
    $teams = $objTeam->getAllTeams();
    $teamsProject = $objTeam->getAllTeamsInProject(6);
    $teamsEXCO = $objTeam->getAllTeamsInProject(5);
       
    if($this->objURL->get('reload') == 'categories') {
        echo Plugin::get('front'.DS.'team_categories', array('teams' => $teams, 'objTeam' => $objTeam));
        exit();
    }
    
    if($this->objURL->get('reload') == 'order') {
        echo Plugin::get('front'.DS.'team_order', array('teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject));
        exit();
    }
    
    require_once('_header.php');
?>
    <h1>Teams :: Manage</h1>
        <div id="categories" style="display:inline-block; float: left;" 
            data-name="/sugarkms/mod/changeTeamName.php" 
            data-type="/sugarkms/mod/changeTeamType.php"
            data-reload="/sugarkms/teams/reload/categories"
            data-remove="/sugarkms/mod/removeTeam.php"
            >
            <?php echo Plugin::get('front'.DS.'team_categories', array('teams' => $teams, 'objTeam' => $objTeam)); ?>  
        </div>
        <div id="order" style="display:inline-block;float:right;" 
            data-reload="/sugarkms/teams/reload/order"
            data-order="/sugarkms/mod/changeTeamOrder.php"
            >        
            <?php echo Plugin::get('front'.DS.'team_order', array('teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject)); ?>
        </div>                    
            
<?php
    require_once('_footer.php');
?>