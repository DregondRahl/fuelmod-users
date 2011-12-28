
<ul>
<?php foreach($followers as $user): ?>
    
    <li><?php echo $user->followers->username; ?> </li>
    
<?php endforeach; ?>
</ul>