<?php

namespace App\Models;

use App\Entities\P2pEntity;
use CodeIgniter\Model;

class P2pModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'p2p';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = P2pEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'user_id','min_amount','max_amount','currency_from','currency_to','exchange_rate','account_name',
        'account_number','bank_name','sort_code','status','created_at','updated_at','deleted_at'
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
        return $this->insert($data);
    }

    public function getOrderList(?int $id):array
    {
        return $this->where(['user_id' => $id])->orderBy('id','DESC')->get()->getResult();
    }

    public function getAllList():array
    {
        return $this->where(['status' => 'open'])->get()->getResult();
    }

    public function getDetails($id)
    {
        return $this->where(['id' => $id, 'status' => 'open'])->get()->getRow();
    }
    public function getBankDetails($p_id)
    {
        return $this->find($p_id);
    }

    public function closeP2p($id):bool
    {
        return $this->update($id,['status' => 'close']);
    }

    public function handPartPurchase($p_id, $remainingBalance):bool
    {
        $info = $this->find($p_id);

        if ($remainingBalance > $info->min_amount)
        {
            $min_amount = $remainingBalance;
        }
        else
        {
            $min_amount = $info->min_amount;
        }

        return $this->update($p_id,[
            'max_amount'    => $remainingBalance,
            'min_amount'    => $min_amount
        ]);
    }
}
