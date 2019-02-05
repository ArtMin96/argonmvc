<?php
use Core\FormHelper;
use Core\CSRF;
?>
<?php $this->start('head'); ?>
<?php $this->setSiteTitle('Argon - Login'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center mt-5">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Log In</h3>
        <form class="form" action="<?=PROOT?>auth/login" method="post">
            <?= CSRF::csrfInput() ?>
            <?= FormHelper::displayErrors($this->displayErrors) ?>
            <?= FormHelper::inputBlock('text','Username','username',$this->login->username,['class'=>'form-control'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::inputBlock('password','Password','password',$this->login->password,['class'=>'form-control'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::checkboxBlock('Remember Me','remember_me',$this->login->getRememberMeChecked(),[],['class'=>'form-group'],$this->displayErrors) ?>
            <div class="d-flex justify-content-end">
                <div class="flex-grow-1 text-body">Don't have an account? <a href="<?=PROOT?>auth/signup">Sign Up</a></div>
                <?= FormHelper::submitTag('Login',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>
<?php $this->end(); ?>