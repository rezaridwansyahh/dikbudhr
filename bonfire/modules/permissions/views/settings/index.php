<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
<?php

$num_columns = 5;

?>
<div class="admin-box">
	<div class="box-header">
	<p class="intro"><?php e(lang('permissions_intro')); ?></p>
	  <a href="<?php echo base_url(); ?>admin/settings/permissions/create" id='create_new'>
		<button type="button" class="btn btn-default btn-warning pull-right">Tambah</button>
	  </a>
	</div>
	
    <?php
    if (isset($results) && is_array($results) && count($results)) :
        echo form_open($this->uri->uri_string());
    ?>
		<table class="table table-bordered table-striped table-responsive dt-responsive table-data">
			<thead>
				<tr>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<th><?php echo lang('permissions_id'); ?></th>
					<th><?php echo lang('permissions_name'); ?></th>
					<th><?php echo lang('permissions_description'); ?></th>
					<th><?php echo lang('permissions_status'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="delete" class="btn btn-danger" id="delete-me" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php e(js_escape(lang('permissions_delete_confirm'))); ?>')">
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($results as $record) : ?>
				<tr>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->permission_id; ?>" /></td>
					<td><?php echo $record->permission_id; ?></td>
					<td><a href='<?php echo site_url(SITE_AREA . "/settings/permissions/edit/{$record->permission_id}"); ?>'><?php e($record->name); ?></a></td>
					<td><?php e($record->description); ?></td>
					<td><?php e(ucfirst($record->status)); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php
        echo form_close();
    else :
    ?>
    <p><?php echo lang('permissions_no_records'); ?></p>
    <?php
    endif;
	?>
</div>
</div>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript">
$(".table-data").DataTable({
	ordering: true,
	processing: true 
});
 

</script>