<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="<?=app_name?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?=app_name?>">
    <link rel="shortcut icon" href="<?=base_url('public/template/images/favicon.png')?>">
    <title>Login | <?=app_name?></title>
    <link rel="stylesheet" href="<?=base_url('public/template/assets/css/dashlite.css?ver=2.4.0')?>">
    <link id="skin-default" rel="stylesheet" href="<?=base_url('public/template/assets/css/theme.css?ver=2.4.0')?>">
</head>

<body class="nk-body bg-white npc-general pg-auth">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->
            <div class="nk-content ">
                <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                    <div class="brand-logo pb-4 text-center">
                        <a href="#" class="logo-link">
                            <img class="logo-light logo-img logo-img-lg" src="<?=base_url('public/template/images/logo.png')?>" srcset="<?=base_url('public/template/images/logo2x.png')?>" alt="logo">
                            <img class="logo-dark logo-img logo-img-lg" src="<?=base_url('public/template/images/logo-dark.png')?>" srcset="<?=base_url('public/template/images/logo-dark2x.png')?>" alt="logo-dark">
                        </a>
                    </div>
                    <div class="card card-bordered">
                        <div class="card-inner card-inner-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h4 class="nk-block-title text-center">Administrative Sign-In</h4>
                                    <div class="nk-block-des text-center">
                                        <p>Access the <?=app_name?> administrative panel using your email and password.</p>
                                    </div>
                                </div>
                            </div>

                            <?php  if (session()->has('error')){ ?>
                                <div class="alert alert-danger" role="alert">
                                    <?=session()->getFlashdata('error')?>
                                </div>
                            <?php  } ?>

                            <form action="<?=base_url('admin/auth/login')?>" method="post">

                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01">Email</label>
                                    </div>
                                    <input type="email" class="form-control form-control-lg" id="default-01" placeholder="Enter your email address" name="email">
                                </div>

                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="password">Password</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control form-control-lg" id="password" placeholder="Enter your password" name="password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
                                </div>

                                <div class="nk-block-content text-center text-lg-center mt-2">
                                    <p class="text-soft">&copy; <?=date('Y')?> <?=app_name?>. All Rights Reserved.</p>
                                </div>

                            </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- wrap @e -->
        </div>
        <!-- content @e -->
    </div>
    <!-- main @e -->
</body>

<!-- JavaScript -->
<script src="<?=base_url('public/template/assets/js/bundle.js?ver=2.4.0')?>"></script>
<script src="<?=base_url('public/template/assets/js/scripts.js?ver=2.4.0')?>"></script>

</html>