<h1><?php echo $title; ?></h1>

<?php echo Form::open(); ?>

<div class="input submit ">
    <?php echo Form::submit('delete', 'Delete'); ?>
</div>

<?php echo Form::close();?>