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
        <div class="row text-center">
            <div class="col-sm-offset-3 col-sm-6">
                <div class="alert bg-purple">
                    <strong>Catatan!</strong> untuk import data dari file excel, silahkan download templatenya terlebih dahulu.
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/mata_pelajaran.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
            <?= form_open_multipart('mapel/preview'); ?>
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
                                <td>Mata Pelajaran</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (empty($import)) {
                                    echo '<tr><td colspan="10" class="text-center">Data kosong! pastikan anda menggunakan format yang telah disediakan.</td></tr>';
                                } else {
                                    $no = 1;
                                    foreach ($import as $mapel) :
                                        ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $mapel; ?></td>
                                    </tr>
                            <?php
                                    endforeach;
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php if (!empty($import)) : ?>

                        <?= form_open('mapel/do_import', array('id'=>'form-soal-mapel'), ['mapel' => json_encode($import)]); ?>
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
    $(document).ready(function() {
        if ($('#error-data').val() === "500") {
            Swal('Format file bukan berupa xls, xlsx ataupun csv!', '', 'error');
        }
    });

    $('#form-soal-mapel').on('submit', function (e) {
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
                                window.location.href = base_url + 'mapel';
                            }
                        });
                } else {
                    Swal('Gagal', 'Data Gagal Disimpan', 'error');
                }
            }
        })
    });
</script>