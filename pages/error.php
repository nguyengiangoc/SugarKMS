<?php     
    $header = 'Error';    
    require_once('_header.php'); 
?>
    <h1>Error</h1>
    <p>The page you're looking for is not available.</p>
    <p>Reasons: <strong><?php echo $error; ?></strong></p>
    <p>For support, please <a href="http://www.facebook.com/BlueMoon.NS" target="_blank" >contact the administrator</a>.</p>
<?php require_once('_footer.php'); ?>