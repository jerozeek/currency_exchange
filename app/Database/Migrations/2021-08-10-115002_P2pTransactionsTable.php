<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class P2pTransactionsTable extends Migration
{
	public function up()
	{
        $fields = [

            'buyer_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'seller_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'p_id'       => [
                'type'       => 'int',
                'constraint' => 100,
            ],

            'amount'        => [
                'type'       => 'double',
            ],

            'charges'        => [
                'type'       => 'double',
            ],

            'currency'        => [
                'type'       => 'varchar',
                'constraint' => 100,
            ],

            'proof_upload'        => [
                'type'       => 'varchar',
                'constraint' => 100,
            ],

            'sender_name'       => [
                'type'       => 'varchar',
                'constraint' => 100,
                'null'    => true
            ],

            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['pending','approve','declined','closed'],
                'default'        => 'pending',
            ],

            'transaction_state'      => [
                'type'           => 'ENUM',
                'constraint'     => ['paid','not_paid'],
                'default'        => 'not_paid',
            ],

            'purchase_type'      => [
                'type'           => 'ENUM',
                'constraint'     => ['full','part'],
                'default'        => 'part',
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
        $this->forge->addForeignKey('buyer_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('seller_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('p_id','p2p','id','CASCADE','CASCADE');
        $this->forge->createTable('p2p_transactions');
	}

	public function down()
	{
		$this->forge->dropTable('p2p_transactions');
	}
}
