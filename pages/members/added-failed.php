<?php
    $url = '/sugarkms/'.$this->objURL->getCurrent(array('action'));
    require_once('_header.php');
?>
<h1>Member Profile :: Add Failed</h1>
<p>There was a problem adding the profile.<br />Please contact administrator.<br />
<a href="<?php echo $url; ?>">Go back to the list of members.</a><br />
</p>
<?php
    require_once('_footer.php');
?>