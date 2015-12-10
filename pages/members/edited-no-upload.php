<?php
    $id = $this->objURL->get('id');
    $url = '/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'));
    require_once('_header.php');
?>
<h1>Member Profile :: Edit</h1>
<p>There was a problem changing avatar in the profile of <strong><?php echo $member['name']; ?></strong>.<br />Please contact administrator.<br />
<a href="<?php echo $url.'/id/'.$id.'/action/edit'; ?>">Go back and attempt edit again.</a><br />
</p>
<?php
    require_once('_footer.php');
?>