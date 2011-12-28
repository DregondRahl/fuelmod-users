<ul>
    <?php foreach($wall as $post): ?>
    <li>
       <?php echo nl2br($post->content); ?> 
    </li>
    <?php endforeach; ?>
</ul>
