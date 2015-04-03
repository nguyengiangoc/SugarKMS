<?php 
    if(Login::isLogged(Login::$_login_admin)) {
        Helper::redirect(Login::$_dashboard_admin);
    }
    $objForm = new Form;
    $objValid = new Validation($objForm);
    if($objForm->isPost('login_email')) {
        $objAdmin = new Admin();
        if($objAdmin->isUser($objForm->getPost('login_email'), $objForm->getPost('login_password'))) {
            Login::loginAdmin($objAdmin->_id, $this->objURL->href($this->objURL->get(Login::$_referrer)));
        } else {
            $objValid->add2Errors('login');
        }
    }
    require_once('_header.php'); 
?>
<h1>Login</h1>
<form action="" method="post">
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert"> 
        <tr>
            <?php echo $objValid->validate('login'); ?>
            <th><label for="login_email">Login:</label></th>
            <td><input type="text" name="login_email" id="login_email" class="fld" value="" /></td>
        </tr>
        <tr>
            <th><label for="login_password">Password:</label></th>
            <td><input type="password" name="login_password" id="login_password" class="fld" value="" /></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td><label for="btn_login" class="sbm sbm_blue fl_l"><input type="submit" id="btn_login" class="btn" value="Login" /></label></td>
        </tr>
    </table>
</form>

<?php require_once('_footer.php'); ?>