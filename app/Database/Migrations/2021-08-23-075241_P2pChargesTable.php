<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class P2pChargesTable extends Migration
{
	public function up()
	{
        $fields = [

            'p_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'amount'        => [
                'type'       => 'double',
            ],

            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['pending','success','closed'],
                'default'        => 'pending',
            ],

            'created_at' => [
                'type' => 'datetime',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],

            'deleted_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        ];
        $this->forge->addField('id');
        $this->forge->addField($fields);
        $this->forge->addForeignKey('p_id','p2p','id','CASCADE','CASCADE');
        $this->forge->createTable('p2p_charges');
	}

	public function down()
	{
		$this->forge->dropTable('p2p_charges');
	}
}
