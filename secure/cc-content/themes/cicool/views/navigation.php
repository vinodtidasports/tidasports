<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top"><?= site_name(); ?></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php foreach (get_menu('top-menu') as $menu) : ?>
                    <?php if (app()->aauth->is_allowed('menu_' . $menu->label)) : 
                        ?>
                        <li>
                            <a class="page-scroll" href="<?= site_url(replace_admin_url($menu->link)); ?>"><?= $menu->label; ?></a>
                        </li>
                    <?php endif ?>
                <?php endforeach; ?>
                <?php if (!app()->aauth->is_loggedin()) : ?>
                    <li>
                        <a class="page-scroll" href="<?= admin_site_url('/login'); ?>"><i class="fa fa-sign-in"></i> <?= cclang('login'); ?></a>
                    </li>
                <?php else : ?>
                    <li>
                        <a class="page-scroll dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                            <img src="<?= BASE_URL . 'uploads/user/' . (!empty(get_user_data('avatar')) ? get_user_data('avatar') : 'default.png'); ?>" class="img-circle img-user" alt="User Image">
                            <?= get_user_data('full_name'); ?>
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= admin_site_url('/user/profile'); ?>">My Profile</a>
                            <a class="dropdown-item" href="<?= admin_site_url('/dashboard'); ?>">Dashboard</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= admin_site_url('/auth/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li class="dropdown ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <span class="flag-icon <?= get_current_initial_lang(); ?>"></span> <?= get_current_lang(); ?> </a>
                    <ul class="dropdown-menu" role="menu">
                        <?php foreach (get_langs() as $lang) : ?>
                            <li><a href="<?= site_url('web/switch_lang/' . $lang['folder_name']); ?>"><span class="flag-icon <?= $lang['icon_name']; ?>"></span> <?= $lang['name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>