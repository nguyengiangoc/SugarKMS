<?php
    $url = '/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'));
    require_once('_header.php');
?>
<h1>Member :: Edit</h1>
<p>The avatar of <strong><?php echo $member['name']; ?></strong> has been edited successfully.<br />
<a href="<?php echo $url.'/id/'.$id; ?>">View this profile.</a><br />
<a href="<?php echo $url; ?>">Go to the list of members.</a><br />
<a href="<?php echo $url.'/id/'.$id.'/action/edit'; ?>">Go back and make another edit.</a><br />
<?php
    require_once('_footer.php');
?>