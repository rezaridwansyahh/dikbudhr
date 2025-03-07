<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_izin extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'izin';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'ID' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'NIP_PNS' => array(
            'type'       => 'VARCHAR',
            'constraint' => 18,
            'null'       => false,
        ),
        'NAMA' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'JABATAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'UNIT_KERJA' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
        ),
        'MASA_KERJA_TAHUN' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'MASA_KERJA_BULAN' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'GAJI_POKOK' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'KODE_IZIN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 5,
            'null'       => false,
        ),
        'DARI_TANGGAL' => array(
            'type'       => 'DATE',
            'null'       => true,
            'default'    => '0000-00-00',
        ),
        'SAMPAI_TANGGAL' => array(
            'type'       => 'DATE',
            'null'       => true,
            'default'    => '0000-00-00',
        ),
        'TAHUN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 4,
            'null'       => true,
        ),
        'JUMLAH' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'SATUAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 10,
            'null'       => true,
        ),
        'KETERANGAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'ALAMAT_SELAMA_CUTI' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'TLP_SELAMA_CUTI' => array(
            'type'       => 'VARCHAR',
            'constraint' => 20,
            'null'       => true,
        ),
        'TGL_DIBUAT' => array(
            'type'       => 'DATE',
            'null'       => true,
            'default'    => '0000-00-00',
        ),
        'LAMPIRAN_FILE' => array(
            'type'       => 'VARCHAR',
            'constraint' => 50,
            'null'       => true,
        ),
        'SISA_CUTI_TAHUN_N2' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'SISA_CUTI_TAHUN_N1' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'SISA_CUTI_TAHUN_N' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'ANAK_KE' => array(
            'type'       => 'VARCHAR',
            'constraint' => 1,
            'null'       => true,
        ),
        'LAMA_KERJA_TAHUN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 2,
            'null'       => true,
        ),
        'NIP_ATASAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 25,
            'null'       => true,
        ),
        'STATUS_ATASAN' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'CATATAN_ATASAN' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ),
        'NIP_PYBMC' => array(
            'type'       => 'VARCHAR',
            'constraint' => 25,
            'null'       => true,
        ),
        'STATUS_PYBMC' => array(
            'type'       => 'INT',
            'null'       => true,
        ),
        'CATATAN_PYBMC' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
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