<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PermissionsTable extends Migration
{
	public function up()
	{
        $fields = [

            'user_id'       => [
                'type'          => 'int',
                'constraint'    => 100,
            ],

            'transactions'       => [
                'type'          => 'text',
                'null'          => true
            ],

            'users'       => [
                'type'          => 'text',
                'null'          => true
            ],

            'kyc'       => [
                'type'          => 'text',
                'null'          => true
            ],

            'settings'       => [
                'type'          => 'text',
                'null'          => true
            ],

            'permissions'       => [
                'type'          => 'text',
                'null'          => true
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
        $this->forge->createTable('permissions');
	}

	public function down()
	{
		$this->forge->dropTable('permissions');
	}
}
