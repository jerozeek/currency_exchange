<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class P2pTable extends Migration
{
	public function up()
	{
        $fields = [

            'user_id'       => [
                'type'          => 'int',
                'constraint'    => 100,
            ],

            'min_amount'        => [
                'type'          => 'double',
            ],

            'max_amount'        => [
                'type'          => 'double',
            ],

            'currency_from'        => [
                'type'          => 'varchar',
                'constraint'    => 100,
            ],

            'currency_to'        => [
                'type'          => 'varchar',
                'constraint'    => 100,
            ],

            'exchange_rate'        => [
                'type'          => 'double',
            ],

            'account_name'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],

            'account_number'       => [
                'type'          => 'varchar',
                'constraint'    => 200,
                'null'          => true
            ],

            'bank_name'       => [
                'type'          => 'varchar',
                'constraint'    => 200,
                'null'          => true
            ],

            'sort_code'       => [
                'type'          => 'varchar',
                'constraint'    => 200,
                'null'          => true
            ],

            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['open', 'close', 'in_progress','requested'],
                'default'        => 'open',
            ],

            'created_at' => [
                'type'          => 'datetime',
                'null'          => true,
            ],

            'updated_at' => [
                'type'          => 'datetime',
                'null'          => true,
            ],
            'deleted_at' => [
                'type'          => 'datetime',
                'null'          => true,
            ],
        ];
        $this->forge->addField('id');
        $this->forge->addField($fields);
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->createTable('p2p');
	}

	public function down()
	{
		$this->forge->dropTable('p2p');
	}
}
