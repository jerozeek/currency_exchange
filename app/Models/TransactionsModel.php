<?php

namespace App\Models;

use App\Entities\TransactionsEntity;
use CodeIgniter\Model;

class TransactionsModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'transactions';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = TransactionsEntity::class;
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'user_id','email','amount','charges','currency','country','payment_method','ip_address','intent_id',
        'status','transaction_type','transaction','reference','beneficiary_email','beneficiary_fullname',
        'beneficiary_account_number','bank_name','beneficiary_account_name','created_at','updated_at','deleted_at',
        'card_type','card_number','refunded','receipt_url','gateway','txn_id','sender_name','trans_date'
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

    public function create($data)
    {
        return $this->insert($data);
    }

    public function all_allowed_intent()
    {
        $data               = $this->findAll();
        $allowed_intents    = [];
        foreach ($data as $item)
        {
            $allowed_intents[] = $item->intent_id;
        }

        return $allowed_intents;
    }

    public function retrieve_payment($intent)
    {
        return $this->where('intent_id',$intent)->get()->getRow();
    }

    public function updateTransaction($data, $status)
    {
        if ($data['gateway'] == 'stripe')
        {
            if ($status === 'succeeded')
            {
                $wallet = new WalletModel();
                $wallet->updateBalance($data['user_id'], $data['amount'],$data['currency']);
                $status = 'success';
            }
            else {
                $status = 'failed';
            }
        }

        return $this->set([
            'amount'        =>  $data['amount'],
            'card_type'     =>  $data['card_type'],
            'card_number'   =>  $data['card_number'],
            'refunded'      =>  $data['refunded'],
            'receipt_url'   =>  $data['receipt_url'],
            'txn_id'        =>  $data['transaction_id'],
            'status'        =>  $status
        ])
            ->where('id',$data['id'])
            ->update();
    }

    public function updateTransactionStatus($transaction_id,$status)
    {
        $transaction            = $this->find($transaction_id);
        $transaction->status    = $status;
        $this->save($transaction);
    }

    public function fetchTransactions($user_id):array
    {
        return $this->where(['user_id' => $user_id, 'status' => 'success','transaction_type !=' => 'exchange'])->orderBy('id','DESC')->get()->getResult();
    }

    public function todayWithdrawalTotal($id):int
    {
        $today  = date('Y-m-d');
        $data   = $this->where(['user_id' => $id, 'status' => 'success','trans_date' => $today, 'transaction_type' => 'transfer'])->get()->getResult();

        if (count($data) > 0)
        {
            $total = 0;

            foreach ($data as $value)
            {
                $total += $value->amount;
            }

            return $total;
        }

        return 0;
    }

    public function todayDepositTotal($id):int
    {
        $today  = date('Y-m-d');
        $data   = $this->where(['user_id' => $id, 'status' => 'success','trans_date' => $today, 'transaction_type' => 'deposit'])->get()->getResult();

        if (count($data) > 0)
        {
            $total = 0;

            foreach ($data as $value)
            {
                $total += $value->amount;
            }

            return $total;
        }

        return 0;
    }
}
