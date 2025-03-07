<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_tte_master_proses extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'tte_master_proses';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'id' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'nama_proses' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => false,
        ),
        'template_sk' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'penandatangan_sk' => array(
            'type'       => 'VARCHAR',
            'constraint' => 32,
            'null'       => true,
        ),
        'keterangan_proses' => array(
            'type'       => 'TEXT',
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
		$this->dbforge->add_key('id', true);
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