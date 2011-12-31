<ul>
<?php foreach($following as $user): ?>

    <li><?php echo Html::anchor('users/'. Inflector::friendly_title($user->username.'-'.$user->id, '-', true), $user->username); ?></li>

<?php endforeach; ?>
</ul>