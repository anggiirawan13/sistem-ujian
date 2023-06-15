<a href="<?= base_url('dashboard') ?>" class="logo">
    <span class="logo-mini"><b>SMK</b></span>
    <span class="logo-lg"><b>SMK</b> <b>B</b>hara <b>T</b>rikora </span>
</a>

<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- The user image in the navbar-->
                    <?php if (empty($user->image)) : ?>
                        <img src="<?= base_url('uploads/profile/default.png') ?>" class="user-image" alt="User Image">
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <?php else : ?>
                        <img src="<?= base_url('uploads/profile/') .  $user->image ?>" class="user-image" alt="User Image">
                    <?php endif; ?>
                    <span class="hidden-xs">Hi, <?= $user->first_name ?></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- The user image in the menu -->
                    <li class="user-header">
                    <?php if (empty($user->image)) : ?>
                        <img src="<?= base_url('uploads/profile/default.png') ?>" class="user-image" alt="User Image">
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <?php else : ?>
                        <img src="<?= base_url('uploads/profile/') .  $user->image ?>" class="user-image" alt="User Image">
                    <?php endif; ?>
                        <p class="text-black">
                            <?= $user->first_name  ?>
                            <small class="text-black">Anggota sejak <?= date('M, Y', $user->created_on) ?></small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="<?= base_url() ?>users/edit/<?= $user->id ?>" class="btn" style="background-color: #069A8E;color:white;">
                                <?= $this->ion_auth->is_admin() ? "Edit Profile" : "Ganti Password" ?>
                            </a>
                        </div>
                        <div class="pull-right">
                            <a href="#" id="logout" class="btn btn-danger">Logout</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>