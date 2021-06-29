<?=$this->extend('admin/layout')?>
<?=$this->section('main')?>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h4 class="nk-block-title">KYC - <?=strtoupper($_GET['status'])?></h4>
                                </div>
                            </div>
                            <div class="card card-preview">
                                <div class="card-inner">
                                    <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid">
                                                    <label class="custom-control-label" for="uid"></label>
                                                </div>
                                            </th>
                                            <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Date of Birth</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Phone</span></th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Verified</span></th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Submitted On</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                            <th class="nk-tb-col nk-tb-col-tools text-right">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  foreach ($kyc as $value)
                                        {
                                            $name   = $value->first_name . ' '. $value->middle_name . ' '. $value->last_name;
                                            $user   = $userModel->find($value->user_id);
                                            ?>
                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid1">
                                                    <label class="custom-control-label" for="uid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                       <span>
                                                    <?php if ($user->profile_image == NULL){  ?>
                                                        <?=substr($user->first_name,0,1) . substr($user->last_name,0,1)?>
                                                    <?php  }else{ ?>
                                                        <img src="<?php echo base_url("public/profile/$user->profile_image")?>">
                                                    <?php  }  ?>
                                                    </span>
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="tb-lead"><?=$name?> <span class="dot dot-success d-md-none ml-1"></span></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?=$value->date_of_birth?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span><?=$value->phone?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg" data-order="Email Verified - Kyc Unverified">
                                                <ul class="list-status">
                                                    <li><em class="icon ni ni-alert-circle"></em> <span>KYC</span></li>
                                                </ul>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span><?=date('d M Y',strtotime($value->created_at))?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="tb-status <?=$value->approved == 1 ? 'text-success' : 'text-warning' ?>"><?=$value->approved == 1 ? 'Approved' : 'Pending'?></span>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="<?=base_url("admin/kyc/view/".$value->id)?>"><em class="icon ni ni-eye"></em><span>View Details</span></a></li>
                                                                    <?php  if ($permission){  ?>
                                                                        <?php if ($value->approved == 0){  ?>
                                                                            <li><a href="#" onclick="HandleKYC('<?=$value->id?>','1')"><em class="icon ni ni-check-fill-c"></em><span>Approve</span></a></li>
                                                                            <li><a href="#" onclick="HandleKYC('<?=$value->id?>','2')"><em class="icon ni ni-delete-fill"></em><span>Decline</span></a></li>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr><!-- .nk-tb-item  -->
                                        <?php   } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- .card-preview -->
                        </div> <!-- nk-block -->
                    </div><!-- .components-preview -->
                </div>
            </div>
        </div>
    </div>
<?=$this->endSection()?>
