<?php
namespace App\Controllers\P2p;

use App\Middleware\Auth;
use App\Models\KycModel;
use App\Models\P2pModel;
use App\Models\P2pTransactionsModel;
use App\Models\WalletModel;
use App\Services\Token;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class P2pController extends ResourceController
{

    use ResponseTrait;


    private Auth $auth;
    private P2pModel $p2pModel;
    private P2pTransactionsModel $p2pTransaction;
    private WalletModel $walletModel;
    private KycModel $kycModel;

    public function __construct()
    {
        $this->auth             = new Auth();
        $this->p2pModel         = new P2pModel();
        $this->p2pTransaction   = new P2pTransactionsModel();
        $this->walletModel      = new WalletModel();
        $this->kycModel         = new KycModel();
    }

    public function saleRequest()
    {
        if ($this->auth->check())
        {
            $data           = (new Token())->getJsonData();
            $walletDetails  = $this->walletModel->getWallet($this->auth->Users->id);

            //check if he has his KYC approved or Not
            if (!($this->kycModel->getInfo($this->auth->Users->id)))
            {
                return $this->fail('Please submit KYC for approval');
            }

            if ($this->kycModel->getInfo($this->auth->Users->id)->approved !== '1')
            {
                return $this->fail('Please submit KYC for approval');
            }

            if (!array_key_exists('from',$data))
            {
                return $this->fail('Please select the wallet currency');
            }

            if (!array_key_exists('to',$data))
            {
                return $this->fail('Please select the sales currency');
            }

            if (!array_key_exists('min_amount',$data))
            {
                return $this->fail('Please enter the minimum amount');
            }

            if (!array_key_exists('max_amount',$data))
            {
                return $this->fail('Please enter the maximum amount');
            }

            if ($data['to'] !== 'naira')
            {
                if (!array_key_exists('sort_code',$data))
                {
                    return $this->fail('Please enter sort code');
                }
                else
                {
                    $sort = $data['sort_code'];
                }
            }
            else
            {
                $sort = NULL;
            }

            if (!array_key_exists('account_number',$data))
            {
                return $this->fail('Account number is required');
            }

            if (!array_key_exists('account_name',$data))
            {
                return $this->fail('Account name is required');
            }

            if (!array_key_exists('bank_name',$data))
            {
                return $this->fail('Bank name is required');
            }

            if (!array_key_exists('rate',$data))
            {
                return $this->fail('Sales rate is required');
            }
            elseif ($data['rate'] < 0)
            {
                return $this->fail('Invalid rate amount');
            }

            //check the amount unit
            if ($data['min_amount'] > $data['max_amount'])
            {
                return $this->fail('Minimum amount cannot be greater than the maximum amount');
            }
            $from = $data['from'];

            //check for the insufficient balance
            if ($walletDetails->$from < $data['min_amount'])
            {
                return $this->fail('Insufficient funds');
            }
            elseif ($walletDetails->$from < $data['max_amount'])
            {
                return $this->fail('Insufficient funds');
            }

            $p2pID = $this->p2pModel->create([
                'user_id'           => $this->auth->Users->id,
                'min_amount'        => $data['min_amount'],
                'max_amount'        => $data['max_amount'],
                'currency_from'     => $data['from'],
                'currency_to'       => $data['to'],
                'exchange_rate'     => $data['rate'],
                'account_name'      => $data['account_name'],
                'account_number'    => $data['account_number'],
                'bank_name'         => $data['bank_name'],
                'sort_code'         => $sort,
                'created_at'        => date('Y-m-d H:i:s')
            ]);

            if ($p2pID)
            {
                //debit the maximum amount from the seller wallet
                $this->walletModel->debitWallet($this->auth->Users->id,$data['from'],$data['max_amount']);

                return $this->respond([
                    'status'    => true,
                    'message'   => 'Request was successfully submitted'
                ]);
            }

            return $this->fail('Something went wrong, Please try again later');
        }

        return $this->failUnauthorized();
    }


    public function saleList()
    {
        if ($this->auth->check())
        {
            $activeSales = $this->p2pModel->getOrderList($this->auth->Users->id);
            if (count($activeSales))
            {
                $result = [];

                foreach ($activeSales as $sale)
                {
                    $result[] = [
                        'id'                => $sale->id,
                        'min_amount'        => $sale->min_amount,
                        'max_amount'        => $sale->max_amount,
                        'currency'          => $sale->currency_from,
                        'sale_currency'     => $sale->currency_to,
                        'exchange_rate'     => $sale->exchange_rate,
                        'account_name'      => $sale->account_name,
                        'account_number'    => $sale->account_number,
                        'bank_name'         => $sale->bank_name,
                        'sort_code'         => $sale->sort_code,
                        'status'            => $sale->status,
                        'created_at'        => $sale->created_at
                    ];

                }

                return $this->respond([
                    'status'    => true,
                    'data'      => $result
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }


    public function getTransactionList($p2p_id)
    {

    }




}