<?php
Assets::add_css(array('bootstrap/bootstrap.min.css', 'font-awesome/css/font-awesome.min.css','animate.css','color-styles.css','ui-elements.css','custom.css'));
Assets::add_js(array('bootstrap/bootstrap.min.js', 'scrolltopcontrol.js','jquery.sticky.js'));
?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <title><?php
        echo isset($page_title) ? "{$page_title} : " : '';
        e(class_exists('Settings_lib') ? settings_item('site.title') : 'Bonfire');
    ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php e(isset($meta_description) ? $meta_description : ''); ?>">
    <meta name="author" content="<?php e(isset($meta_author) ? $meta_author : ''); ?>">
    <?php
    /* Modernizr is loaded before CSS so CSS can utilize its features */
    echo Assets::js('modernizr-2.5.3.js');
    ?>
    <?php echo Assets::css(); ?>
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,500,400italic,500italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
    <script>
    var base_url = "<?php echo base_url(); ?>";
    </script>
</head>
  <body class="body-green" style="padding-left: 30px;padding-right: 30px;">
    