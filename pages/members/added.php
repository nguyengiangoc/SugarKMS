<?php
    $url = '/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'));
    require_once('_header.php');
?>
<h1><?php echo $member['name']; ?> :: Added</h1>
<p>The profile for <strong><?php echo $member['name']; ?></strong> has been added successfully.<br />
<a href="<?php echo $url.'/id/'.$id; ?>">View this profile.</a><br />
<a href="<?php echo $url; ?>">Go back to the list of members.</a><br />
<a href="<?php echo $url.'/action/add'; ?>">Add another member.</a></p>
<?php
    require_once('_footer.php');
?>