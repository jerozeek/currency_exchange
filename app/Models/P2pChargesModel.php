<?php

namespace App\Models;

use App\Entities\P2pChargesEntity;
use CodeIgniter\Model;

class P2pChargesModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'p2p_charges';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = P2pChargesEntity::class;
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'p_id','amount','status','created_at','updated_at','deleted_at'
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

    public function removeFunds($p_id,$amount)
    {
        $this->set(["amount","amount-$amount",false, 'status' => 'success'])->where("p_id",$p_id)->update();
    }

    public function setSuccess($p_id)
    {
        $this->set("status","success")->where("p_id",$p_id)->update();
    }
}
