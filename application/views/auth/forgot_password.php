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
							<h4><?php echo lang('forgot_password_heading'); ?></h4>
						</div>


						<div class="card-body">
							<p class="text-muted"><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>
							<?php if ($this->session->flashdata('success')) : ?>
								<p class="alert alert-success text-center"><?= $this->session->flashdata('success'); ?></p>
							<?php endif; ?>
							<div id="infoMessage" class="alert alert-danger text-center"><?php echo $message; ?></div>
							<?php echo form_open("auth/forgot_password"); ?>
							<div class="form-group">
								<label for="identity"><?php echo (($type == 'email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label)); ?></label>
								<?php echo form_input($identity); ?>
							</div>

							<div class="form-group">
								<?php echo form_submit('submit', lang('forgot_password_submit_btn'), ['class' => 'btn btn-primary btn-lg btn-block']); ?>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>