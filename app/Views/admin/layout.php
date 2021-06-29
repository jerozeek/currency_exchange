<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="<?=app_name?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=base_url('public/template/images/favicon.png')?>">
    <!-- Page Title  -->
    <title><?=$page_title?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=base_url('public/template/assets/css/dashlite.css?ver=2.4.0')?>">
    <link id="skin-default" rel="stylesheet" href="<?=base_url('public/template/assets/css/theme.css?ver=2.4.0')?>">
</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- sidebar @s -->
        <div class="nk-sidebar nk-sidebar-fixed is-dark " data-content="sidebarMenu">
            <div class="nk-sidebar-element nk-sidebar-head">
                <div class="nk-menu-trigger">
                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
                    <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                </div>
                <div class="nk-sidebar-brand">
                    <a href="<?=base_url('admin/account/dashboard')?>" class="logo-link nk-sidebar-logo">
                        <img class="logo-light logo-img" src="<?=base_url('public/template/images/logo.png')?>" srcset="<?=base_url('public/template/images/logo2x.png')?>" alt="logo">
                        <img class="logo-dark logo-img" src="<?=base_url('public/template/images/logo-dark.png')?>" srcset="<?=base_url('public/template/images/logo-dark2x.png')?>" alt="logo-dark">
                    </a>
                </div>
            </div><!-- .nk-sidebar-element -->
            <div class="nk-sidebar-element nk-sidebar-body">
                <div class="nk-sidebar-content">
                    <div class="nk-sidebar-menu" data-simplebar>
                        <ul class="nk-menu">
                            <li class="nk-menu-item">
                                <a href="<?=base_url('admin/account/dashboard')?>" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-dashlite"></em></span>
                                    <span class="nk-menu-text">Dashboard</span>
                                </a>
                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item">
                                <a href="<?=base_url('admin/users/manage')?>" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                    <span class="nk-menu-text">User Manage</span>
                                </a>
                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item">
                                <a href="<?=base_url('admin/transactions/transactions?type=deposit')?>" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span>
                                    <span class="nk-menu-text">Deposits</span>
                                </a>
                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item">
                                <a href="<?=base_url('admin/transactions/transactions?type=transfer')?>" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-bag-fill"></em></span>
                                    <span class="nk-menu-text">Withdrawals</span>
                                </a>
                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item">
                                <a href="<?=base_url('admin/transactions/transactions?type=exchange')?>" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-exchange-v"></em></span>
                                    <span class="nk-menu-text">Exchanges</span>
                                </a>
                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-user-circle"></em></span>
                                    <span class="nk-menu-text">KYC</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="<?=base_url('admin/kyc/kyc?status=pending')?>" class="nk-menu-link"><span class="nk-menu-text">Pending KYC</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="<?=base_url('admin/kyc/kyc?status=approved')?>" class="nk-menu-link"><span class="nk-menu-text">Approved KYC</span></a>
                                    </li>
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-user-check-fill"></em></span>
                                    <span class="nk-menu-text">Administrator</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="<?=base_url('admin/kyc/manage/deposit')?>" class="nk-menu-link"><span class="nk-menu-text">Manage Administrator</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="<?=base_url('admin/kyc/manage/deposit')?>" class="nk-menu-link"><span class="nk-menu-text">Assign Administrator</span></a>
                                    </li>
                                </ul><!-- .nk-menu-sub -->

                            </li><!-- .nk-menu-item -->

                            <li class="nk-menu-item has-sub">
                                <a href="<?=base_url('admin/ticket/manage')?>" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-ticket-fill"></em></span>
                                    <span class="nk-menu-text">Tickets</span>
                                </a>

                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="<?=base_url('admin/ticket/open')?>" class="nk-menu-link"><span class="nk-menu-text">Open Ticket</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="<?=base_url('admin/ticket/closed')?>" class="nk-menu-link"><span class="nk-menu-text">Closed Ticket</span></a>
                                    </li>
                                </ul><!-- .nk-menu-sub -->
                            </li>

                            <li class="nk-menu-item">
                                <a href="<?=base_url('admin/setting/manage')?>" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                                    <span class="nk-menu-text">App Settings</span>
                                </a>
                            </li><!-- .nk-menu-item -->


                        </ul><!-- .nk-menu -->
                    </div><!-- .nk-sidebar-menu -->
                </div><!-- .nk-sidebar-content -->
            </div><!-- .nk-sidebar-element -->
        </div>
        <!-- sidebar @e -->
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            <div class="nk-header nk-header-fixed is-light">
                <div class="container-fluid">
                    <div class="nk-header-wrap">
                        <div class="nk-menu-trigger d-xl-none ml-n1">
                            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                        </div>
                        <div class="nk-header-brand d-xl-none">
                            <a href="<?=base_url('admin/account/dashboard')?>" class="logo-link">
                                <img class="logo-light logo-img" src="<?=base_url('public/template/images/logo.png')?>" srcset="<?=base_url('public/template/images/logo2x.png')?>" alt="logo">
                                <img class="logo-dark logo-img" src="<?=base_url('public/template/images/logo-dark.png')?>" srcset="<?=base_url('public/template/images/logo-dark2x.png')?>" alt="logo-dark">
                            </a>
                        </div><!-- .nk-header-brand -->

                        <div class="nk-header-tools">
                            <ul class="nk-quick-nav">
                                <li class="dropdown user-dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <em class="icon ni ni-user-alt"></em>
                                            </div>
                                            <div class="user-info d-none d-md-block">
                                                <div class="user-status">Administrator</div>
                                                <div class="user-name dropdown-indicator"><?=$admin_info->first_name. ' '. $admin_info->last_name?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                            <div class="user-card">
                                                <div class="user-avatar">
                                                    <span>AB</span>
                                                </div>
                                                <div class="user-info">
                                                    <span class="lead-text"><?=$admin_info->first_name. ' '. $admin_info->last_name?></span>
                                                    <span class="sub-text"><?=$admin_info->email?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="html/user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>Account Profile</span></a></li>
                                                <li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>App Setting</span></a></li>
                                                <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a href="<?=base_url('admin/auth/logout')?>"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li><!-- .dropdown -->
                            </ul><!-- .nk-quick-nav -->
                        </div><!-- .nk-header-tools -->
                    </div><!-- .nk-header-wrap -->
                </div><!-- .container-fliud -->
            </div>
            <!-- main header @e -->
            <!-- content @s -->
           <?=$this->renderSection('main')?>
            <!-- content @e -->
            <!-- footer @s -->
            <div class="nk-footer">
                <div class="container-fluid">
                    <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright"> &copy; <?=date('Y')?> <?=app_name?>. Designed by <a href="https://payluk.com" target="_blank">Payluk Technologies Limited</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<!-- JavaScript -->
<script src="<?=base_url('public/template/assets/js/bundle.js?ver=2.4.0')?>"></script>
<script src="<?=base_url('public/template/assets/js/scripts.js?ver=2.4.0')?>"></script>
<script src="<?=base_url('public/template/assets/js/charts/gd-default.js?ver=2.4.0')?>"></script>

<script>
    function HandleKYC(id,status)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, execute action!'
        }).then(function (result) {
            if (result.value) {

                $(document).load('<?=base_url('admin/kyc/action?id=')?>'+id+'&status='+status, function (d,s){
                    if (s)
                    {
                        if (d === 'declined')
                        {
                            Swal.fire('Success!', 'KYC declined successfully', 'success');
                            setTimeout(function ()
                            {
                                location.reload();
                            },3000);
                        }

                        else if (d === 'approved')
                        {
                            Swal.fire('Success!', 'KYC approved successfully', 'success');
                            setTimeout(function ()
                            {
                                location.reload();
                            },3000);
                        }
                        else
                        {
                            Swal.fire('Opps!', 'Something went wrong', 'error');
                        }
                    }
                    else
                    {
                        Swal.fire('Opps!', 'Something went wrong', 'error');
                    }
                })
            }
        });
    }

    function SuspendUser(id,status)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, i want!'
        }).then(function (result) {
            if (result.value)
            {
                $(document).load('<?=base_url('admin/users/suspend?user_id=')?>'+id+'&status='+status, function (d,s) {
                    if (s)
                    {
                        if (d === 'success')
                        {
                            Swal.fire('Success','Action done successfully','success');
                            setTimeout(function () {
                                location.reload();
                            },3000);
                        }
                        else
                        {
                            Swal.fire('Error','Something went wrong','error');
                        }
                    }
                    else
                    {
                        Swal.fire('Error','Something went wrong','error');
                    }
                });
            }
        });
    }
</script>
</body>

</html>
