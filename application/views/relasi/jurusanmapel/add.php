<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>jurusanmapel" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert" style="background-color:#005555;">
                    <h4 class="text-white" style="color: white;"><i class="fa fa-info-circle"></i> Informasi</h4>
                    <p class="text-white" style="color:white">Jika kolom Mata Pelajaran kosong, berikut ini kemungkinan penyebabnya :</p>
                    <ol class="pl-4" style="color: white;">
                        <li>Anda belum menambahkan master data Mata Pelajaran (Master Mata Pelajaran kosong/belum ada data sama sekali).</li>
                        <li>Mata Pelajaran sudah ditambahkan, jadi anda tidak perlu tambah lagi. Anda hanya perlu mengedit data Jurusan Mata Pelajaran nya saja.</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-4">
                <?=form_open('jurusanmapel/save', array('id'=>'jurusanmapel'), array('method'=>'add'))?>
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <select name="mapel_id" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected></option>
                        <?php foreach ($mapel as $m) : ?>
                            <option value="<?=$m->id_mapel?>"><?=$m->nama_mapel?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <select id="jurusan" multiple="multiple" name="jurusan_id[]" class="form-control select2" style="width: 100%!important">
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat" style="background-color: #005555;color:white">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/relasi/jurusanmapel/add.js"></script>