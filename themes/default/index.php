	<?php echo theme_view('header'); ?>
    <?php
    echo theme_view('_sitenav');

    //echo Template::message();
    echo isset($content) ? $content : Template::content();

    echo theme_view('footerlogin');
    ?>