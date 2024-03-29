<?php 
function echo_login_page(LoginController $controller) { ?>
<div class="container-fluid content">
    
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
            <img src="/logo/logo2.png">
            <form id="loginForm" method="POST" role="form" style="margin-top: 10px">
                <input type="text" class="hidden" name="form_build_id" value="<?php echo $controller->form_build_id; ?>"/>
                <input name="username" class="form-control" placeholder="<?php echo _t(20); ?>" required autofocus>
                <input name="password" class="form-control" type="password" placeholder="<?php echo _t(22); ?>" required>
                <input type="submit" class="btn btn-lg btn-default btn-block" value="<?php echo _t(21); ?>" name="login">
                <div class="row login-actions">
                    <label class="pull-left">
                        <input type="checkbox" value="remember-me" name="remember-me" <?php if(isset($_COOKIE["remember-me"]) && $_COOKIE["remember-me"]==true ) echo "checked"; ?>>
                        <?php echo _t(113); ?>
                    </label>
                    <label class="pull-right">
                    <a href="<?php echo SITE_ROOT."/forget_password"; ?>" id="forgetPassword">
                        <?php echo _t(23); ?></a>
                    </label>
                </div>
            </form>
            <?php $controller->printMessages(); ?>
        </div>
    </div>
</div>

<?php } ?> 