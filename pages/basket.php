<?php
    $action = $this->objURL->get('action');
    if($action == 'view') {
        echo Plugin::get('front'.DS.'basket_view');
    } else {
        require_once("_header.php");   
?>
    <h1>Basket</h1>
    <div id="big_basket">
        <?php echo Plugin::get('front'.DS.'basket_view'); ?>
    </div>
<?php  
        require_once("_footer.php");
    }
?>