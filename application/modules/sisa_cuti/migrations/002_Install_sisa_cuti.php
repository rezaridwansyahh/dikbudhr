<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_sisa_cuti extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'sisa_cuti';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'ID' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'PNS_NIP' => array(
            'type'       => 'BIGINT',
            'constraint' => 18,
            'null'       => false,
        ),
        'TAHUN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 4,
            'null'       => false,
        ),
        'SISA' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'STATUS_ACTIVE' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
	);

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		$this->dbforge->add_field($this->fields);
		$this->dbforge->add_key('ID', true);
		$this->dbforge->create_table($this->table_name);
	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dbforge->drop_table($this->table_name);
	}
}