<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/masters/daftar_rohaniawan';

?>
<ul class='nav nav-pills'>
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl); ?>" id='list'>
            <?php echo lang('daftar_rohaniawan_list'); ?>
        </a>
	</li>
	<?php if ($this->auth->has_permission('Daftar_rohaniawan.Masters.Create')) : ?>
	<li<?php echo $checkSegment == 'create' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl . '/create'); ?>" id='create_new'>
            <?php echo lang('daftar_rohaniawan_new'); ?>
        </a>
	</li>
	<?php endif; ?>
</ul>