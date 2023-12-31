<input type="hidden" value="<?= $errFile; ?>" id="error-data">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <ul class="alert alert-info" style="padding-left: 40px">
            <li>Silahkan import data dari excel, menggunakan format yang sudah disediakan</li>
            <li>Data tidak boleh ada yang kosong, harus terisi semua.</li>
            <li>Untuk data Kelas, hanya bisa diisi menggunakan ID Kelas. <a data-toggle="modal" href="#kelasId" style="text-decoration:none" class="btn btn-xs btn-primary">Lihat ID</a>.</li>
        </ul>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/siswa.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
            <?= form_open_multipart('siswa/preview'); ?>
            <label for="file" class="col-sm-offset-1 col-sm-3 text-right">Pilih File</label>
            <div class="col-sm-4">
                <div class="form-group">
                    <input type="file" name="upload_file" required>
                </div>
            </div>
            <div class="col-sm-3">
                <button name="preview" type="submit" class="btn btn-sm btn-success">Preview</button>
            </div>
            <?= form_close(); ?>
            <div class="col-sm-6 col-sm-offset-3">
                <?php if (isset($_POST['preview'])) : ?>
                    <br>
                    <h4>Preview Data</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>NISN</td>
                                <td>Nama</td>
                                <td>Email</td>
                                <td>Jenis Kelamin</td>
                                <td>ID Kelas</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $status = true;
                                if (empty($import)) {
                                    echo '<tr><td colspan="10" class="text-center">Data kosong! pastikan anda menggunakan format yang telah disediakan.</td></tr>';
                                } else {
                                    $no = 1;
                                    foreach ($import as $data) :
                                        ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="<?= $data['nisn'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nisn'] == null ? 'BELUM DIISI' : $data['nisn']; ?>
                                        </td>
                                        <td class="<?= $data['nama'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nama'] == null ? 'BELUM DIISI' : $data['nama'];; ?>
                                        </td>
                                        <td class="<?= $data['email'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['email'] == null ? 'BELUM DIISI' : $data['email'];; ?>
                                        </td>
                                        <td class="<?= $data['jenis_kelamin'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jenis_kelamin'] == null ? 'BELUM DIISI' : $data['jenis_kelamin'];; ?>
                                        </td>
                                        <td class="<?= $data['kelas_id'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['kelas_id'] == null ? 'BELUM DIISI' : $data['kelas_id'];; ?>
                                        </td>
                                    </tr>
                            <?php
                                        if ($data['nisn'] == null || $data['nama'] == null || $data['email'] == null || $data['jenis_kelamin'] == null || $data['kelas_id'] == null) {
                                            $status = false;
                                        }
                                    endforeach;
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php if ($status) : ?>

                        <?= form_open('siswa/do_import', array('id'=>'form-soal-siswa'), ['data' => json_encode($import)]); ?>
                        <button type='submit' class='btn btn-block btn-flat bg-purple'>Import</button>
                        <?= form_close(); ?>

                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kelasId">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data Kelas</h4>
            </div>
            <div class="modal-body">
                <table id="kelas" class="table table-bordered table-condensed table-striped">
                    <thead>
                        <th>ID</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                    </thead>
                    <tbody>
                        <?php foreach ($kelas as $k) : ?>
                            <tr>
                                <td><?= $k->id_kelas; ?></td>
                                <td><?= $k->nama_kelas; ?></td>
                                <td><?= $k->nama_jurusan; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table;
        table = $("#kelas").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
        });
    
        if ($('#error-data').val() === "500") {
            Swal('Format file bukan berupa xls, xlsx ataupun csv!', '', 'error');
        }
    });

    $('#form-soal-siswa').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function (response) {
                if (response === "200") {
                    Swal('Sukses', 'Data Berhasil Disimpan', 'success')
                        .then((result) => {
                            if (result.value) {
                                window.location.href = base_url + 'siswa';
                            }
                        });
                } else {
                    Swal('Gagal', 'Data Gagal Disimpan', 'error');
                }
            }
        })
    });
</script>