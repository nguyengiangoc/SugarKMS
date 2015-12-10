<?php 
    if(Login::isLogged(Login::$_login_admin)) {
        Helper::redirect(Login::$_default);
    }
    $objForm = new Form;
    $objValid = new Validation($objForm);
    if($objForm->isPost('login_email')) {
        $objAdmin = new Admin();
        if($objAdmin->isUser($objForm->getPost('login_email'), $objForm->getPost('login_password'))) {
            Login::loginAdmin($objAdmin->_id, '/sugarkms/'.$this->objURL->href($this->objURL->get(Login::$_referrer)), $objForm->getPost('remember'));
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
            <th><label for="login_email">Email:</label></th>
            <td><input type="text" name="login_email" id="login_email" class="fld" value="" /></td>
        </tr>
        <tr>
            <th><label for="login_password">Password:</label></th>
            <td><input type="password" name="login_password" id="login_password" class="fld" value="" /></td>
        </tr>
        <tr>            
            <td colspan="2">
                <input type="checkbox" name="remember" value="1" /><label for="remember" class="checkboxLabel">Remember me</label> 
                <label for="btn_login" class="sbm sbm_blue fl_r"><input type="submit" id="btn_login" class="btn" value="Login" /></label>
            </td>
        </tr>
    </table>
</form>

<?php require_once('_footer.php'); ?>