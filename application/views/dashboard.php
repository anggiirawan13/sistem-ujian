<?php if ($this->ion_auth->is_admin()) : ?>
    <div class="row">
        <?php foreach ($info_box as $info) : ?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-<?= $info->box ?>">
                    <div class="inner">
                        <h3><?= $info->total; ?></h3>
                        <p><?= $info->title; ?></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-<?= $info->icon ?>"></i>
                    </div>
                    <a href="<?= base_url() . strtolower($info->title); ?>" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php elseif ($this->ion_auth->in_group('guru')) : ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Akun</h3>
                </div>
                <table class="table table-hover">
                    <tr>
                        <th>Nama</th>
                        <td><?= $guru->nama_guru ?></td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td><?= $guru->nip ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $guru->email ?></td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td><?= $guru->nama_mapel ?></td>
                    </tr>
                    <tr>
                        <th>Daftar Kelas</th>
                        <td>
                            <ol class="pl-4">
                                <?php foreach ($kelas as $k) : ?>
                                    <li><?= $k->nama_kelas ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-solid">
                <div class="box-header" style="background-color:#005555;color:white">
                    <h3 class="box-title">Pemberitahuan</h3>
                </div>
                <div class="box-body">
                    <ul>
                        <li>Tata tertib hanya dikirim satu kali di hari pertama.</li>
                        <li>Setelah bel tanda waktu kedua dimulai, pengawas mengirimkan pesan berisi soal ke dalam WhatsApp Group dalam format yang sudah disiapkan oleh masing-masing guru mata pelajaran.</li>
                        <li>Setelah memastikan terkirimnya soal, pengawas mempersilakan siswa untuk mengerjakan soalnya masing-masing.</li>
                        <li>Pengawas tidak dibenarkan memberikan bantuan dalam menjawab soal kepada peserta tes dalam bentuk apapun.</li>
                        <li>Setelah bel tanda waktu ketiga berbunyi, pengawas meminta peserta untuk berhenti mengerjakan soal.</li>
                        <li>Pengawas wajib mengecek dan memastikan semua siswa telah mengerjakan soal.</li>
                        <li>Hal-hal yang menyimpang selama berlangsungnya tes hendaknya dicatat dalam berita acara.</li>
                        <li>Selama ujian berlangsung, pengawas harus memerhatikan berbagai hal berikut: </li>
                    </ul>
                    <ol>
                        <li>Tetap aktif (online) di dalam grup whatsapp kelas untuk merespon siswa yang mungkin akan bertanya tentang kejelasan soal.</li>
                        <li>Tetap aktif (online) di dalam grup whatsapp panitia dan pengawas untuk melihat tanda bel yang diberikan panitia sekaligus mengantisipasi update informasi dari panitia.</li>
                        <li>Mengingatkan siswa setiap 15 menit sekali dengan pesan yang menekankan pentingnya kejujuran di tengah PTS yang menggunakan mode daring.</li>
                        <li>Menjaga ketertiban siswa di dalam grup agar tetap kondusif.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<?php else : ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Akun</h3>
                </div>
                <table class="table table-hover">
                    <tr>
                        <th>NISN</th>
                        <td><?= $siswa->nisn ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?= $siswa->nama ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?= $siswa->jenis_kelamin === 'L' ? "Laki-laki" : "Perempuan"; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $siswa->email ?></td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td><?= $siswa->nama_jurusan ?></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td><?= $siswa->nama_kelas ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-solid">
                <div class="box-header bg-purple">
                    <h3 class="box-title">Pemberitahuan</h3>
                </div>
                <div class="box-body">
                    <h4>Informasi</h4>
                    <ol>
                        <li>Siswa wajib memiliki kartu peserta ujian.</li>
                        <li>Siswa wajib menyiapkan Handphone / laptop selama ujian berlangsung.</li>
                        <li>Siswa wajib memiliki email active dan email milik sendiri.</li>
                        <li>Siswa hanya akan diberi kesempatan log in satu kali, oleh karena itu siswa harus mengerjakan soal dengan teliti. Jika ada kendala Harap hubungi team / panitia yang sudah ditugaskan melalui group kelas.</li>
                        <li>Durasi Mengerjakan soal per mapel 120 menit dan tidak ada penambahan waktu.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>