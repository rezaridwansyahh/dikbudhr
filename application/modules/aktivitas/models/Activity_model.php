<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Activities
 *
 * Provides a simple and consistent way to record and display user-related
 * activities in both core and custom modules.
 *
 * @package    Bonfire\Modules\Activities\Models\Activity_model
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/activities
 *
 */
class Activity_model extends BF_Model
{
	/**
	 * @var string Name of the table
	 */
	protected $table_name = 'activities';

	/**
	 * @var string Name of the primary key
	 */
	protected $key = 'activity_id';

	/**
	 * @var bool Whether to use soft deletes
	 */
	protected $soft_deletes = true;

	/**
	 * @var string The date format to use
	 */
	protected $date_format = 'datetime';

	/**
	 * @var bool Set the created time automatically on a new record
	 */
	protected $set_created = true;

	/**
	 * @var bool Set the modified time automatically on editing a record
	 */
	protected $set_modified = false;

	//--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('activities/activities');
    }

    public function find_all($user_id ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*, p."NIP_BARU", p."NAMA"');
		}
		if($user_id!=""){
			$this->db->where('user_id',$user_id);
		}
        $this->db->join('users u','u.id = activities.user_id');
        $this->db->join('pegawai p','p.NIP_BARU = u.username');
        $this->db->order_by('activity_id','desc');
		return parent::find_all();
	}

    public function count_all($user_id ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($user_id!=""){
			$this->db->where('user_id',$user_id);
		}
        $this->db->join('users u','u.id = activities.user_id');
        $this->db->join('pegawai p','p.NIP_BARU = u.username');
		return parent::count_all();
	}

	/**
	 * Return all activities created by one or more modules.
	 *
	 * @param string[]|string $modules Module name(s)
	 *
	 * @return bool/array An array of activity objects, or false
	 */
	public function find_by_module($modules = array())
	{
		if (empty($modules)) {
			$this->error = lang('activities_model_no_module');
			return false;
		}

        if (! is_array($modules)) {
			$modules = array($modules);
		}

        if (! class_exists('user_model')) {
            $this->load->model('users/user_model');
        }

		return $this->select(array(
                                'activity_id',
                                "{$this->table_name}.user_id",
                                'activity',
                                'module',
                                "{$this->table_name}.{$this->created_field}",
                                'display_name',
                                'username',
                                'email',
                                'last_login',
                            ))
                    ->where_in('module', $modules)
                    ->where("{$this->table_name}.{$this->deleted_field}", 0)
                    ->join($this->user_model->get_table(), "{$this->table_name}.user_id = " . $this->user_model->get_table() . '.' . $this->user_model->get_key(), 'left')
                    ->find_all();
	}

    /**
     * Find the top modules
     *
     * @param Number $limit The number of modules to return
     *
     * @return Array    An array of results
     */
    public function findTopModules($limit = 5)
    {
        return $this->select(array(
                                'module',
                                'COUNT(module) AS activity_count',
                            ))
                    ->group_by('module')
                    ->where("{$this->table_name}.{$this->deleted_field}", 0)
                    ->limit($limit)
                    ->order_by('activity_count', 'desc')
                    ->find_all();
    }

    /**
     * Find the top users
     *
     * @param Number $limit The number of users to return
     *
     * @return Array    An array of results
     */
    public function findTopUsers($limit = 5)
    {
        if (! class_exists('user_model')) {
            $this->load->model('users/user_model');
        }

        // Fields in the select list must also be in the group by clause for
        // compatibility with most databases other than MySQL.
        $usersFields = array('username', 'user_id');

        return $this->select(array_merge($usersFields, array('COUNT(user_id) AS activity_count')))
                    ->where("{$this->table_name}.{$this->deleted_field}", 0)
                    ->join($this->user_model->get_table(), "{$this->table_name}.user_id = " . $this->user_model->get_table() . '.' . $this->user_model->get_key(), 'left')
                    ->group_by($usersFields)
                    ->order_by('activity_count', 'desc')
                    ->limit($limit)
                    ->find_all();
    }

    public function get_modul_all()
    {
        $this->db->select('module');
        $this->db->distinct('module');
        $this->db->order_by('module');
        return parent::find_all();
    }
}
/* /activities/models/activity_model.php */
