<div class="row">
    <div class="col-sm-4">
        <div class="box box-info">
            <?= form_open_multipart('fotoprofile/save'); ?>
            <div class="box-header with-border">
                <h3 class="box-title">Edit Foto Profile</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <img class="profile-user-img img-responsive img-circle" src="<?= base_url('uploads/profile/') .  $user->image ?>" alt="User profile picture">
                </div>
                <div class="form-group">
                    <label for="username">Foto Profile</label>
                    <input type="file" id="image" name="image" class="form-control">
                    <small class="help-block">* Untuk memaksimalkan tampilan foto profile, maka disarankan menggunakan foto berdimensi 400x400 atau 500x500</small>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btn-info" class="btn btn-info">Simpan</button>
            </div>
            <?= form_close(); ?>
            <div class="box-footer">
                <a href="<?= base_url() ?>fotoprofile/remove" class="btn btn-danger">Hapus Foto Profil</a>
            </div>
        </div>
    </div>
</div>