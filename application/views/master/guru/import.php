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
            <li>Untuk data Mata Pelajaran, hanya bisa diisi menggunakan ID Mata Pelajaran. <a data-toggle="modal" href="#mapelId" style="text-decoration:none" class="btn btn-xs btn-primary">Lihat ID</a>.</li>
        </ul>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/guru.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
            <?= form_open_multipart('guru/preview'); ?>
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
                                <td>NIP</td>
                                <td>Nama</td>
                                <td>Email</td>
                                <td>ID Mata Pelajaran</td>
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
                                        <td class="<?= $data['nip'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nip'] == null ? 'BELUM DIISI' : $data['nip']; ?>
                                        </td>
                                        <td class="<?= $data['nama_guru'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nama_guru'] == null ? 'BELUM DIISI' : $data['nama_guru'];; ?>
                                        </td>
                                        <td class="<?= $data['email'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['email'] == null ? 'BELUM DIISI' : $data['email'];; ?>
                                        </td>
                                        <td class="<?= $data['mapel_id'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['mapel_id'] == null ? 'BELUM DIISI' : $data['mapel_id'];; ?>
                                        </td>
                                    </tr>
                            <?php
                                        if ($data['nip'] == null || $data['nama_guru'] == null || $data['email'] == null || $data['mapel_id'] == null) {
                                            $status = false;
                                        }
                                    endforeach;
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php if ($status) : ?>

                        <?= form_open('guru/do_import', array('id'=>'form-soal-guru'), ['data' => json_encode($import)]); ?>
                        <button type='submit' class='btn btn-block btn-flat bg-purple'>Import</button>
                        <?= form_close(); ?>

                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mapelId">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data Mata Pelajaran</h4>
            </div>
            <div class="modal-body">
                <table id="mapel" class="table table-condensed table-striped">
                    <thead>
                        <th>ID</th>
                        <th>Mata Pelajaran</th>
                    </thead>
                    <tbody>
                        <?php foreach ($mapel as $m) : ?>
                            <tr>
                                <td><?= $m->id_mapel; ?></td>
                                <td><?= $m->nama_mapel; ?></td>
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
        table = $("#mapel").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
        });

        if ($('#error-data').val() === "500") {
            Swal('Format file bukan berupa xls, xlsx ataupun csv!', '', 'error');
        }
    });

    $('#form-soal-guru').on('submit', function (e) {
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
                                window.location.href = base_url + 'guru';
                            }
                        });
                } else {
                    Swal('Gagal', 'Data Gagal Disimpan', 'error');
                }
            }
        })
    });
</script>