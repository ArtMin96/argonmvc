<?php $this->start('head'); ?>
<?php $this->setSiteTitle('Argon - High Performance PHP Framework'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<div class="container-fluid h-100">
    <div class="row h-100">
        <div class="col-12 p-0 h-100">
            <div class="jumbotron text-center m-0 d-flex flex-column justify-content-center">
                <h1 class="display-4">Argon</h1>
                <p class="lead text-muted">This is a lightweight and high performance MVC framework for building PHP websites. It comes with bootstrap preinstalled and is very easy to extend and use.</p>
                <p class="lead">Current Version <?= VERSION; ?></p>
            </div>
        </div>
    </div>
</div>
<?php $this->end(); ?>
