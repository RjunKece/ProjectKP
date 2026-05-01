
# Part 2: Sections 4.5 - 4.8, combines with part1
import os

def tbl(headers, rows):
    h = ''.join(f'<th>{x}</th>' for x in headers)
    r = ''
    for row in rows:
        r += '<tr>' + ''.join(f'<td>{x}</td>' for x in row) + '</tr>\n'
    return f'<table>\n<tr>{h}</tr>\n{r}</table>\n'

sec45 = """
<h2>4.5 Implementasi Basis Data</h2>
<p>Struktur basis data Sistem ERP Prototype dibangun menggunakan fitur migration pada Laravel yang memungkinkan pembuatan dan pengelolaan skema basis data secara terprogram dan terversioning. Basis data menggunakan MySQL dengan lima tabel utama yang saling berelasi. Berikut adalah penjelasan detail setiap tabel beserta relasinya.</p>

<h3>4.5.1 Tabel Roles</h3>
<p>Tabel <code>roles</code> berfungsi menyimpan data hak akses pengguna dalam sistem. Tabel ini menjadi referensi utama untuk menentukan level akses setiap pengguna. Terdapat tiga role yang dikonfigurasi melalui RoleSeeder, yaitu: super_admin, admin, dan karyawan.</p>
""" + tbl(['Kolom','Tipe Data','Keterangan'],[
    ['id','bigint unsigned (PK, AI)','Primary key, auto increment'],
    ['nama_role','varchar (unique)','Nama role: super_admin, admin, karyawan'],
    ['created_at','timestamp','Waktu pembuatan record'],
    ['updated_at','timestamp','Waktu pembaruan record'],
]) + """

<h3>4.5.2 Tabel Divisions</h3>
<p>Tabel <code>divisions</code> menyimpan data divisi atau departemen yang ada di perusahaan. Tabel ini digunakan untuk mengelompokkan pengguna berdasarkan unit kerja. Terdapat delapan divisi yang dikonfigurasi melalui DivisionSeeder, yaitu: Marketing, Sales, Keuangan, Konten Kreator, Gudang, CRM, YouTube, dan Admin Marketplace.</p>
""" + tbl(['Kolom','Tipe Data','Keterangan'],[
    ['id','bigint unsigned (PK, AI)','Primary key, auto increment'],
    ['nama_divisi','varchar (unique)','Nama divisi perusahaan'],
    ['created_at','timestamp','Waktu pembuatan record'],
    ['updated_at','timestamp','Waktu pembaruan record'],
]) + """

<h3>4.5.3 Tabel Users</h3>
<p>Tabel <code>users</code> merupakan tabel inti yang menyimpan data seluruh pengguna sistem. Tabel ini memiliki relasi foreign key ke tabel roles dan divisions untuk menentukan hak akses dan unit kerja setiap pengguna.</p>
""" + tbl(['Kolom','Tipe Data','Keterangan'],[
    ['id','bigint unsigned (PK, AI)','Primary key, auto increment'],
    ['name','varchar(255)','Nama lengkap pengguna'],
    ['email','varchar(255) (unique)','Alamat email sebagai identitas login'],
    ['email_verified_at','timestamp (nullable)','Waktu verifikasi email'],
    ['password','varchar(255) (hashed)','Kata sandi terenkripsi bcrypt'],
    ['role_id','bigint unsigned (FK)','Foreign key ke tabel roles'],
    ['division_id','bigint unsigned (FK, nullable)','Foreign key ke tabel divisions'],
    ['remember_token','varchar(100)','Token untuk fitur remember me'],
    ['created_at','timestamp','Waktu pembuatan akun'],
    ['updated_at','timestamp','Waktu pembaruan data akun'],
]) + """

<h3>4.5.4 Tabel Activities</h3>
<p>Tabel <code>activities</code> berfungsi mencatat seluruh log aktivitas yang dilakukan pengguna dalam sistem. Tabel ini memiliki foreign key ke tabel users dengan constraint cascade on delete, yang berarti apabila data user dihapus maka seluruh aktivitas terkait juga akan terhapus secara otomatis.</p>
""" + tbl(['Kolom','Tipe Data','Keterangan'],[
    ['id','bigint unsigned (PK, AI)','Primary key, auto increment'],
    ['user_id','bigint unsigned (FK, cascade delete)','Foreign key ke tabel users'],
    ['tanggal','datetime','Tanggal dan waktu aktivitas dilakukan'],
    ['deskripsi','text','Deskripsi detail aktivitas'],
    ['status','varchar (default: submitted)','Status aktivitas (submitted, created, dll)'],
    ['created_at','timestamp','Waktu pembuatan record'],
    ['updated_at','timestamp','Waktu pembaruan record'],
]) + """

<h3>4.5.5 Tabel Reports</h3>
<p>Tabel <code>reports</code> menyimpan data laporan yang dibuat oleh Super Admin melalui modul Reports Center. Tabel ini mendukung empat jenis laporan yaitu financial, department, growth, dan custom.</p>
""" + tbl(['Kolom','Tipe Data','Keterangan'],[
    ['id','bigint unsigned (PK, AI)','Primary key, auto increment'],
    ['title','varchar(255)','Judul laporan'],
    ['type','varchar','Jenis laporan (financial, department, growth, custom)'],
    ['description','text (nullable)','Deskripsi atau isi laporan'],
    ['start_date','date (nullable)','Tanggal mulai periode laporan'],
    ['end_date','date (nullable)','Tanggal akhir periode laporan'],
    ['status','enum (generated, ready, failed)','Status pemrosesan laporan'],
    ['created_by','bigint unsigned (FK, nullable)','Foreign key ke tabel users (pembuat)'],
    ['created_at','timestamp','Waktu pembuatan laporan'],
    ['updated_at','timestamp','Waktu pembaruan laporan'],
]) + """

<h3>4.5.6 Relasi Antar Tabel</h3>
<p>Seluruh relasi antar tabel dalam sistem ERP ini menggunakan pola Eloquent Relationship pada Laravel. Hubungan antar entitas didefinisikan dalam model masing-masing menggunakan method <code>belongsTo()</code> dan <code>hasMany()</code>. Berikut adalah ringkasan relasi antar tabel:</p>
""" + tbl(['Relasi','Tipe','Penjelasan'],[
    ['Role &rarr; Users','One-to-Many (hasMany)','Satu role dapat dimiliki oleh banyak user'],
    ['Division &rarr; Users','One-to-Many (hasMany)','Satu divisi dapat memiliki banyak user'],
    ['User &rarr; Role','Many-to-One (belongsTo)','Setiap user memiliki satu role'],
    ['User &rarr; Division','Many-to-One (belongsTo)','Setiap user memiliki satu divisi (nullable)'],
    ['User &rarr; Activities','One-to-Many (hasMany)','Satu user dapat memiliki banyak aktivitas'],
    ['Activity &rarr; User','Many-to-One (belongsTo)','Setiap aktivitas dimiliki oleh satu user'],
    ['User &rarr; Reports','One-to-Many','Satu user dapat membuat banyak laporan'],
    ['Report &rarr; User (creator)','Many-to-One (belongsTo)','Setiap laporan dibuat oleh satu user'],
]) + """
<p>Seluruh foreign key diimplementasikan dengan constraint yang sesuai. Foreign key <code>user_id</code> pada tabel activities menggunakan <code>cascadeOnDelete()</code> sehingga data aktivitas otomatis terhapus ketika user dihapus. Sementara foreign key <code>created_by</code> pada tabel reports menggunakan <code>nullOnDelete()</code> sehingga data laporan tetap tersimpan meskipun user pembuat dihapus.</p>

<h2>4.6 Implementasi Backend (MVC Laravel)</h2>

<h3>4.6.1 Arsitektur Model-View-Controller</h3>
<p>Sistem ERP Prototype ini mengimplementasikan pola arsitektur Model-View-Controller (MVC) yang merupakan standar bawaan framework Laravel. Pola MVC memisahkan logika aplikasi menjadi tiga komponen utama yang saling terhubung namun memiliki tanggung jawab yang berbeda:</p>
<ol>
<li><b>Model</b> &mdash; Terdapat lima model utama yaitu <code>User</code>, <code>Role</code>, <code>Division</code>, <code>Activity</code>, dan <code>Report</code>. Setiap model mendefinisikan fillable attributes untuk mass assignment protection, casting untuk konversi tipe data otomatis, dan relasi Eloquent untuk menghubungkan antar entitas.</li>
<li><b>View</b> &mdash; Menggunakan Blade Template Engine dengan sistem layout inheritance. Layout utama <code>layouts/admin.blade.php</code> menjadi template induk untuk seluruh halaman panel admin. TailwindCSS 4.0 dan Alpine.js digunakan untuk styling dan interaktivitas.</li>
<li><b>Controller</b> &mdash; Terdapat delapan controller yang menangani logika bisnis, meliputi <code>AdminDashboardController</code>, <code>UserController</code>, <code>ActivityController</code>, <code>ReportController</code>, <code>LoginController</code>, <code>ProfileController</code>, <code>DashboardController</code>, dan <code>AuthenticatedSessionController</code>.</li>
</ol>

<h3>4.6.2 Sistem Autentikasi</h3>
<p>Proses autentikasi diimplementasikan melalui <code>LoginController</code> dengan alur sebagai berikut: (1) Pengguna mengakses halaman login melalui route GET <code>/login</code>; (2) Pengguna mengisi email dan password, kemudian mengirim form melalui route POST <code>/login</code>; (3) Sistem memvalidasi input dengan aturan required dan email; (4) <code>Auth::attempt()</code> memverifikasi kredensial terhadap data di tabel users; (5) Jika valid, session diregenerasi untuk keamanan dan pengguna diarahkan ke route <code>/dashboard</code>; (6) Route <code>/dashboard</code> memeriksa role pengguna dan melakukan redirect ke dashboard yang sesuai.</p>

<h3>4.6.3 Sistem Routing</h3>
<p>Routing sistem dikonfigurasi dalam file <code>routes/web.php</code> dengan pengelompokan berdasarkan middleware dan prefix. Terdapat empat kelompok route utama:</p>
""" + tbl(['Kelompok Route','Prefix','Middleware','Keterangan'],[
    ['Auth Routes','/login','guest','Route untuk halaman login (GET dan POST)'],
    ['Admin Routes','/admin','auth','Dashboard, Users, Activities, Reports'],
    ['Karyawan Routes','/karyawan','auth','Dashboard karyawan'],
    ['Utility Routes','/','-','Profile, Logout, Root redirect'],
]) + """

<h3>4.6.4 Fitur Dark Mode</h3>
<p>Sistem mendukung fitur dark mode yang diimplementasikan menggunakan Alpine.js dengan penyimpanan preferensi di localStorage browser. TailwindCSS dikonfigurasi dengan <code>darkMode: 'class'</code> untuk mendukung styling kondisional berdasarkan tema aktif. Fitur ini meningkatkan kenyamanan pengguna dalam menggunakan sistem.</p>

<h2>4.7 Pengujian Sistem</h2>
<p>Pengujian sistem dilakukan menggunakan metode <i>Black Box Testing</i>, yaitu metode pengujian yang berfokus pada fungsionalitas sistem tanpa memperhatikan struktur internal kode program. Pengujian dilakukan untuk memastikan bahwa setiap fitur sistem berjalan sesuai dengan kebutuhan yang telah didefinisikan pada tahap perancangan.</p>

<h3>4.7.1 Hasil Pengujian Black Box</h3>
<p class="no-indent">Berikut adalah tabel hasil pengujian Black Box Testing pada Sistem ERP Prototype:</p>
"""

bb_rows = [
    ['1','Login Valid','Memasukkan email dan password yang benar, klik Sign In','Pengguna berhasil login dan diarahkan ke dashboard sesuai role','Berhasil'],
    ['2','Login Tidak Valid','Memasukkan email atau password yang salah, klik Sign In','Muncul pesan error "Email atau password salah"','Berhasil'],
    ['3','Redirect Super Admin','Login menggunakan akun Super Admin','Diarahkan ke halaman /admin/dashboard','Berhasil'],
    ['4','Redirect Karyawan','Login menggunakan akun Karyawan','Diarahkan ke halaman /karyawan/dashboard','Berhasil'],
    ['5','Tampilan Dashboard Admin','Mengakses halaman /admin/dashboard','Menampilkan KPI, grafik analytics, dan quick actions','Berhasil'],
    ['6','Menambahkan User Baru','Klik Add User, mengisi form lengkap, klik submit','User baru tersimpan dan muncul di daftar pengguna','Berhasil'],
    ['7','Validasi Form User','Submit form tambah user dengan field kosong','Muncul pesan validasi error pada field yang kosong','Berhasil'],
    ['8','Pencarian User','Mengetik nama/email pada kolom search','Tabel menampilkan hasil pencarian yang sesuai','Berhasil'],
    ['9','Reset Password User','Klik Reset Password pada user, konfirmasi reset','Password user berhasil direset ke default','Berhasil'],
    ['10','Monitoring Aktivitas','Mengakses halaman /admin/activities','Menampilkan log aktivitas dengan KPI dan paginasi','Berhasil'],
    ['11','Generate Report','Memilih jenis report, mengisi form, submit','Report berhasil disimpan dan tampil di daftar','Berhasil'],
    ['12','Melihat Detail Report','Klik View pada report di tabel recent reports','Menampilkan halaman detail report','Berhasil'],
    ['13','Akses Halaman Profil','Klik avatar pengguna, pilih Profile','Menampilkan informasi akun dan ringkasan aktivitas','Berhasil'],
    ['14','Toggle Dark Mode','Klik tombol toggle Dark/Light di header','Tampilan berubah sesuai tema yang dipilih','Berhasil'],
    ['15','Logout','Klik tombol Logout pada dropdown profil','Session dihapus dan diarahkan kembali ke halaman login','Berhasil'],
    ['16','Akses Tanpa Login','Mengakses URL /admin/dashboard tanpa login','Diarahkan kembali ke halaman login','Berhasil'],
    ['17','Duplikasi Email','Menambahkan user dengan email yang sudah terdaftar','Muncul pesan error validasi email unique','Berhasil'],
    ['18','Paginasi Data','Mengakses halaman activities dengan data lebih dari 15','Data ditampilkan dengan pagination 15 per halaman','Berhasil'],
]

sec47_table = tbl(['No','Skenario Pengujian','Langkah Pengujian','Hasil yang Diharapkan','Status'], bb_rows)

sec47b = """
<h3>4.7.2 Pengujian Validasi Input</h3>
<p>Sistem menerapkan validasi input pada sisi server (<i>server-side validation</i>) menggunakan fitur Request Validation bawaan Laravel. Validasi ini memastikan bahwa data yang dikirimkan oleh pengguna memenuhi aturan yang telah ditetapkan sebelum diproses lebih lanjut. Berikut adalah aturan validasi yang diterapkan pada setiap form dalam sistem:</p>
""" + tbl(['Form','Field','Aturan Validasi'],[
    ['Login','email','required, email'],
    ['Login','password','required'],
    ['Tambah User','name','required, string, max:255'],
    ['Tambah User','email','required, email, unique:users'],
    ['Tambah User','role_id','required, exists:roles,id'],
    ['Tambah User','division_id','required, exists:divisions,id'],
    ['Generate Report','title','required, string, max:255'],
    ['Generate Report','type','required, string'],
    ['Generate Report','report_date','required, date'],
    ['Generate Report','attachment','nullable, file, max:2048'],
]) + """

<h2>4.8 Pembahasan</h2>
<p>Pada subbab ini diuraikan pembahasan mengenai hasil implementasi Sistem ERP Prototype yang telah dibangun selama pelaksanaan Kerja Praktik. Pembahasan mencakup analisis kelebihan dan kekurangan sistem berdasarkan hasil implementasi dan pengujian yang telah dilakukan.</p>

<h3>4.8.1 Kelebihan Sistem</h3>
<p>Berdasarkan hasil implementasi dan pengujian, Sistem ERP Prototype PT. Golden Intan Berlian memiliki beberapa kelebihan sebagai berikut:</p>
<ol>
<li><b>Arsitektur Terstruktur</b> &mdash; Penggunaan pola MVC pada framework Laravel memastikan pemisahan yang jelas antara logika bisnis (Controller), tampilan antarmuka (View), dan pengelolaan data (Model), sehingga kode program lebih terorganisir, mudah dipelihara, dan dapat dikembangkan secara modular.</li>
<li><b>Keamanan Data</b> &mdash; Sistem menerapkan beberapa mekanisme keamanan yang meliputi: hashing password menggunakan algoritma bcrypt, proteksi CSRF (<i>Cross-Site Request Forgery</i>) pada setiap form, regenerasi session setelah proses login, dan validasi input pada sisi server untuk mencegah injeksi data berbahaya.</li>
<li><b>Antarmuka Responsif dan Modern</b> &mdash; Antarmuka pengguna dibangun menggunakan TailwindCSS 4.0 yang memastikan tampilan responsif di berbagai ukuran layar. Dukungan fitur dark mode meningkatkan kenyamanan dan fleksibilitas penggunaan sistem.</li>
<li><b>Pencatatan Aktivitas Otomatis</b> &mdash; Setiap aksi penting yang dilakukan dalam sistem dicatat secara otomatis ke dalam tabel activities, sehingga tersedia audit trail yang komprehensif untuk keperluan monitoring dan evaluasi.</li>
<li><b>Visualisasi Data Informatif</b> &mdash; Penggunaan Chart.js untuk menampilkan grafik tren aktivitas pada dashboard membantu pengambilan keputusan berbasis data secara visual dan intuitif.</li>
<li><b>Pengembangan Cepat dengan RAD</b> &mdash; Penerapan metode RAD memungkinkan pengembangan sistem secara iteratif dan cepat, sesuai dengan keterbatasan waktu pelaksanaan Kerja Praktik.</li>
</ol>

<h3>4.8.2 Kekurangan Sistem</h3>
<p>Meskipun demikian, sebagai sebuah prototype, sistem ini juga memiliki beberapa kekurangan dan keterbatasan yang perlu diperhatikan secara realistis:</p>
<ol>
<li><b>Status Prototype</b> &mdash; Sistem masih berupa prototype sehingga beberapa fitur, khususnya fitur untuk aktor Karyawan seperti pencatatan aktivitas harian dan dashboard karyawan, belum diimplementasikan secara lengkap dan memerlukan pengembangan lebih lanjut.</li>
<li><b>Belum Terdapat Middleware Role</b> &mdash; Sistem belum memiliki middleware khusus untuk memvalidasi role pengguna pada setiap route admin. Saat ini pembatasan akses hanya berdasarkan redirect di route <code>/dashboard</code>, sehingga secara teoritis pengguna dengan role karyawan yang mengetahui URL admin dapat mengaksesnya.</li>
<li><b>Penyimpanan File Lokal</b> &mdash; Fitur upload lampiran pada modul report masih menggunakan penyimpanan lokal (<i>local storage</i>) dan belum terintegrasi dengan layanan cloud storage seperti Amazon S3 atau Google Cloud Storage untuk skalabilitas yang lebih baik.</li>
<li><b>Belum Terdapat Sistem Notifikasi</b> &mdash; Sistem belum memiliki fitur notifikasi real-time untuk memberitahu admin tentang aktivitas penting atau perubahan data dalam sistem.</li>
<li><b>Ekspor Data Terbatas</b> &mdash; Fitur download report belum sepenuhnya diimplementasikan untuk menghasilkan file dalam format PDF atau Excel, yang merupakan kebutuhan umum dalam konteks pelaporan bisnis.</li>
<li><b>Belum Dilakukan Performance Testing</b> &mdash; Pengujian yang dilakukan masih sebatas black box testing fungsional. Belum dilakukan pengujian performa (<i>performance testing</i>) untuk mengukur kemampuan sistem dalam menangani beban pengguna dalam jumlah besar.</li>
</ol>

<h3>4.8.3 Kesesuaian dengan Metode RAD</h3>
<p>Penerapan metode Rapid Application Development (RAD) dalam pengembangan Sistem ERP Prototype ini telah berjalan sesuai dengan tahapan yang didefinisikan. Fase <i>Requirements Planning</i> dilaksanakan melalui analisis kebutuhan dan observasi proses bisnis perusahaan. Fase <i>User Design</i> direalisasikan melalui perancangan diagram UML dan ERD. Fase <i>Construction</i> dilaksanakan melalui implementasi kode program menggunakan Laravel. Fase <i>Cutover</i> dilaksanakan melalui pengujian black box testing untuk memastikan fungsionalitas sistem.</p>

<p>Metode RAD terbukti efektif untuk pengembangan prototype ini karena memungkinkan iterasi yang cepat dalam proses pembangunan sistem. Namun demikian, keterbatasan waktu pelaksanaan Kerja Praktik menyebabkan beberapa fitur belum dapat diselesaikan secara sempurna dan memerlukan iterasi pengembangan tambahan di masa mendatang.</p>

</body>
</html>"""

# Read part 1 and combine
base = os.path.dirname(__file__)
with open(os.path.join(base, '_bab4_part1.html'), 'r', encoding='utf-8') as f:
    part1 = f.read()

full = part1 + sec45 + sec47_table + sec47b

with open(os.path.join(base, 'laporan_bab4_v2.html'), 'w', encoding='utf-8') as f:
    f.write(full)

# Cleanup
os.remove(os.path.join(base, '_bab4_part1.html'))

print("BAB IV generated: laporan_bab4_v2.html")
