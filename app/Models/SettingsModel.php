<?php

namespace App\Models;

use App\Entities\SettingsEntity;
use CodeIgniter\Model;

class SettingsModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'settings';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = SettingsEntity::class;
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'daily_deposit_limit','daily_withdrawal_limit',
        'daily_deposit_limit_kyc','daily_withdrawal_limit_kyc',
        'created_at','updated_at','deleted_at'
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

	public function getSettings()
    {
        return $this->where(['id' => 1])->get()->getRow();
    }
}
