Hey <?php echo $user; ?>
<br/>
Password Reset Link : <?php echo Html::anchor('users/auth/reset_password/'.$reset_link, 'Reset'); ?>