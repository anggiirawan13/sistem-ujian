<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Relasi <?=$subjudul?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="mt-2 mb-3">
            <a href="<?= base_url('kelasguru/add') ?>" class="btn btn-sm" style="background-color:#069A8E;color:white"><i class="fa fa-plus"></i> Tambah</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm" style="background-color:#A1E3D8;"><i class="fa fa-refresh"></i> Reload</button>
			<div class="pull-right">
				<button onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button>
			</div>
        </div>
		<?=form_open('',array('id'=>'bulk'))?>
        <table id="kelasguru" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIP</th>
                    <th>Guru</th>
                    <th>Kelas</th>
					<th class="text-center">Edit</th>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>NIP</th>
                    <th>Guru</th>
                    <th>Kelas</th>
					<th class="text-center">Edit</th>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
                </tr>
            </tfoot>
        </table>
		<?=form_close()?>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/relasi/kelasguru/data.js"></script>