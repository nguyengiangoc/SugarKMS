    <?php 
        if(Login::isLogged()) {
            Helper::redirect(Login::$_default);
        }
        $objForm = new Form;
        $objValid = new Validation($objForm);
        $objMember = new Member();
        if($objForm->isPost('login_email')) {
            
            $member = $objMember->getMembers(array('personal_email' => $objForm->getPost('login_email'), 'password' => Login::hash($objForm->getPost('login_password'))));
            
            if(!empty($member) && count($member) == 1) {
                //echo '<h1>VALID</h1>';
                Login::processLogin($member[0]['id'], '/sugarkms/'.$this->objURL->href($this->objURL->get(Login::$_referrer)), $objForm->getPost('remember'));
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
                <td><label for="login_email">Email:</label></td>
                <td><input type="text" name="login_email" id="login_email" class="fld" value="" /></td>
            </tr>
            <tr>
                <td><label for="login_password">Password:</label></td>
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
    <span style="display:block;margin-bottom:20px;">Can't log in? <a href="http://www.facebook.com/BlueMoon.NS" target="_blank" >Contact admin!</a></span>
    
    
    <?php require_once('_footer.php'); ?>