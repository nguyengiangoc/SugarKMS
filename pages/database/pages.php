<?php 
    $objPage = new Page();    
    $header = 'Database :: Pages';
    require_once('_header.php');
?>

    <h1><?php echo $header; ?></h1>
    <div id="links" 
        data-access_mode="/sugarkms/mod/changeAccessMode.php"
        data-get_position="/sugarkms/mod/getPositionsForType.php"
        data-get_team="/sugarkms/mod/getTeamsForPosition.php"
    ></div>       

        <div style="width:170px;" class="fl_l">
            <h2>Pages</h2>      
            <div style="height:700px;overflow-y:scroll;border-top:1px dashed #AAA">
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="margin-bottom:0px;">
            <?php 
                $groups = $objPage->getGroups('',array('order' => 'asc'));    
                foreach($groups as $group) {
            ?>
                <tr  class="groupRow" >
                    <td><strong><?php echo $group['name']; ?></strong></td>
                </tr>
                <?php 
                    $actions = $objPage->getPages(array('group_id' => $group['id']), array('everyone' => 'desc', 'order' => 'asc'));
                    foreach($actions as $action) {
                        ?>
                        <tr data-id="<?php echo $action['id']; ?>" class="clickable" >
                            <td >
                                <a href="#"><?php echo ucwords($action['name']); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
                     
            <?php
                }
            ?>
            </table>
            </div>
        </div>
        <div style="margin-left:180px;">
            
            <div data-plugin="page_details" style="width:auto;" class="reloadSection">
                <h2 style="border-bottom: 1px #AAA dashed;">Page Details</h2>
                <br />
                Click the page to see the page details.
            </div>
        </div>

    
    <div style="height:25px;clear:both;"></div>
    

<?php
    require_once('_footer.php');
?>