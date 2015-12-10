<?php
    $url = '/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'));
    require_once('_header.php');
?>
<h1><?php echo $member['name']; ?> :: Password Changed</h1>
<p>The password for <strong><?php echo $member['name']; ?></strong>'s account has been edited successfully.<br />
<a href="<?php echo $url.'/id/'.$id; ?>">View this profile.</a><br />
<a href="<?php echo $url; ?>">Go to the list of members.</a><br />
<?php
    require_once('_footer.php');
?>