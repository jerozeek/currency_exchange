<?=$this->extend('admin/layout')?>
<?=$this->section('main')?>
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">KYCs / <strong class="text-primary small"><?=$kyc->first_name . ' '. $kyc->middle_name . ' '. $kyc->last_name?></strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li>Application ID: <span class="text-base">KID000<?=$kyc->id?></span></li>
                                    <li>Submitted At: <span class="text-base"><?=date('d M, Y H:i:s', strtotime($kyc->created_at))?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="<?=previous_url()?>" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                            <a href="<?=previous_url()?>" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="row gy-5">
                        <div class="col-lg-5">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h5 class="nk-block-title title">Application Info</h5>
                                    <p>Submission date, approve date, status etc.</p>
                                </div>
                            </div><!-- .nk-block-head -->
                            <div class="card card-bordered">
                                <ul class="data-list is-compact">
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Submitted By</div>
                                            <div class="data-value"><?=$kyc->first_name?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Submitted At</div>
                                            <div class="data-value"><?=date('d M, Y H:i:s', strtotime($kyc->created_at))?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Status</div>
                                            <div class="data-value"><span class="badge badge-dim badge-sm <?=$kyc->approved == 1 ? 'badge-outline-success' : 'badge-outline-warning'?>"><?=$kyc->approved == 1 ? 'Approved' : 'Pending'?></span></div>
                                        </div>
                                    </li>
                                </ul>
                            </div><!-- .card -->
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h5 class="nk-block-title title">Uploaded Documents</h5>
                                    <p>Here is user uploaded documents.</p>
                                </div>
                            </div><!-- .nk-block-head -->
                            <div class="card card-bordered">
                                <ul class="data-list is-compact">
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Document Type</div>
                                            <div class="data-value"><?=$kyc->id_type?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Document ID</div>
                                            <div class="data-value"><?=$kyc->id_number?></div>
                                        </div>
                                    </li>

                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Document File</div>
                                            <div class="data-value"><a target="_blank" href="<?=base_url('public/kyc/'.$kyc->id_upload)?>">View</a> </div>
                                        </div>
                                    </li>
                                </ul>
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-lg-7">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h5 class="nk-block-title title">Applicant Information</h5>
                                    <p>Basic info, like name, phone, address, country etc.</p>
                                </div>
                            </div>
                            <div class="card card-bordered">
                                <ul class="data-list is-compact">
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">First Name</div>
                                            <div class="data-value"><?=$kyc->first_name?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Last Name</div>
                                            <div class="data-value"><?=$kyc->last_name?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Middle Name</div>
                                            <div class="data-value"><?=$kyc->middle_name?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Phone Number</div>
                                            <div class="data-value text-soft"><em><?=$kyc->phone?></em></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Date of Birth</div>
                                            <div class="data-value"><?=$kyc->date_of_birth?></div>
                                        </div>
                                    </li>
                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Full Address</div>
                                            <div class="data-value"><?=$kyc->address?></div>
                                        </div>
                                    </li>

                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">City</div>
                                            <div class="data-value"><?=$kyc->city?></div>
                                        </div>
                                    </li>

                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">State</div>
                                            <div class="data-value"><?=$kyc->state?></div>
                                        </div>
                                    </li>

                                    <li class="data-item">
                                        <div class="data-col">
                                            <div class="data-label">Country of Residence</div>
                                            <div class="data-value"><?=$kyc->country?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .col -->
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<?=$this->endSection()?>