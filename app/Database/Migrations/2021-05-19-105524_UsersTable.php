<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTable extends Migration
{
	public function up()
	{
        $fields = [
            'customer_id'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true,
            ],
            'first_name'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
            ],
            'last_name'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'default'       => 'null'
            ],
            'email'        => [
                'type'          => 'varchar',
                'constraint'    => 100,
            ],
            'password'        => [
                'type'          => 'varchar',
                'constraint'    => 500,
            ],
            'phone'      => [
                'type'          => 'varchar',
                'constraint'    => 20,
            ],

            'expire'      => [
                'type'          => 'varchar',
                'constraint'    => 50,
            ],
            'country'      => [
                'type'          => 'varchar',
                'constraint'    => 50,
            ],
            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'deactivated', 'not_activated'],
                'default'        => 'not_activated',
            ],

            'account_type'      => [
                'type'           => 'ENUM',
                'constraint'     => ['admin', 'user'],
                'default'        => 'user',
            ],

            'activation_code'      => [
                'type'          => 'varchar',
                'constraint'    => 4,
            ],

            'active_token'      => [
                'type'          => 'text',
                'null'          => true,
            ],

            'player_id'      => [
                'type'          => 'text',
                'null'          => true,
            ],

            'ip_address' => [
                'type'          => 'text',
                'null'          => true,
            ],

            'profile_image' => [
                'type'          => 'text',
                'null'          => true,
            ],

            'created_at' => [
                'type'          => 'datetime',
                'null'          => true,
            ],
            'updated_at' => [
                'type'          => 'datetime',
                'null'          => true,
            ],
            'last_login' => [
                'type'          => 'varchar',
                'constraint'    => 40,
                'null'          => true,
            ],
            'login_count' => [
                'type'          => 'int',
                'constraint'    => 40,
                'default'       => 0,
            ],
            'deleted_at' => [
                'type'          => 'datetime',
                'null'          => true,
            ],
        ];
        $this->forge->addField('id');
        $this->forge->addField($fields);

        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('phone');
        $this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
