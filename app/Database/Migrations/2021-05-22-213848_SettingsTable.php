<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SettingsTable extends Migration
{
	public function up()
	{
        $fields = [

            'daily_deposit_limit'       => [
                'type'          => 'int',
                'constraint'    => 100,
                'default'       => 100
            ],

            'daily_withdrawal_limit'       => [
                'type'          => 'int',
                'constraint'    => 100,
                'default'       => DAILY_WITHDRAWAL_LIMIT
            ],

            'daily_deposit_limit_kyc'       => [
                'type'          => 'int',
                'constraint'    => 100,
                'default'       => 5000
            ],

            'daily_withdrawal_limit_kyc'       => [
                'type'          => 'int',
                'constraint'    => 100,
                'default'       => 1000000
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
        $this->forge->createTable('settings');
	}

	public function down()
	{
		$this->forge->dropTable('settings');
	}
}
