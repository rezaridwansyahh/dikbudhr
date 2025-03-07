<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_izin_pegawai_permissions extends Migration
{
	/**
	 * @var array Permissions to Migrate
	 */
	private $permissionValues = array(
		array(
			'name' => 'Izin_pegawai.Izin.View',
			'description' => 'View Izin_pegawai Izin',
			'status' => 'active',
		),
		array(
			'name' => 'Izin_pegawai.Izin.Create',
			'description' => 'Create Izin_pegawai Izin',
			'status' => 'active',
		),
		array(
			'name' => 'Izin_pegawai.Izin.Edit',
			'description' => 'Edit Izin_pegawai Izin',
			'status' => 'active',
		),
		array(
			'name' => 'Izin_pegawai.Izin.Delete',
			'description' => 'Delete Izin_pegawai Izin',
			'status' => 'active',
		),

		array(
			'name' => 'Setting_atasan.Izin.View',
			'description' => 'Setting_atasan.Izin.View',
			'status' => 'active',
		),
		array(
			'name' => 'Setting_atasan.Izin.Add',
			'description' => 'Setting_atasan.Izin.Add',
			'status' => 'active',
		),
		array(
			'name' => 'Setting_atasan.Izin.Delete',
			'description' => 'Setting_atasan.Izin.Delete',
			'status' => 'active',
		),
    );

    /**
     * @var string The name of the permission key in the role_permissions table
     */
    private $permissionKey = 'permission_id';

    /**
     * @var string The name of the permission name field in the permissions table
     */
    private $permissionNameField = 'name';

	/**
	 * @var string The name of the role/permissions ref table
	 */
	private $rolePermissionsTable = 'role_permissions';

    /**
     * @var numeric The role id to which the permissions will be applied
     */
    private $roleId = '1';

    /**
     * @var string The name of the role key in the role_permissions table
     */
    private $roleKey = 'role_id';

	/**
	 * @var string The name of the permissions table
	 */
	private $tableName = 'permissions';

	//--------------------------------------------------------------------

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		$rolePermissionsData = array();
		foreach ($this->permissionValues as $permissionValue) {
			$this->db->insert($this->tableName, $permissionValue);

			$rolePermissionsData[] = array(
                $this->roleKey       => $this->roleId,
                $this->permissionKey => $this->db->insert_id(),
			);
		}

		$this->db->insert_batch($this->rolePermissionsTable, $rolePermissionsData);
	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
        $permissionNames = array();
		foreach ($this->permissionValues as $permissionValue) {
            $permissionNames[] = $permissionValue[$this->permissionNameField];
        }

        $query = $this->db->select($this->permissionKey)
                          ->where_in($this->permissionNameField, $permissionNames)
                          ->get($this->tableName);

        if ( ! $query->num_rows()) {
            return;
        }

        $permissionIds = array();
        foreach ($query->result() as $row) {
            $permissionIds[] = $row->{$this->permissionKey};
        }

        $this->db->where_in($this->permissionKey, $permissionIds)
                 ->delete($this->rolePermissionsTable);

        $this->db->where_in($this->permissionNameField, $permissionNames)
                 ->delete($this->tableName);
	}
}