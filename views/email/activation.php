Welcome <?php echo $user; ?>
<br/>
Activation Link : <?php echo Html::anchor('users/auth/activate/'. $activation_link, 'Activate'); ?>