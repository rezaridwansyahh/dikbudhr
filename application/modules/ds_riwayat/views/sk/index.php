<?php

$num_columns	= 6;
$can_delete	= $this->auth->has_permission('Ds_riwayat.Sk.Delete');
$can_edit		= $this->auth->has_permission('Ds_riwayat.Sk.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class='admin-box'>
	<h3>
		<?php echo lang('ds_riwayat_area_title'); ?>
	</h3>
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class='table table-striped'>
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th class='column-check'><input class='check-all' type='checkbox' /></th>
					<?php endif;?>
					
					<th><?php echo lang('ds_riwayat_field_id_pemroses'); ?></th>
					<th><?php echo lang('ds_riwayat_field_tindakan'); ?></th>
					<th><?php echo lang('ds_riwayat_field_catatan_tindakan'); ?></th>
					<th><?php echo lang('ds_riwayat_field_waktu_tindakan'); ?></th>
					<th><?php echo lang('ds_riwayat_field_akses_pengguna'); ?></th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'>
						<?php echo lang('bf_with_selected'); ?>
						<input type='submit' name='delete' id='delete-me' class='btn btn-danger' value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('ds_riwayat_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class='column-check'><input type='checkbox' name='checked[]' value='<?php echo $record->id_riwayat; ?>' /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/sk/ds_riwayat/edit/' . $record->id_riwayat, '<span class="icon-pencil"></span> ' .  $record->id_pemroses); ?></td>
				<?php else : ?>
					<td><?php e($record->id_pemroses); ?></td>
				<?php endif; ?>
					<td><?php e($record->tindakan); ?></td>
					<td><?php e($record->catatan_tindakan); ?></td>
					<td><?php e($record->waktu_tindakan); ?></td>
					<td><?php e($record->akses_pengguna); ?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'><?php echo lang('ds_riwayat_records_empty'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
    echo form_close();
    
    ?>
</div>