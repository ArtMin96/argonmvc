<?php
use Core\FormHelper;
use Core\CSRF;
?>
<?php $this->start('head'); ?>
<?php $this->setSiteTitle('Argon - Sign Up'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center mt-5">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Register Here!</h3><hr>
        <form class="form" action="" method="post">
            <?= CSRF::csrfInput() ?>
            <?= FormHelper::inputBlock('text','First Name','fname',$this->newUser->fname,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::inputBlock('text','Last Name','lname',$this->newUser->lname,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::inputBlock('text','Email','email',$this->newUser->email,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::inputBlock('text','Username','username',$this->newUser->username,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::inputBlock('password','Password','password',$this->newUser->password,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
            <?= FormHelper::inputBlock('password','Confirm Password','confirm',$this->newUser->confirm,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
            <div class="d-flex justify-content-end">
                <div class="text-dk flex-grow-1">Alread have an account? <a href="<?=PROOT?>auth/login">Log In</a></div>
                <?= FormHelper::submitTag('Register',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>
<?php $this->end(); ?>