<?php
    $objProject = new Project();
    $projects = $objProject->getAllProjectsForList();
    $objMember = new Member();
    $objForm = new Form();
    $rows = $objProject->getProjectsNoEXCO();
    require_once('_header.php');
?>
<h1>Project &amp; EXCO :: Search</h1>

        <div style="height:10px;display:block;">
        </div>
                       
        <div style="overflow-x:scroll;white-space: nowrap;" >
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:0px;">
                <tr>
                    <th style="border-right:dashed 1px #222;">Name</th>
                    <th colspan="<?php echo date("Y")-2008; ?>">Years</th>
                </tr>
            <?php 
                foreach($projects as $project) { 
                    
            ?>
                <tr> 
                    <td class="br_td" style="border-right:dashed 1px #222;width:135px;">
                        <strong><?php echo $project['name']; ?></strong>
                    </td>
                    <?php 
                        $years = $objProject->getAnnualProjects($project['id']);
                        for($i=date("Y");$i>2008;$i--) { 
                    ?>
                        <td class="br_td">
                    <?php
                            foreach($years as $year) {
                                if($year['year_start'] == $i) {
                    ?>
                            <a href="/sugarkms/projects/id/<?php echo $year['id']; ?>"><?php echo $year['project_time']; ?></a>
                    <?php
                                if($year['wave_id'] != 0) { echo '<br />'; }
                                }
                            }
                    ?>
                        </td>
                    <?php 
                            
                        } 
                    ?> 
                </tr>
            <?php 
                    
                } 
            ?>
                <tr>
                    <td colspan="7" style="height:0px;border-top: dashed 1px #222;padding:0;"></td>
                </tr>
            </table>
        </div>
        <div style="height:25px;"></div>

<?php 
    require_once('_footer.php');  
?>