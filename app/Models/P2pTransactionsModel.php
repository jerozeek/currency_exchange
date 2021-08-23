<?php

namespace App\Models;

use App\Entities\P2pTransactionsEntity;
use CodeIgniter\Model;

class P2pTransactionsModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'p2p_transactions';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = P2pTransactionsEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'buyer_id','seller_id','p_id','amount','exchange_rate','charges','currency','proof_upload','sender_name',
        'status','transaction_state','purchase_type','created_at','updated_at','deleted_at','request_time'
    ];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	public function create(array $data)
    {
        try {
           return $this->insert($data);
        } catch (\ReflectionException $e) {
            return $e->getMessage();
        }
    }

    public function delineRequest($id)
    {
        $transactions = $this->find($id);
        $transactions->status = 'declined';
        $this->save($transactions);
    }
    public function approveRequest($id)
    {
        $transactions = $this->find($id);
        $transactions->status = 'approve';
        $this->save($transactions);
    }

    public function buyerConfirmPayment(array $data):bool
    {
        return $this->update($data['id'],[
            'transaction_state'     => 'paid',
            'sender_name'           => $data['sender'],
            'currency'              => $data['currency'],
            'proof_upload'          => $data['proof'],
            'updated_at'            => date('Y-m-d H:i:s')
        ]);
    }

    public function closeTransaction($id):bool
    {
        return $this->update($id,[
            'status'     => 'closed',
        ]);
    }
}
