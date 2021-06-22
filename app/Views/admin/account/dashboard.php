<?=$this->extend('admin/layout')?>
<?=$this->section('main')?>
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Dashboard Overview</h3>
                            <div class="nk-block-des text-soft">
                                <p>Welcome back <?=ucfirst($admin_info->first_name)?></p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-xxl-6">
                            <div class="row g-gs">
                                <div class="col-lg-6 col-xxl-12">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">All Registered Users</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Total registered users"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount"><?=count($users);?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Total Transactions</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Total transactions"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount"><?=count($transactions);?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .col -->
                                <div class="col-lg-6 col-xxl-12">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Total Deposit</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Total Deposit"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount"><?=count($total_deposit);?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Total Transfer</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Total transfer"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount"><?=count($total_withdrawal);?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .col -->
                            </div><!-- .row -->
                        </div><!-- .col -->
                        <div class="col-xxl-6">
                            <div class="card card-bordered h-100">
                                <div class="card-inner">
                                    <div class="card-title-group align-start gx-3 mb-3">
                                        <div class="card-title">
                                            <h6 class="title">Transactions Overview</h6>
                                            <p>In 30 days transactions</p>
                                        </div>
                                    </div>
<!--                                    <div class="nk-sale-data-group align-center justify-between gy-3 gx-5">
                                        <div class="nk-sale-data">
                                            <span class="amount">Deposit: $82,944.60</span>
                                        </div>

                                        <div class="nk-sale-data">
                                            <span class="amount">Transfer: $82,944.60</span>
                                        </div>

                                        <div class="nk-sale-data">
                                            <span class="amount">Exchange: $82,944.60</span>
                                        </div>
                                    </div>-->
                                    <div class="nk-sales-ck large pt-4">
                                        <canvas class="sales-overview-chart" id="salesOverview"></canvas>
                                    </div>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-xxl-8">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><span class="mr-2">Transaction</span> <a href="#" class="link d-none d-sm-inline">See History</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner p-0 border-top">
                                    <div class="nk-tb-list nk-tb-orders">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span>Trans No.</span></div>
                                            <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                                            <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                                            <div class="nk-tb-col tb-col-lg"><span>Reference</span></div>
                                            <div class="nk-tb-col tb-col-lg"><span>Trans. Type</span></div>
                                            <div class="nk-tb-col"><span>Amount</span></div>
                                            <div class="nk-tb-col"><span class="d-none d-sm-inline">Status</span></div>
                                            <div class="nk-tb-col"><span>&nbsp;</span></div>
                                        </div>
                                        <?php  if (count($transactions) > 0){ ?>

                                        <?php $counters = 0; foreach ($transactions as $transaction){ $counters++; ?>
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#000<?=$transaction->id?></a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <div class="user-card">
                                                    <div class="user-avatar user-avatar-sm bg-purple">
                                                        <span>
                                                            <?php $userDetails = $userModel->find($transaction->user_id)  ?>
                                                            <?php if ($userDetails->profile_image == NULL){  ?>
                                                                <?=substr($userDetails->first_name,0,1) . substr($userDetails->last_name,0,1)?>
                                                            <?php  }else{ ?>
                                                            <img src="<?php echo base_url("public/profile/$userDetails->profile_image")?>">
                                                            <?php  }  ?>
                                                        </span>
                                                    </div>
                                                    <div class="user-name">
                                                        <span class="tb-lead"><?=ucfirst($userDetails->first_name). ' '. ucfirst($userDetails->last_name)?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub"><?=date('d M Y',strtotime($transaction->created_at))?></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-lg">
                                                <span class="tb-sub text-primary"><?= $transaction->reference == NULL ? $transaction->txn_id : $transaction->reference?></span>
                                            </div>

                                            <div class="nk-tb-col tb-col-lg">
                                                <span class="tb-sub text-primary"><?=$transaction->transaction_type?></span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span class="tb-sub tb-amount"><?=number_format($transaction->amount)?> <span><?=strtoupper($transaction->currency)?></span></span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <?php if ($transaction->status == 'success'){  ?>
                                                    <span class="badge badge-dot badge-dot-xs badge-success">Success</span>
                                                <?php  } ?>

                                                <?php if ($transaction->status == 'pending'){  ?>
                                                    <span class="badge badge-dot badge-dot-xs badge-warning">Pending</span>
                                                <?php  } ?>

                                                <?php if ($transaction->status == 'failed'){  ?>
                                                    <span class="badge badge-dot badge-dot-xs badge-failed">Pending</span>
                                                <?php  } ?>
                                            </div>
                                        </div>
                                    <?php if ($counters > 7) break; } }  ?>
                                    </div>
                                </div>
                                <div class="card-inner-sm border-top text-center d-sm-none">
                                    <a href="<?=base_url('admin/transactions/all')?>" class="btn btn-link btn-block">See History</a>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-md-6 col-xxl-4">
                            <div class="card card-bordered card-full">
                                <div class="card-inner-group">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">New Users</h6>
                                            </div>
                                            <div class="card-tools">
                                                <a href="<?=base_url('admin/users/manage')?>" class="link">View All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php  if (count($users) > 0){ ?>
                                    <?php $count = 0;  foreach ($users as $user){  $count++; ?>
                                    <div class="card-inner card-inner-md">
                                        <div class="user-card">
                                            <div class="user-avatar bg-primary-dim">
                                                    <span>
                                                    <?php if ($user->profile_image == NULL){  ?>
                                                        <?=substr($user->first_name,0,1) . substr($user->last_name,0,1)?>
                                                    <?php  }else{ ?>
                                                    <img src="<?php echo base_url("public/profile/$user->profile_image")?>">
                                                <?php  }  ?>
                                                    </span>
                                            </div>
                                            <div class="user-info">
                                                <span class="lead-text"><?=ucfirst($user->first_name) . ' '. ucfirst($user->last_name)?></span>
                                                <span class="sub-text"><?=$user->email?></span>
                                            </div>
                                            <div class="user-action">
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger mr-n1" data-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($count > 4){ break; } } }  ?>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-lg-6 col-xxl-4">
                            <div class="card card-bordered h-100">
                                <div class="card-inner border-bottom">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Notifications</h6>
                                        </div>
                                        <div class="card-tools">
                                            <a href="#" class="link">View All</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner">
                                    <div class="timeline">
                                        <h6 class="timeline-head">November, 2019</h6>
                                        <ul class="timeline-list">
                                            <li class="timeline-item">
                                                <div class="timeline-status bg-primary is-outline"></div>
                                                <div class="timeline-date">13 Nov <em class="icon ni ni-alarm-alt"></em></div>
                                                <div class="timeline-data">
                                                    <h6 class="timeline-title">Submited KYC Application</h6>
                                                    <div class="timeline-des">
                                                        <p>Re-submitted KYC Application form.</p>
                                                        <span class="time">09:30am</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-item">
                                                <div class="timeline-status bg-primary"></div>
                                                <div class="timeline-date">13 Nov <em class="icon ni ni-alarm-alt"></em></div>
                                                <div class="timeline-data">
                                                    <h6 class="timeline-title">Submited KYC Application</h6>
                                                    <div class="timeline-des">
                                                        <p>Re-submitted KYC Application form.</p>
                                                        <span class="time">09:30am</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-item">
                                                <div class="timeline-status bg-pink"></div>
                                                <div class="timeline-date">13 Nov <em class="icon ni ni-alarm-alt"></em></div>
                                                <div class="timeline-data">
                                                    <h6 class="timeline-title">Submited KYC Application</h6>
                                                    <div class="timeline-des">
                                                        <p>Re-submitted KYC Application form.</p>
                                                        <span class="time">09:30am</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<?=$this->endSection()?>