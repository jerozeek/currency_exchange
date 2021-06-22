<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KycTable extends Migration
{
	public function up()
	{
        $fields = [
            'user_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'first_name'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'middle_name'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'last_name'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'phone'       => [
                'type'       => 'varchar',
                'constraint' => 20,
                'null'       => true
            ],

            'date_of_birth'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'address'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'city'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'state'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'country'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'id_type'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'id_number'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'id_upload'       => [
                'type'       => 'varchar',
                'constraint' => 200,
                'null'       => true
            ],

            'approved'       => [
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
        $this->forge->createTable('kyc');
	}

	public function down()
	{
		$this->forge->dropTable('kyc');
	}
}
