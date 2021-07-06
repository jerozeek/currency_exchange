<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WalletTable extends Migration
{
	public function up()
	{
        $fields = [
            'user_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'dollar'       => [
                'type'       => 'double',
            ],

            'euro'        => [
                'type'       => 'double',
            ],

            'pound'        => [
                'type'       => 'double',
            ],

            'naira'        => [
                'type'       => 'double',
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
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->createTable('wallets');
	}

	public function down()
	{
		$this->forge->dropTable('wallets');
	}
}
