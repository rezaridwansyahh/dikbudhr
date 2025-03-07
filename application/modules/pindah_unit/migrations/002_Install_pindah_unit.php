<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_pindah_unit extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'pindah_unit';

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
            'constraint' => 18,
            'null'       => false,
        ),
        'SURAT_PERMOHONAN_PINDAH' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'UNIT_ASAL' => array(
            'type'       => 'VARCHAR',
            'constraint' => 32,
            'null'       => true,
        ),
        'UNIT_TUJUAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 32,
            'null'       => true,
        ),
        'SURAT_PERNYATAAN_MELEPAS' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'SK_KP_TERAKHIR' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'SK_JABATAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'SKP' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'SK_TUNKIN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'SURAT_PERNYATAAN_MENERIMA' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'NO_SK_PINDAH' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'TANGGAL_SK_PINDAH' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'FILE_SK' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
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