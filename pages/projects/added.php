<?php
    $url = '/sugarkms/projects';
    require_once('_header.php');
    $type = $project['project_type_id'] == 5 ? 'EXCO' : 'project';
?>
<h1><?php echo $project['name'].' '.$project['project_time']; ?> :: Added</h1>
<p><strong><?php echo $project['name'].' '.$project['project_time']; ?></strong> has been added successfully.<br />
<a href="<?php echo '/sugarkms/projects/id/'.$id; ?>">View this <?php echo $type; ?>.</a><br />
<a href="<?php echo '/sugarkms/projects/id/'.$id.'/action/edit/'; ?>">Add information to this <?php echo $type; ?>.</a><br />
<a href="<?php echo '/sugarkms/projects/action/add'; ?>">Add another project.</a></p>
<?php
    require_once('_footer.php');
?>