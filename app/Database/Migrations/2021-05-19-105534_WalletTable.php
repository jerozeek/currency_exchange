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
                'type'       => 'int',
                'constraint' => 10,
                'default'    => 0
            ],

            'euro'        => [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => 0
            ],

            'pound'        => [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => 0
            ],

            'naira'        => [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => 0
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
