<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExchangeTable extends Migration
{
	public function up()
	{
        $fields = [
            'user_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'amount'       => [
                'type'       => 'double',
            ],

            'converted_amount'       => [
                'type'       => 'double',
            ],

            'charges'        => [
                'type'       => 'double',
            ],

            'from'        => [
                'type'       => 'varchar',
                'constraint' => 20,
                'null'       => true,
            ],

            'to'        => [
                'type'       => 'varchar',
                'constraint' => 20,
                'null'       => true,
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
        $this->forge->createTable('exchanges');
	}

	public function down()
	{
		$this->forge->dropTable('exchanges');
	}
}
