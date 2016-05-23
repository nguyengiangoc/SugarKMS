<?php 
    $objPage = new Page();
    $groups = $objPage->getGroups('',array('order' => 'asc'));
    require_once('_header.php');
?>

    <h1><?php echo $header; ?></h1> 
    <div class="fl_l">
        <h2>Page Groups</h2>
        <div data-plugin="page_groups" class="reloadSection" data-current="1" >
             <?php echo Plugin::get('page_groups', array('groups' => $groups)); ?>  
        </div>
        
    </div>
    <div style="margin-left:45%;">
        
        <div data-plugin="pages_in_group" class="reloadSection" style="overflow-y:hidden;">      
            <h1>Group Details</h1>
            Click a group to see the pages in it.
        </div>  
    </div>
    <div style="height:25px;clear:both;"></div>
    

<?php
    require_once('_footer.php');
?>