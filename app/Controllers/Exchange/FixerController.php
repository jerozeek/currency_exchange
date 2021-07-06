<?php

namespace App\Controllers\Exchange;

use App\Middleware\Auth;
use App\Models\ExchangeModel;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
use App\Models\WalletModel;
use App\Services\Exchange;
use App\Services\Token;
use CodeIgniter\RESTful\ResourceController;

class FixerController extends ResourceController
{

    private Auth $auth;
    private UsersModel $userModel;
    private WalletModel $wallet;
    private Exchange $exchange;
    private ExchangeModel $exchangeModel;
    private NotificationsModel $notifier;

    public function __construct()
    {
        $this->auth             = new Auth();
        $this->userModel        = new UsersModel();
        $this->wallet           = new WalletModel();
        $this->exchange         = new Exchange();
        $this->exchangeModel    = new ExchangeModel();
        $this->notifier         = new NotificationsModel();
    }

    public function currencyExchange()
    {
        if ($this->auth->check())
        {
            $data = (new Token())->getJsonData();

            if (!array_key_exists('from',$data))
            {
                return $this->fail('Please select currency from');
            }

            if (!array_key_exists('to',$data))
            {
                return $this->fail('Please select currency to');
            }

            if (!array_key_exists('amount',$data))
            {
                return $this->fail('Please select currency amount');
            }

            $charges        = exchangeCharges;
            $total_amount   = $data['amount']+$charges;
            $from_currency  = $data['from'];

            //get wallet balance
            $walletBalance  = $this->wallet->getWallet($this->auth->Users->id)->$from_currency;

            if ($total_amount > $walletBalance)
            {
                return $this->fail('Insufficient funds');
            }

            //convert to readable currency sign
            $from       = $this->currencySigns($data['from']);
            $to         = $this->currencySigns($data['to']);
            $amount     = $data['amount'];

            //do the exchange
            $convertedAmount      = $this->exchange->conversion($from,$to,$amount);

            if ($convertedAmount)
            {

                if (!($convertedAmount  >= 1))
                {
                    return $this->fail('Conversion cannot be done. Please choose a higher amount');
                }

                //debit the from wallet
                $this->wallet->debitWallet($this->auth->Users->id,$data['from'],$amount);

                //create the to wallet
                $this->wallet->fundWallet($this->auth->Users->id,$data['to'],$convertedAmount);

                //log the transaction on exchange
                $this->exchangeModel->create([
                   'user_id'            => $this->auth->Users->id,
                   'amount'             => $amount,
                   'charges'            => $charges,
                   'from'               => $from,
                   'to'                 => $to,
                   'converted_amount'   => $convertedAmount,
                   'created_at'         => date('Y-m-d H:i:s')
                ]);

                //send a notification
                $message = 'Your exchange from: '.$from.' currency to: '.$to.' currency was successful';
                $this->notifier->setMessage($this->auth->Users->player_id,$this->auth->Users->id,$message);

                return $this->respond([
                    'status'    => true,
                    'message'   => 'Conversion was done successfully'
                ]);

            }

            return $this->fail('Failed to convert currency. Please try again later');
        }

        return $this->failUnauthorized();
    }

    private function currencySigns($currency)
    {
        $allowed = [
            'naira'     => 'NGN',
            'pound'     => 'GBP',
            'euro'      => 'EUR',
            'dollar'    => 'USD'
        ];

        foreach ($allowed as $key => $value)
        {
            if ($key == $currency)
            {
                return $value;
            }
        }

        return null;
    }

    public function getExchange()
    {
        if ($this->auth->check())
        {
            $exchanges = $this->exchangeModel->where(['user_id' => $this->auth->Users->id])->orderBy('id','DESC')->get()->getResult();
            if (count($exchanges) > 0)
            {
                return $this->respond([
                    'status'    => true,
                    'message'   => 'Exchange found',
                    'data'      => $this->exchangeList($exchanges)
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    private function exchangeList(array $exchanges):array
    {
        $output = [];

        foreach ($exchanges as $exchange)
        {
            $output[] = [
                'amount'        => round($exchange->amount,2),
                'from'          => $exchange->from,
                'to'            => $exchange->to,
                'charges'       => round($exchange->charges,2),
                'converted_to'  => round($exchange->converted_amount,2),
                'date'          => date('d M, Y', strtotime($exchange->created_at)),
            ];
        }

        return $output;
    }

}