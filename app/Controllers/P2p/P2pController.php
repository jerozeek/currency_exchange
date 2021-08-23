<?php
namespace App\Controllers\P2p;

use App\Middleware\Auth;
use App\Models\KycModel;
use App\Models\NotificationsModel;
use App\Models\P2pChargesModel;
use App\Models\P2pModel;
use App\Models\P2pTransactionsModel;
use App\Models\UsersModel;
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
    private NotificationsModel $notifier;
    private UsersModel $userModel;
    private P2pChargesModel $p2pCharges;

    public function __construct()
    {
        $this->auth             = new Auth();
        $this->p2pModel         = new P2pModel();
        $this->p2pTransaction   = new P2pTransactionsModel();
        $this->walletModel      = new WalletModel();
        $this->kycModel         = new KycModel();
        $this->notifier         = new NotificationsModel();
        $this->userModel        = new UsersModel();
        $this->p2pCharges       = new P2pChargesModel();
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
                return $this->fail('KYC have not yet been approval');
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

            //add the charges to the amount before submitting
            $amount             = P2P_CHARGES/100 * $data['max_amount'];
            $availableBalance   = $data['max_amount'] + $amount;

            if ($availableBalance > $walletDetails->$from)
            {
                return $this->fail('Insufficient funds. Please note that you will be charges '.P2P_CHARGES.' charges');
            }

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

                //send the charges amount out
                $this->p2pCharges->create([
                    'p_id'          => $p2pID,
                    'amount'        => $availableBalance,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);

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
            if (count($activeSales) > 0)
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


    public function getAllP2pList()
    {
        if ($this->auth->check())
        {
            $allP2p = $this->p2pModel->getAllList();
            if (count($allP2p) > 0)
            {
                $response = [];
                foreach ($allP2p as $value)
                {
                    $response[] = [
                        'id'                => $value->id,
                        'min_amount'        => $value->min_amount,
                        'max_amount'        => $value->max_amount,
                        'currency'          => $value->currency_from,
                        'sale_currency'     => $value->currency_to,
                        'exchange_rate'     => $value->exchange_rate,
                        'account_name'      => $value->account_name,
                        'account_number'    => $value->account_number,
                        'bank_name'         => $value->bank_name,
                        'sort_code'         => $value->sort_code,
                        'status'            => $value->status,
                        'created_at'        => $value->created_at
                    ];
                }

                return $this->respond([
                    'status'    => true,
                    'data'      => $response
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }


    public function requestToBuy()
    {
        if ($this->auth->check())
        {
            $data = (new Token())->getJsonData();

            //check if he has his KYC approved or Not
            if (!($this->kycModel->getInfo($this->auth->Users->id)))
            {
                return $this->fail('Please submit KYC for approval');
            }

            if ($this->kycModel->getInfo($this->auth->Users->id)->approved !== '1')
            {
                return $this->fail('KYC have not yet been approval');
            }

            if (!array_key_exists('p2p_id',$data))
            {
                return $this->fail('P2p id is required');
            }

            $p2p_details = $this->p2pModel->getDetails($data['p2p_id']);

            if (!$p2p_details)
            {
                return $this->fail('P2p details is no longer available');
            }

            if ($p2p_details->user_id === $this->auth->Users->id)
            {
                return $this->fail('Action not allowed');
            }

            if (!array_key_exists('amount',$data))
            {
                return $this->fail('Amount is required');
            }

            elseif ($data['amount'] < $p2p_details->min_amount)
            {
                return $this->fail('Amount cannot be less than the minimum amount required');
            }

            elseif (!$p2p_details->max_amount > $data['amount'])
            {
                return $this->fail('Amount cannot be greater than the maximum amount required');
            }

            if (!array_key_exists('exchange_rate',$data))
            {
                return $this->fail('Exchange amount is required');
            }

            //check if this buyer have submitted this request before
            $details = $this->p2pTransaction->where('p_id',$data['p2p_id'])->get()->getRow();
            if ($details)
            {
                if ($details->buyer_id == $this->auth->Users->id && $details->status == 'pending' || $details->status == 'approve')
                {
                    return $this->fail('You have already submitted a buy request which is under processing');
                }

                if ($details->seller_id == $this->auth->Users->id)
                {
                    return $this->fail('Action not allowed');
                }

            }

            $transaction_id = $this->p2pTransaction->create([
                'p_id'              => $data['p2p_id'],
                'seller_id'         => $p2p_details->user_id,
                'buyer_id'          => $this->auth->Users->id,
                'amount'            => $data['amount'],
                'exchange_rate'     => $data['exchange_rate'],
                'charges'           => P2P_CHARGES,
                'purchase_type'     => $p2p_details->max_amount == $data['amount'] ? 'full' : 'part',
                'currency'          => NULL,
                'request_time'      => strtotime('now'),
                'created_at'        => date('Y-m-d H:i:s')
            ]);

            if ($transaction_id)
            {
                $seller_id = $this->p2pModel->find($data['p2p_id'])->user_id;
                //send a push notification to the seller immediately
                //send a notification
                $seller = $this->userModel->find($seller_id);
                $message = 'A sales request have been made to your order. Please login and accept or decline request';
                $this->notifier->setMessage($seller->player_id,$seller->id,$message);

                return $this->respond([
                    'status'    => true,
                    'message'   => 'Request was sent successfully'
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    public function buyOrderList()
    {
        if ($this->auth->check())
        {
            $orderRequest = $this->p2pTransaction->where(['buyer_id' => $this->auth->Users->id])->orderBy('id','DESC')->get()->getResult();

            if (count($orderRequest) > 0)
            {
                $results =  [];
                $p2p =      [];
                foreach ($orderRequest as $request)
                {
                    if ($request->status === 'pending' || $request->status == 'approve')
                    {
                        $results[] = [
                            'id'                    => $request->id,
                            'p_id'                  => $request->p_id,
                            'amount'                => $request->amount,
                            'exchange_amount'       => $request->exchange_rate,
                            'status'                => $request->status,
                            'transaction_state'     => $request->transaction_state,
                            'purchase_type'         => $request->purchase_type,
                            'created_at'            => date('d M Y',strtotime($request->created_at))
                        ];

                        $p2p[] = $this->p2pModel->getBankDetails($request->p_id);
                    }
                }

                return $this->respond([
                    'status'        => true,
                    'data'          => [
                        'order'     => $results,
                        'p2p'       => $p2p
                    ]
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    public function getSalesOrder()
    {
        if ($this->auth->check())
        {
            $orders = $this->p2pTransaction->where(['seller_id' => $this->auth->Users->id])->get()->getResult();
            if (count($orders) > 0)
            {
                $results = [];
                foreach ($orders as $order)
                {
                    $buyer = $this->userModel->find($order->buyer_id);
                    $results = [
                        'id'                    => $order->id,
                        'p_id'                  => $order->p_id,
                        'buyer'                 => $buyer->first_name .' '. $buyer->last_name,
                        'amount'                => $order->amount,
                        'exchange_rate'         => $order->exchange_rate,
                        'charges'               => $order->charges,
                        'proof_upload'          => $order->proof_upload === null ? null : base_url("public/p2p/$order->proof_upload"),
                        'sender_name'           => $order->sender_name,
                        'status'                => $order->status,
                        'transaction_state'     => $order->transaction_state,
                        'purchase_type'         => $order->purchase_type,
                        'request_time'          => $order->request_time,
                        'created_at'            => date('d M Y',strtotime($order->created_at))
                    ];
                }

                return $this->respond([
                    'status'        => true,
                    'data'          => $results
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    public function orderResponse($order_id)
    {
        if ($this->auth->check())
        {
            if ($order_id)
            {
                $details = $this->p2pTransaction->find($order_id);

                if ($details && $details->seller_id === $this->auth->Users->id)
                {

                    $status = $this->request->getGet('status');
                    $message = '';

                    if ($status === 'approved')
                    {
                        $this->p2pTransaction->approveRequest($order_id);
                        $message = 'Order request have been approved by seller. You can now login to make the payment to seller account';
                    }

                    if ($status === 'declined')
                    {
                        $this->p2pTransaction->delineRequest($order_id);
                        $message = 'Your order request was declined by seller. Please login and make another request';
                    }

                    $buyer = $this->userModel->find($details->buyer_id);

                    if (!empty($message))
                    {
                        $this->notifier->setMessage($buyer->player_id,$buyer->id,$message);
                    }

                    return $this->respond([
                        'status'    => true,
                        'message'   => 'Action was successful'
                    ]);

                }

                return $this->failNotFound();
            }

        }

        return $this->failUnauthorized();
    }

    public function cancelOrder($order_id)
    {
        if ($this->auth->check())
        {
            $orderDetails = $this->p2pModel->find($order_id);
            $transactionDetails = $this->p2pTransaction->where('p_id',$order_id)->get()->getResult();
            if ($orderDetails && $orderDetails->user_id == $this->auth->Users->id)
            {
                if ($transactionDetails)
                {
                    foreach ($transactionDetails as $detail)
                    {
                        if ($detail->status === 'accepted' || $detail->status === 'pending' || $detail->transaction_state == 'paid')
                        {
                            return $this->fail('Order cannot be removed. Because you already have some pending orders');
                        }

                        //check if he has made a transaction before!!!!!!!
                        //debit the amount from the p2p_charges section!!!
                        $currentMax     = $orderDetails->max_amount;
                        $chargeAmount   = P2P_CHARGES/100 * $currentMax;
                        $this->p2pCharges->removeFunds($orderDetails->id,$chargeAmount);

                        //remove the order.....!!!!
                        $this->p2pModel->closeP2p($order_id);
                        //credit seller wallet back with funds........!!!!!

                        $this->walletModel->creditWallet($this->auth->Users->id,$orderDetails->currency_from,$orderDetails->max_amount);

                        return $this->respond([
                            'status'        => true,
                            'message'       => 'Order removed successfully'
                        ]);
                    }
                }
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    public function confirmPaymentToSeller()
    {
        if ($this->auth->check())
        {
            $id         = $this->request->getPost('id');
            $file       = $this->request->getFile('proof');
            $sender     = $this->request->getPost('sender');
            $currency   = $this->request->getPost('currency');

            $validate = $this->validate([
                'proof' => [
                    'uploaded[proof]',
                    'mime_in[proof,image/jpg,image/jpeg,image/png]',
                    'max_size[proof,5096]',
                ],
                'sender'    => 'required',
                'currency'  => 'required',
                'p_id'      => 'required'
            ]);

            if (!$validate)
            {
                if ($this->validator->showError('proof'))
                {
                    return $this->fail($this->validator->showError('proof'));
                }
                if ($this->validator->showError('id'))
                {
                    return $this->fail($this->validator->showError('id'));
                }

                if ($this->validator->showError('sender'))
                {
                    return $this->fail($this->validator->showError('sender'));
                }

                if ($this->validator->showError('currency'))
                {
                    return $this->fail($this->validator->showError('currency'));
                }

            }

            $transactions = $this->p2pTransaction->find($id);

            if ($transactions)
            {
                if (!($transactions->buyer_id === $this->auth->Users->id))
                {
                    return $this->failForbidden();
                }

                if ($transactions->status !== 'approve')
                {
                    return $this->fail('Buyer request have not been approved by seller');
                }

                if ($transactions->transaction_state === 'paid')
                {
                    return $this->fail('You have already submitted transaction proof. Please wait for seller to confirm funds');
                }

                //do the upload
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/p2p/', $newName);

                $this->p2pTransaction->buyerConfirmPayment([
                    'id'        => $transactions->id,
                    'sender'    => $sender,
                    'proof'     => $newName,
                    'currency'  => $currency
                ]);

                //send a push notification to the seller about this transaction
                $seller     = $this->userModel->find($transactions->seller_id);
                $message    = $this->auth->Users->first_name .' '. $this->auth->Users->last_name . ' just sent you a payment transfer proof. Make sure you confirm transaction before you confirm payment';
                if (!empty($message))
                {
                    $this->notifier->setMessage($seller->player_id,$seller->id,$message);
                }

                return $this->respond([
                    'status'        => true,
                    'message'       => 'Proof have been successfully sent to seller'
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    public function confirmPaymentFromBuyer($id)
    {
        if ($this->auth->check())
        {
            $transactions   = $this->p2pTransaction->where('id',$id)->get()->getRow();
            $p2pDetails     = $this->p2pModel->where('id',$transactions->p_id)->get()->getRow();

            if ($transactions && $p2pDetails)
            {

                if ($p2pDetails->status === 'close')
                {
                    return $this->failNotFound();
                }

                if ($p2pDetails->user_id !== $this->auth->Users->id)
                {
                    return $this->failForbidden();
                }

                if ($transactions->status === 'closed')
                {
                    return $this->failNotFound();
                }

                if ($transactions->transaction_state !== 'paid')
                {
                    return $this->fail('Payment have not yet been made by the buyer');
                }
            }

            if ($transactions->purchase_type === 'full')
            {
                $this->p2pModel->closeP2p($transactions->p_id);

                //set the charges amount as success
                $this->p2pCharges->setSuccess($transactions->p_id);
            }
            elseif ($transactions->purchase_type == 'part')
            {
                //deduct the actual funds from the p2p order balance.....!!!!!
                $remainingBalance = $p2pDetails->max_amount - $transactions->amount;
                $this->p2pModel->handPartPurchase($transactions->p_id,$remainingBalance);
            }

            //set the transactions as closed
            $this->p2pTransaction->closeTransaction($id);

            //credit the buyer wallet with the funds
            $this->walletModel->creditWallet($transactions->buyer_id,$p2pDetails->currency_from,$transactions->amount);

            //send a push notification to the buyer. Informing him that the payment have been made
            $buyer      = $this->userModel->find($transactions->buyer_id);
            $message    = $this->auth->Users->first_name .' '. $this->auth->Users->last_name . ' just confirmed your payment. Your wallet have been credited successfully';
            if (!empty($message))
            {
                $this->notifier->setMessage($buyer->player_id,$buyer->id,$message);
            }

            return $this->respond([
                'status'        => true,
                'message'       => 'Payment have been successfully confirmed'
            ]);

        }

        return $this->failUnauthorized();
    }



}