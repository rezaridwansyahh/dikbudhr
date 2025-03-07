<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/masters/jenis_izin';

?>
<ul class='nav nav-pills'>
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl); ?>" id='list'>
            <?php echo lang('jenis_izin_list'); ?>
        </a>
	</li>
	<?php if ($this->auth->has_permission('Jenis_izin.Masters.Create')) : ?>
	<li<?php echo $checkSegment == 'create' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl . '/create'); ?>" id='create_new'>
            <?php echo lang('jenis_izin_new'); ?>
        </a>
	</li>
	<?php endif; ?>
</ul>