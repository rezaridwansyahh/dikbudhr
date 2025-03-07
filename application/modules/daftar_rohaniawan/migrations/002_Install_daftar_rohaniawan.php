<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_daftar_rohaniawan extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'daftar_rohaniawan';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'id' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'nip' => array(
            'type'       => 'VARCHAR',
            'constraint' => 30,
            'null'       => true,
        ),
        'nama' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'jabatan' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'agama' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'aktif' => array(
            'type'       => 'VARCHAR',
            'constraint' => 5,
            'null'       => true,
        ),
        'pangkat_gol' => array(
            'type'       => 'VARCHAR',
            'constraint' => 30,
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