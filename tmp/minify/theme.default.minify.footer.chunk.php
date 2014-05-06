<footer>
<div style="float:left;"><?php echo Chunk::get('footer-links'); ?></div>
<div style="float:right;"><?php Action::run('theme_footer'); ?><?php echo Site::powered(); ?></div>
</footer></div><?php echo Snippet::get('google-analytics'); ?>
<?php Javascript::add('public/assets/js/jquery.js', 'frontend', 1); ?>
<?php Javascript::add('public/assets/js/bootstrap.js', 'frontend', 2); ?>
<?php Javascript::load(); ?></body>
</html>