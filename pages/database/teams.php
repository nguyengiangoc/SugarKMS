<?php
    $objTeam = new Team();
    $teams = $objTeam->getAllTeams();
    $teamsProject = $objTeam->getAllTeamsInProject(6);
    $teamsEXCO = $objTeam->getAllTeamsInProject(5);    
    require_once('_header.php');
?>
    <h1><?php echo $header; ?></h1>
        <div data-plugin="team_categories" class="reloadSection" style="display:inline-block; float: left;" 
            data-type="/sugarkms/mod/changeTeamType.php"
            >
            <?php echo Plugin::get('team_categories', array('teams' => $teams, 'objTeam' => $objTeam)); ?>  
        </div>
        <div data-plugin="team_order" class="reloadSection" style="display:inline-block;float:right;" >        
            <?php echo Plugin::get('team_order', array('teamsEXCO' => $teamsEXCO, 'teamsProject' => $teamsProject)); ?>
        </div>                    
            
<?php
    require_once('_footer.php');
?>