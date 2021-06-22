<?php

namespace App\Models;

use App\Entities\KycEntity;
use CodeIgniter\Model;

class KycModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'kyc';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = KycEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'user_id','first_name','middle_name','last_name','phone','date_of_birth','address','city','state',
        'country','id_number','id_upload','approved','created_at','updated_at','deleted_at','id_type'
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
        $user = $this->where(['user_id' => $data['user_id']])->get()->getRow();
        if ($user)
        {
            return null;
        }

        return $this->insert($data);
    }

    public function getInfo($id)
    {
        return $this->where(['user_id' => $id])->get()->getRow();
    }
}
