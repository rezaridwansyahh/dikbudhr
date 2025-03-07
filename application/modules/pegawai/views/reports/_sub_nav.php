<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/kepegawaian/pegawai';

?>
<ul class='nav nav-pills'>
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl); ?>" id='list'>
           aacd <?php echo lang('pegawai_list'); ?>
        </a>
	</li>
	<?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Create')) : ?>
	<li<?php echo $checkSegment == 'create' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl . '/create'); ?>" id='create_new'>
            aacde<?php echo lang('pegawai_new'); ?>
        </a>
	</li>
	<?php endif; ?>
</ul>