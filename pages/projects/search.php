<?php
    $objProject = new Project();    
    $exco = $this->cPage == 'exco' ? 1 : 0;
    
    $header = $exco ? 'EXCO' : 'Project';
    $header .= ' :: Search';
    require_once('_header.php');
?>
<h1><?php echo $header; ?></h1>

        <div style="height:10px;display:block;">
        </div>
                       
        <div style="overflow-x:scroll;white-space: nowrap;" >
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:0px;">
                <tr>
                    <th style="border-right:dashed 1px #222;">Name</th>
                    <th colspan="<?php echo date("Y")-2008; ?>">Years</th>
                </tr>
            <?php 
                if($exco) {
                    ?>
                    <tr> 
                        <td  style="border-right:dashed 1px #222;width:135px;">
                            <strong>EXCO</strong>
                        </td>
                        <?php 
                            $years = $objProject->getAnnualProjects(5);
                            for($i=date("Y");$i>2008;$i--) { 
                        ?>
                            <td >
                        <?php
                                foreach($years as $year) {
                                    if($year['year_start'] == $i) {
                        ?>
                                <a href="<?php echo $this->objPage->generateURL('exco', array('id' => $year['id'])); ?>"><?php echo $year['project_time']; ?></a>
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
                } else {
                    $rows = $objProject->getProjectsNoEXCO();
                    foreach($rows as $project) {
                        ?>
                            <tr> 
                                <td  style="border-right:dashed 1px #222;width:135px;">
                                    <strong><?php echo $project['name']; ?></strong>
                                </td>
                                <?php 
                                    $years = $objProject->getAnnualProjects($project['id']);
                                    for($i=date("Y");$i>2008;$i--) { 
                                ?>
                                    <td style="<?php echo $project['wave'] != 0 ? 'vertical-align:top;' : ''; ?>">
                                <?php
                                        foreach($years as $year) {
                                            if($year['year_start'] == $i) {
                                ?>
                                        <a href="<?php echo $this->objPage->generateURL('project', array('id' => $year['id'])); ?>"><?php echo $year['project_time']; ?></a>
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
                }
                 
                    
            ?>
            
            </table>
        </div>
        <div style="height:25px;"></div>

<?php 
    require_once('_footer.php');  
?>