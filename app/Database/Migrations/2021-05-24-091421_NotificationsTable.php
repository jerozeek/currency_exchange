<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NotificationsTable extends Migration
{
	public function up()
	{
        $fields = [
            'user_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'player_id'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'      => true
            ],

            'message'       => [
                'type'       => 'text',
                'null'      => true
            ],

            'status'        => [
                'type'       => 'int',
                'constraint' => 2,
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
        $this->forge->createTable('notifications');
	}

	public function down()
	{
		$this->forge->dropTable('notifications');
	}
}
