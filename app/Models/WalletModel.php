<?php

namespace App\Models;

use App\Entities\WalletEntity;
use CodeIgniter\Model;

class WalletModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'wallets';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = WalletEntity::class;
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'user_id','dollar','euro','pound','naira','created_at','updated_at','deleted_at'
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

	public function updateBalance($user_id,$amount,$currency)
    {
        if ($currency == 'usd')
        {
            $c = 'dollar';
        }

        if ($currency == 'ngn')
        {
            $c = 'naira';
        }

        if ($currency == 'eur')
        {
            $c = 'euro';
        }

        if ($currency == 'gbp')
        {
            $c = 'pound';
        }

        $this->set("$c","$c+$amount",false)->where(['user_id' => $user_id])->update();
    }

    public function getWallet($id)
    {
        return $this->where(['user_id' => $id])->get()->getRow();
    }

    public function debitWallet($id,$wallet,$amount):bool
    {
        return $this->set("$wallet","$wallet-$amount",false)->where(['user_id' => $id])->update();
    }

    public function fundWallet($id,$wallet,$amount)
    {
        $walletInfo = $this->where(['user_id' => $id])->get()->getRow();
        if ($walletInfo)
        {
            $walletDetails = $this->find($walletInfo->id);
            $walletDetails->$wallet = $walletDetails->$wallet + $amount;
            $this->save($walletDetails);
        }
    }
}
