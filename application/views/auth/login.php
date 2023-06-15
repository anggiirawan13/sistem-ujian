	<div id="app">
		<section class="section">
			<div class="container mt-5">
				<div class="row">
					<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
						<div class="login-brand">
							<img src="<?php echo base_url(); ?>assets/stisla/assets/img/logo-ct.png" alt="logo" width="100" class="shadow-light rounded-circle">
						</div>

						<div class="card card-primary">
							<div class="card-header">
								<h4>Login</h4>
							</div>
							<div class="card-body">
								<div id="infoMessage" class="text-center">
									<?php echo $message; ?>
								</div>
								<?= form_open("auth/cek_login", array('id' => 'login', 'class' => 'needs-validation',  'novalidate' => '')); ?>
								<!-- <form method="POST" action="#" class="needs-validation" novalidate=""> -->
								<div class="form-group">
									<label for="identity">Email</label>
									<?= form_input($identity); ?>
									<!-- <div class="invalid-feedback">
										
									</div> -->
									<!-- <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus> --->
								</div>

								<div class="form-group">
									<div class="d-block">
										<label for="password" class="control-label">Password</label>
										<div class="float-right">
											<a href="<?= base_url() ?>auth/forgot_password" class="text-small">
												<?= lang('login_forgot_password'); ?>
											</a>
										</div>
									</div>
									<?= form_input($password); ?>
								</div>

								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<?= form_checkbox('remember', '', FALSE, 'id="remember"' . ' ' . 'class="custom-control-input"'); ?>
										<label class="custom-control-label" for="remember"><?= lang('login_remember_label'); ?></label>
										<!-- <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me"> -->
									</div>
								</div>

								<div class="form-group">
									<?= form_submit('submit', lang('login_submit_btn'), array('id' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block')); ?>
								</div>
								<?= form_close(); ?>

							</div>
						</div>

					</div>
				</div>
			</div>
		</section>
	</div>
	<script type="text/javascript">
		let base_url = '<?= base_url() ?>';
	</script>
	<script src="<?= base_url() ?>assets/dist/js/app/auth/login.js"></script>