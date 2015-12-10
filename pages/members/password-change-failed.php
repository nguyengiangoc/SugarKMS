<?php
    $id = $this->objURL->get('id');
    $url = '/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'));
    require_once('_header.php');
?>
<h1>Member Profile :: Password Change Failed</h1>
<p>There was a problem saving the new password for <strong><?php echo $member['name']; ?></strong>'s profile.<br />Please contact administrator.<br />
<a href="<?php echo $url.'/action/password/id/'.$id; ?>">Go back and attempt edit again.</a><br />
</p>
<?php
    require_once('_footer.php');
?>