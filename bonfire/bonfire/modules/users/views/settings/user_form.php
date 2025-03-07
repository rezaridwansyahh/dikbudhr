<div class='box box-primary'>
    <div class="box-body">
<?php

$errorClass = ' error';
$controlClass = 'span6';
$fieldData = array(
    'errorClass'    => $errorClass,
    'controlClass'  => $controlClass,
);

if (isset($user) && $user->banned) :
?>
<div class="alert alert-warning fade in">
	<h4 class="alert-heading"><?php echo lang('us_banned_admin_note'); ?></h4>
</div>
<?php
endif;
if (isset($password_hints) ) :
?>
<div class="alert alert-info fade in">
    <a data-dismiss="alert" class="close">&times;</a>
    <?php echo $password_hints; ?>
</div>
<?php
endif;

echo form_open($this->uri->uri_string(), 'class="form-horizontal" autocomplete="off"');
?>
	<fieldset>
		<legend><?php echo lang('us_account_details') ?></legend>
        <?php Template::block('user_fields', 'user_fields', $fieldData); ?>
	</fieldset>
    <?php
     
    if ($this->auth->has_permission('Bonfire.Roles.Manage')) :
    ?>
    <fieldset>
        <legend><?php echo lang('us_role'); ?></legend>
        <div class="control-group col-sm-12">
            <label for="role_id" class="control-label"><?php echo lang('us_role'); ?></label>
            <div class="controls">
                <select name="role_id[]" id="role_id" multiple="multiple" class="select2 <?php echo $controlClass; ?> form-control">
                    <?php
                    if (isset($roles) && is_array($roles) && count($roles)) :
                        foreach ($roles as $role) :
                            $selected = '';
                            foreach($selectedRoles as $selectedRole) : 
                                if($role->role_id == $selectedRole->role_id){
                                    $selected = 'SELECTED';
                                }
                            endforeach;    
                            echo "<option $selected value='$role->role_id'>".strtoupper($role->role_name)."</option>";
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </div>
    </fieldset>
    <?php endif; ?>
	<!--
	<div class="control-group <?php echo form_error('nip') ? 'error' : ''; ?> col-sm-12">
		<?php echo form_label('Pegawai', 'nip', array('class' => 'control-label') ); ?>
		<div class='controls'>
			<select name="nip" id="nip" class="select2 form-control"width="400px" onchange="getinfo(this.value)">
				<option value="">-- Pilih  --</option>
				<?php if (isset($pegawais) && is_array($pegawais) && count($pegawais)):?>
				<?php foreach($pegawais as $rec):?>
					<option value="<?php echo $rec->no_absen?>" <?php if(isset($user->nip))  echo  ($rec->no_absen == $user->nip) ? "selected" : ""; ?>> <?php e(ucfirst($rec->nama)); ?></option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
			<span class='help-inline'><?php echo form_error('nip'); ?></span>
		</div>
	</div>
	-->
	<?php
    // Allow modules to render custom fields
    Events::trigger('render_user_form');
    ?>
    
    <?php
    if (isset($user) && has_permission('Permissions.' . ucfirst($user->role_name) . '.Manage')
        && $user->id != $this->auth->user_id() && ($user->banned || $user->deleted)
       ) :
    ?>
    <fieldset>
        <legend><?php echo lang('us_account_status'); ?></legend>
        <?php
        $field = 'activate';
        if ($user->active) {
            $field = 'de' . $field;
        }
        ?>
        <div class="control-group">
            <div class="controls">
                <label for="<?php echo $field; ?>">
                    <input type="checkbox" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="1" />
                    <?php echo lang('us_' . $field . '_note') ?>
                </label>
            </div>
        </div>
        <?php if ($user->deleted) : ?>
        <div class="control-group">
            <div class="controls">
                <label for="restore">
                    <input type="checkbox" name="restore" id="restore" value="1" />
                    <?php echo lang('us_restore_note'); ?>
                </label>
            </div>
        </div>
        <?php elseif ($user->banned) : ?>
        <div class="control-group">
            <div class="controls">
                <label for="unban">
                    <input type="checkbox" name="unban" id="unban" value="1" />
                    <?php echo lang('us_unban_note'); ?>
                </label>
            </div>
        </div>
        <?php endif; ?>
    </fieldset>
    <?php endif; ?>
   </div>
    <div class="box-footer">
        <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save') . ' ' . lang('bf_user'); ?>" />
        <?php echo lang('bf_or'); ?>
        <?php echo anchor(SITE_AREA . '/settings/users', lang('bf_action_cancel')); ?>
    </div>
<?php echo form_close(); ?>
</div>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>
<link href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script language='JavaScript' type='text/javascript' src='<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js'></script>
<script type="text/javascript">
$(".select2").select2();
</script>
  