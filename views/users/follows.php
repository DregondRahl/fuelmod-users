
<ul>
<?php foreach($follows as $user): ?>
    
    <li><?php echo $user->follows->username; ?> </li>
    
<?php endforeach; ?>
</ul>