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
        </ul>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/soal.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
        <?= form_open_multipart('soal/preview'); ?>
            <label for="file" class="col-sm-offset-1 col-sm-3 text-right">Pilih File</label>
            <div class="col-sm-4">
                <div class="form-group">
                    <input id="upload-file" type="file" name="upload_file" required>
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
                                <td>Bobot Soal</td>
                                <td>Soal</td>
                                <td>Jawaban A</td>
                                <td>Jawaban B</td>
                                <td>Jawaban C</td>
                                <td>Jawaban D</td>
                                <td>Jawaban E</td>
                                <td>Jawaban Benar</td>
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
                                        <td class="<?= $data['bobot'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['bobot'] == null ? 'BELUM DIISI' : $data['bobot']; ?>
                                        </td>
                                        <td class="<?= $data['soal'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['soal'] == null ? 'BELUM DIISI' : $data['soal']; ?>
                                        </td>
                                        <td class="<?= $data['jawaban_a'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jawaban_a'] == null ? 'BELUM DIISI' : $data['jawaban_a']; ?>
                                        </td>
                                        <td class="<?= $data['jawaban_b'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jawaban_b'] == null ? 'BELUM DIISI' : $data['jawaban_b']; ?>
                                        </td>
                                        <td class="<?= $data['jawaban_c'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jawaban_c'] == null ? 'BELUM DIISI' : $data['jawaban_c']; ?>
                                        </td>
                                        <td class="<?= $data['jawaban_d'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jawaban_d'] == null ? 'BELUM DIISI' : $data['jawaban_d']; ?>
                                        </td>
                                        <td class="<?= $data['jawaban_e'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jawaban_e'] == null ? 'BELUM DIISI' : $data['jawaban_e']; ?>
                                        </td>
                                        <td class="<?= $data['jawaban_benar'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['jawaban_benar'] == null ? 'BELUM DIISI' : $data['jawaban_benar']; ?>
                                        </td>
                                    </tr>
                            <?php
                                        if ($data['bobot'] == null || $data['soal'] == null || $data['jawaban_a'] == null || $data['jawaban_b'] == null || $data['jawaban_c'] == null || $data['jawaban_d'] == null || $data['jawaban_e'] == null || $data['jawaban_benar'] == null) {
                                            $status = false;
                                        }
                                    endforeach;
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php if ($status) : ?>

                        <?= form_open('soal/do_import', array('id'=>'form-soal-guru'), ['data' => json_encode($import)]); ?>
                        <button type='submit' class='btn btn-block btn-flat bg-purple'>Import</button>
                        <?= form_close(); ?>

                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function (e) {
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
                                window.location.href = base_url + 'soal';
                            }
                        });
                } else {
                    Swal('Gagal', 'Data Gagal Disimpan', 'error');
                }
            }
        })
    });
</script>
