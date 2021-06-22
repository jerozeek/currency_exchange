<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransactionsTable extends Migration
{
	public function up()
	{
        $fields = [
            'user_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'email'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true,
            ],

            'amount'       => [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => 0
            ],

            'charges'       => [
                'type'       => 'int',
                'constraint' => 10,
                'default'    => 0
            ],

            'currency'       => [
                'type'          => 'varchar',
                'constraint'    => 10,
                'null'          => true,
            ],

            'country'       => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
            ],

            'payment_method'       => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
            ],

            'ip_address'       => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
            ],

            'intent_id'       => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
            ],

            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['success', 'pending', 'failed'],
                'default'        => 'pending',
            ],

            'transaction_type'      => [
                'type'           => 'ENUM',
                'constraint'     => ['deposit', 'transfer','exchange'],
                'default'        => 'deposit',
            ],

            'transaction'      => [
                'type'           => 'ENUM',
                'constraint'     => ['in_app', 'out_app'],
                'default'        => 'in_app',
            ],

            'reference'       => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true
            ],

            'txn_id'       => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true
            ],

            'sender_name'       => [
                'type'          => 'varchar',
                'constraint'    => 200,
                'null'          => true
            ],

            'card_type'       => [
                'type'          => 'varchar',
                'constraint'    => 20,
                'null'          => true
            ],


            'card_number'       => [
                'type'          => 'varchar',
                'constraint'    => 20,
                'null'          => true
            ],



            'refunded'       => [
                'type'          => 'varchar',
                'constraint'    => 20,
                'null'          => true
            ],


            'receipt_url'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],


            'gateway'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],


            'beneficiary_email'       => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],

            'beneficiary_fullname'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'       => true
            ],

            'beneficiary_account_name'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'    => true
            ],

            'beneficiary_account_number'       => [
                'type'       => 'varchar',
                'constraint' => 20,
                'null'    => true
            ],

            'bank_name'       => [
                'type'       => 'varchar',
                'constraint' => 20,
                'null'    => true
            ],

            'trans_date' => [
                'type' => 'date',
                'null' => true,
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
        $this->forge->createTable('transactions');
	}

	public function down()
	{
		$this->forge->dropTable('transactions');
	}
}
