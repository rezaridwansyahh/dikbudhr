<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_jenis_izin extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'jenis_izin';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'ID' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'KODE' => array(
            'type'       => 'VARCHAR',
            'constraint' => 5,
            'null'       => false,
        ),
        'NAMA_IZIN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 50,
            'null'       => false,
        ),
        'KETERANGAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'LEVEL_PERSETUJUAN' => array(
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