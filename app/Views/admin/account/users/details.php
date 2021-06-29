<?=$this->extend('admin/layout')?>
<?=$this->section('main')?>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-aside-wrap">
                                <div class="card-inner card-inner-lg">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personal">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">Personal Information</h4>
                                                        <div class="nk-block-des">
                                                            <p>Basic info, like your name and address, that you use on Nio Platform.</p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-block">
                                                <div class="nk-data data-list">
                                                    <div class="data-head">
                                                        <h6 class="overline-title">Basics</h6>
                                                    </div>
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">Full Name</span>
                                                            <span class="data-value"><?=$details->first_name . ' '. $details->last_name?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">Display Name</span>
                                                            <span class="data-value"><?=$details->first_name?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Email</span>
                                                            <span class="data-value"><?=$details->email?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span></div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">Phone Number</span>
                                                            <span class="data-value text-soft"><?=$details->phone?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div><!-- data-item -->


                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit" data-tab-target="#address">
                                                        <div class="data-col">
                                                            <span class="data-label">Player ID:</span>
                                                            <span class="data-value"><?=$details->player_id == null ? 'Not Set' : $details->player_id?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div><!-- data-item -->

                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit" data-tab-target="#address">
                                                        <div class="data-col">
                                                            <span class="data-label">Account Type:</span>
                                                            <span class="data-value"><?=ucfirst($details->account_type)?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div><!-- data-item -->


                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit" data-tab-target="#address">
                                                        <div class="data-col">
                                                            <span class="data-label">Active Token ID:</span>
                                                            <span class="data-value"><?=$details->active_token == null ? 'Not Set' : substr($details->active_token,0,35)?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div><!-- data-item -->

                                                </div><!-- data-list -->

                                            </div><!-- .nk-block -->
                                        </div><!-- .tab-pane -->
                                        <div class="tab-pane" id="activity">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">Login Activity</h4>
                                                        <div class="nk-block-des">
                                                            <p>Here is your last 20 login activities log. <span class="text-soft"><em class="icon ni ni-info"></em></span></p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-block card">
                                                <table class="table table-ulogs">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th class="tb-col-os"><span class="overline-title">Browser <span class="d-sm-none">/ IP</span></span></th>
                                                        <th class="tb-col-ip"><span class="overline-title">IP</span></th>
                                                        <th class="tb-col-ip"><span class="overline-title">State</span></th>
                                                        <th class="tb-col-time"><span class="overline-title">Time</span></th>
                                                        <th class="tb-col-action"><span class="overline-title">&nbsp;</span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div><!-- .nk-block-head -->
                                        </div><!-- .tab-pane -->

                                        <div class="tab-pane" id="notification">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">Quick Notification</h4>
                                                        <div class="nk-block-des">
                                                            <p><span>Send a push notification only to this user</span></p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-block card">
                                                <div class="nk-block">
                                                    <div class="nk-block-head nk-block-head-sm nk-block-between">
                                                        <h5 class="title">Admin Note</h5>
                                                        <a href="#" class="link link-sm">+ Add Note</a>
                                                    </div><!-- .nk-block-head -->
                                                    <div class="bq-note">
                                                        <div class="bq-note-item">
                                                            <div class="bq-note-text">
                                                                <p>Aproin at metus et dolor tincidunt feugiat eu id quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean sollicitudin non nunc vel pharetra. </p>
                                                            </div>
                                                            <div class="bq-note-meta">
                                                                <span class="bq-note-added">Added on <span class="date">November 18, 2019</span> at <span class="time">5:34 PM</span></span>
                                                                <span class="bq-note-sep sep">|</span>
                                                                <span class="bq-note-by">By <span>Softnio</span></span>
                                                                <a href="#" class="link link-sm link-danger">Delete Note</a>
                                                            </div>
                                                        </div><!-- .bq-note-item -->
                                                        <div class="bq-note-item">
                                                            <div class="bq-note-text">
                                                                <p>Aproin at metus et dolor tincidunt feugiat eu id quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean sollicitudin non nunc vel pharetra. </p>
                                                            </div>
                                                            <div class="bq-note-meta">
                                                                <span class="bq-note-added">Added on <span class="date">November 18, 2019</span> at <span class="time">5:34 PM</span></span>
                                                                <span class="bq-note-sep sep">|</span>
                                                                <span class="bq-note-by">By <span>Softnio</span></span>
                                                                <a href="#" class="link link-sm link-danger">Delete Note</a>
                                                            </div>
                                                        </div><!-- .bq-note-item -->
                                                    </div><!-- .bq-note -->
                                                </div><!-- .nk-block -->
                                            </div><!-- .nk-block-head -->
                                        </div><!-- .tab-pane -->
                                    </div><!-- .tab-content -->
                                </div>
                                <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                                    <div class="card-inner-group" data-simplebar>
                                        <div class="card-inner">
                                            <div class="user-card">
                                                <?php  if($details->profile_image == null){ ?>
                                                    <div class="user-avatar sm">
                                                        <em class="icon ni ni-user-alt"></em>
                                                    </div>
                                                <?php }else{ ?>
                                                    <img src="<?=base_url("public/profile/$details->profile_image")?>"  width="33px" style="border-radius: 50%; margin-right: 10px">
                                                <?php } ?>
                                                <div class="user-info">
                                                    <span class="lead-text"><?=ucfirst($details->first_name) . ' '. ucfirst($details->last_name)?></span>
                                                    <span class="sub-text"><?=$details->email?></span>
                                                </div>

                                            </div><!-- .user-card -->
                                        </div><!-- .card-inner -->
                                        <div class="card-inner">
                                            <div class="user-account-info py-0">
                                                <h6 class="overline-title-alt">Last Login</h6>
                                                <p><?=date('d M Y',strtotime($details->last_login))?></p>
                                                <h6 class="overline-title-alt">Login IP</h6>
                                                <p><?=$details->ip_address == null ? '127.0.0.1' : $details->ip_address?></p>
                                            </div>
                                        </div><!-- .card-inner -->
                                        <div class="card-inner p-0">
                                            <ul class="link-list-menu nav nav-tabs">
                                                <li><a data-toggle="tab" href="#personal" class="active" href="#"><em class="icon ni ni-user-fill-c"></em><span>Personal Information</span></a></li>
                                                <li><a data-toggle="tab" href="#activity" href="#"><em class="icon ni ni-activity-round-fill"></em><span>Account Activity</span></a></li>
                                                <li><a data-toggle="tab" href="#notification" href="#"><em class="icon ni ni-bell-fill"></em><span>Notification</span></a></li>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div><!-- .card-inner-group -->
                                </div><!-- card-aside -->
                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
<?=$this->endSection()?>