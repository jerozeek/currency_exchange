<?=$this->extend('admin/layout')?>
<?=$this->section('main')?>

<?php
$allTransactions            = $transactions->where(['status' => 'success', 'transaction_type' => $_GET['type']])->orderBy('id','DESC')->get()->getResult();
$total_transaction_amount   = 0;
$total_charges              = 0;

foreach ($allTransactions as $trans)
{
    $total_transaction_amount += $trans->amount;
    $total_charges            += $trans->charges;
}

//NGN changes
$NGN_deposit = 0;
$NGN_charges = 0;
$currencyTransactions = $transactions->where(['status' => 'success', 'transaction_type' => $_GET['type'], 'currency' => 'NGN'])->orderBy('id','DESC')->get()->getResult();
foreach ($currencyTransactions as $currencyTransaction)
{
    $NGN_deposit += $currencyTransaction->amount;
    $NGN_charges += $currencyTransaction->charges;
}

//EUR changes
$EUR_deposit = 0;
$EUR_charges = 0;
$currencyTransactions = $transactions->where(['status' => 'success', 'transaction_type' => $_GET['type'], 'currency' => 'EUR'])->orderBy('id','DESC')->get()->getResult();
foreach ($currencyTransactions as $currencyTransaction)
{
    $EUR_deposit += $currencyTransaction->amount;
    $EUR_charges += $currencyTransaction->charges;
}

//USD changes
$USD_deposit = 0;
$USD_charges = 0;
$currencyTransactions = $transactions->where(['status' => 'success', 'transaction_type' => $_GET['type'], 'currency' => 'USD'])->orderBy('id','DESC')->get()->getResult();
foreach ($currencyTransactions as $currencyTransaction)
{
    $USD_deposit += $currencyTransaction->amount;
    $USD_charges += $currencyTransaction->charges;
}

//USD changes
$GBP_deposit = 0;
$GBP_charges = 0;
$currencyTransactions = $transactions->where(['status' => 'success', 'transaction_type' => $_GET['type'], 'currency' => 'GBP'])->orderBy('id','DESC')->get()->getResult();
foreach ($currencyTransactions as $currencyTransaction)
{
    $GBP_deposit += $currencyTransaction->amount;
    $GBP_charges += $currencyTransaction->charges;
}
?>

    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"><?=ucfirst($_GET['type'])?> Overview</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-xxl-6">
                                <div class="row g-gs">
                                    <?php if ($_GET['type'] == 'deposit'){  ?>
                                        <div class="col-lg-12 col-xxl-12">
                                        <div class="row g-gs">

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total Transaction</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($total_transaction_amount);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total NGN Deposit</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($NGN_deposit);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->


                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total USD Deposit</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($USD_deposit);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total GBP Deposit</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($GBP_deposit);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total EUR Deposit</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($EUR_deposit);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

















                                        </div><!-- .row -->
                                    </div><!-- .col -->
                                    <?php  } ?>

                                    <?php if ($_GET['type'] == 'transfer' || $_GET['type'] == 'exchange'){  ?>
                                    <div class="col-lg-12 col-xxl-12">
                                        <div class="row g-gs">
                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total Amount</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($total_transaction_amount);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total NGN Charges</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($NGN_charges);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->


                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total USD Charges</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($USD_charges);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total GBP Charges</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($GBP_charges);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->

                                            <div class="col">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Total EUR Charges</h6>
                                                            </div>
                                                            <div class="card-tools">
                                                                <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"></em>
                                                            </div>
                                                        </div>
                                                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                            <div class="nk-sale-data">
                                                                <span class="amount"><?=number_format($EUR_charges);?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card -->
                                            </div><!-- .col -->




                                        </div><!-- .row -->
                                    </div><!-- .col -->
                                    <?php  } ?>

                                </div><!-- .row -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .nk-block -->
                    <br>

                    <div class="card card-preview">
                        <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                                <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="nk-tb-col nk-tb-col-check">
                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                            <input type="checkbox" class="custom-control-input" id="uid">
                                            <label class="custom-control-label" for="uid"></label>
                                        </div>
                                    </th>
                                    <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Amount</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Charges</span></th>
                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Currency</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Type</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Date</span></th>
                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($allTransactions as $transaction){
                                    $user = $userModel->find($transaction->user_id);
                                    ?>
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="uid<?=$user->id?>">
                                                <label class="custom-control-label" for="uid<?=$user->id?>"></label>
                                            </div>
                                        </td>
                                        <td class="nk-tb-col">
                                            <div class="user-card">
                                                <?php  if($user->profile_image == null){ ?>
                                                    <div class="user-avatar sm">
                                                        <em class="icon ni ni-user-alt"></em>
                                                    </div>
                                                <?php }else{ ?>
                                                    <img src="<?=base_url("public/profile/$user->profile_image")?>" width="33px" style="border-radius: 50%; margin-right: 10px">
                                                <?php } ?>
                                                <div class="user-info">
                                                    <span class="tb-lead"><?=ucfirst($user->first_name) .' '. ucfirst($user->last_name)?> <span class="dot dot-success d-md-none ml-1"></span></span>
                                                    <span><?=$user->email?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nk-tb-col tb-col-md">
                                            <span><?=number_format($transaction->amount)?></span>
                                        </td>

                                        <td class="nk-tb-col tb-col-md">
                                            <span><?=number_format($transaction->charges)?></span>
                                        </td>

                                        <td class="nk-tb-col tb-col-md">
                                            <span><?=$transaction->currency?></span>
                                        </td>

                                        <td class="nk-tb-col tb-col-md">
                                            <span><?=ucfirst($transaction->transaction_type)?></span>
                                        </td>



                                        <td class="nk-tb-col tb-col-lg">
                                            <span><?=date('d M Y',strtotime($transaction->created_at))?></span>
                                        </td>
                                        <td class="nk-tb-col tb-col-md">
                                            <span class="tb-status text-<?=$transaction->status == 'success' ? 'success' : 'warning'?>"><?=$transaction->status?></span>
                                        </td>
                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="<?=base_url("admin/transactions/details/$transaction->id")?>"><em class="icon ni ni-eye"></em><span>View Invoice</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr><!-- .nk-tb-item  -->
                                <?php  } ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- .card-preview -->



                </div>
            </div>
        </div>
    </div>
<?=$this->endSection()?>