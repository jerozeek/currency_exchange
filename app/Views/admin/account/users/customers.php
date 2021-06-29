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
                                                    <h4 class="nk-block-title">Manage Users</h4>
                                                    <div class="nk-block-des">
                                                        <p>List of all registered users</p>
                                                    </div>
                                                </div>
                                            </div>
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
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Phone</span></th>
                                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Verified</span></th>
                                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Account Type</span></th>
                                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Last Login</span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                            <th class="nk-tb-col nk-tb-col-tools text-right">
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($users as $user){  ?>
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
                                                                    <span><?=$user->phone?></span>
                                                                </td>
                                                                <td class="nk-tb-col tb-col-lg" data-order="Email Verified - Kyc Unverified">
                                                                    <ul class="list-status">
                                                                        <li><em class="icon text-success ni ni-check-circle"></em> <span>Email</span></li>
                                                                    </ul>
                                                                </td>
                                                                <td class="nk-tb-col tb-col-lg">
                                                                    <span><?=strtoupper($user->account_type)?></span>
                                                                </td>

                                                                <td class="nk-tb-col tb-col-lg">
                                                                    <span><?=date('d M Y',strtotime($user->last_login))?></span>
                                                                </td>
                                                                <td class="nk-tb-col tb-col-md">
                                                                    <span class="tb-status text-<?=$user->status == 'active' ? 'success' : 'warning'?>"><?=ucfirst($user->status)?></span>
                                                                </td>
                                                                <td class="nk-tb-col nk-tb-col-tools">
                                                                    <ul class="nk-tb-actions gx-1">
                                                                        <li>
                                                                            <div class="drodown">
                                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <ul class="link-list-opt no-bdr">
                                                                                        <?php  if ($details){  ?>
                                                                                            <li><a href="<?=base_url("admin/users/details/$user->id")?>"><em class="icon ni ni-eye"></em><span>View Details</span></a></li>
                                                                                        <?php }  ?>

                                                                                        <?php if ($transactions){  ?>
                                                                                            <li><a href="#"><em class="icon ni ni-repeat"></em><span>Transaction</span></a></li>
                                                                                        <?php  } ?>

                                                                                        <?php  if ($user->account_type == 'admin'){  ?>
                                                                                            <?php  if ($permission){  ?>
                                                                                                <li><a href="#"><em class="icon ni ni-user-cross"></em><span>Permissions</span></a></li>
                                                                                            <?php } ?>
                                                                                        <?php  } ?>

                                                                                        <?php  if ($edit){  ?>
                                                                                        <li><a href="#" onclick="SuspendUser('<?=$user->id?>','<?=$user->status?>')"><em class="icon ni ni-na"></em><span><?=$user->status == 'active' ? 'Suspend User' : 'Unsuspend User'?></span></a></li>
                                                                                        <?php }  ?>
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
                                        </div> <!-- nk-block -->
                                    </div><!-- .components-preview -->
                                </div>
                            </div>
                        </div>
                    </div>

<?=$this->endSection()?>
