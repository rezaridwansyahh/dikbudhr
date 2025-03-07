<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_pengajuan_tubel extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'pengajuan_tubel';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'ID' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'NIP' => array(
            'type'       => 'VARCHAR',
            'constraint' => 30,
            'null'       => true,
        ),
        'NOMOR_USUL' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'TANGGAL_USUL' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'UNIVERSITAS' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'FAKULTAS' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'PRODI' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'BEASISWA' => array(
            'type'       => 'INT',
            'constraint' => 1,
            'null'       => true,
        ),
        'PEMBERI_BEASISWA' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'JENJANG' => array(
            'type'       => 'VARCHAR',
            'constraint' => 5,
            'null'       => true,
        ),
        'NEGARA' => array(
            'type'       => 'VARCHAR',
            'constraint' => 50,
            'null'       => true,
        ),
        'STATUS' => array(
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