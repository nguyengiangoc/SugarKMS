<?php
    $objValid = new Validation();
    $objURL = new URL();
    $zone = $objShipping->getZones();
    require_once('_header.php');
?>
<h1>Local zones</h1>
<form method="post" class="ajax" data-action="/ecommerce/<?php echo $this->objURL->getCurrent('action', false, array('action', 'add')); ?>">
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
        <tr>
            <th><label for="name" class="valid_name">Zone name: *</label></th>
        </tr>
        <tr>
            <td>
                <input type="text" name="name" id="name" class="fld" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="btn_add" class="sbm sbm_blue fl_l"><input type="submit" id="btn_add" class="btn" value="Add" /></label>
            </td>
        </tr>
    </table>
</form>
<div class="dev br_td">&nbsp;</div>
<form method="post" data-url="/ecommerce/<?php echo $this->objURL->getCurrent(array('action', 'id'), false, array('action', 'update', 'id')); ?>">
    <div id="zoneList">
        <?php echo Plugin::get('admin'.DS.'zone', array('rows' => $zone, 'objURL' => $objURL)); ?>
    </div>
</form>
<?php require_once('_footer.php'); ?>