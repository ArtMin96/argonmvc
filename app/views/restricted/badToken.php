<?php
use Core\Router;
?>
<?php $this->start('head'); ?>
<?php $this->setSiteTitle('Bad Token'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main>
    <section class="section section-shaped section-lg">
        <div class="shape shape-style-1 bg-gradient-default">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="container pt-lg-md">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center mt-5">
                    <h1 class="display-1 mb-4">Bad Token</h1>
                    <p class="text-white mb-5">Your token is corrupted.</p>
                    <a href="<?= Router::goBack(); ?>" class="btn btn-icon btn-3 btn-neutral">
                        <span class="btn-inner--icon"><i class="fas fa-chevron-circle-left"></i></span>
                        <span class="btn-inner--text">Go Back</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php $this->end(); ?>