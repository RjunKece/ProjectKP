
# Part 1: Sections 4.1 - 4.4
import os

CSS = """@page{margin:3cm 3cm 3cm 4cm}
body{font-family:'Times New Roman',Times,serif;font-size:12pt;color:#000;line-height:1.5;text-align:justify;margin:3cm 3cm 3cm 4cm;max-width:none}
h1{text-align:center;font-size:14pt;font-weight:bold;margin-bottom:24pt}
h2{font-size:12pt;font-weight:bold;margin-top:18pt;margin-bottom:6pt}
h3{font-size:12pt;font-weight:bold;margin-top:12pt;margin-bottom:6pt}
h4{font-size:12pt;font-weight:bold;margin-top:10pt;margin-bottom:6pt}
p{margin-bottom:6pt;text-indent:1.27cm}
table{border-collapse:collapse;width:100%;margin:12pt 0;font-size:11pt}
th,td{border:1px solid #000;padding:6px 8px;text-align:left}
th{background:#f0f0f0;font-weight:bold}
.no-indent{text-indent:0}
.img-placeholder{border:2px dashed #999;padding:40px;text-align:center;margin:12pt auto;color:#555;font-style:italic;background:#fafafa;max-width:80%}
ol,ul{margin-left:1cm;margin-bottom:6pt}
li{margin-bottom:4pt}
.center{text-align:center}
pre{font-family:'Courier New',monospace;font-size:10pt;background:#f5f5f5;padding:8px;border:1px solid #ccc;overflow-x:auto;margin:8pt 0}"""

def img(num, caption):
    return f'<div class="img-placeholder">[Gambar 4.{num} {caption}]</div>\n<p class="center no-indent"><b>Gambar 4.{num}</b> {caption}</p>\n'

def tbl(headers, rows):
    h = ''.join(f'<th>{x}</th>' for x in headers)
    r = ''
    for row in rows:
        r += '<tr>' + ''.join(f'<td>{x}</td>' for x in row) + '</tr>\n'
    return f'<table>\n<tr>{h}</tr>\n{r}</table>\n'

html = f"""<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>BAB IV - Hasil dan Pembahasan</title>
<style>{CSS}</style></head>
<body>

<h1>BAB IV<br>HASIL DAN PEMBAHASAN</h1>

<h2>4.1 Gambaran Umum Sistem</h2>
<p>Pada Bab IV ini diuraikan hasil perancangan dan implementasi Sistem Enterprise Resource Planning (ERP) sederhana berbasis web yang dikembangkan selama pelaksanaan Kerja Praktik di PT. Golden Intan Berlian. Sistem ini dirancang sebagai prototype pendukung proses bisnis perusahaan dengan tujuan mengintegrasikan fungsi-fungsi operasional ke dalam satu platform terpadu yang dapat diakses melalui browser.</p>

<p>Pengembangan sistem dilakukan dengan menggunakan metode Rapid Application Development (RAD), yang memungkinkan proses pembangunan aplikasi secara cepat dan iteratif. Metode RAD dipilih karena sesuai dengan keterbatasan waktu pelaksanaan Kerja Praktik, serta memungkinkan penyesuaian kebutuhan secara fleksibel berdasarkan umpan balik yang diperoleh selama proses pengembangan.</p>

<p>Sistem ERP Prototype ini dibangun menggunakan framework Laravel 12 dengan bahasa pemrograman PHP 8.2 dan berjalan di atas arsitektur Model-View-Controller (MVC). Basis data yang digunakan adalah MySQL, sementara antarmuka pengguna dikembangkan menggunakan Blade Template Engine dengan dukungan TailwindCSS 4.0 untuk styling responsif, Alpine.js untuk interaktivitas sisi klien, dan Chart.js untuk visualisasi data dalam bentuk grafik. Vite 7 digunakan sebagai build tool untuk pengelolaan aset frontend. Sistem dijalankan pada server lokal menggunakan Laragon sebagai lingkungan pengembangan.</p>

<p>Sistem ini dirancang untuk dua aktor utama, yaitu <b>Super Admin</b> dan <b>Karyawan</b>. Super Admin memiliki hak akses penuh terhadap seluruh fitur sistem, termasuk manajemen pengguna, monitoring aktivitas, dan pembuatan laporan. Sementara itu, aktor Karyawan dirancang untuk memiliki akses terbatas pada dashboard pribadi dan pencatatan aktivitas harian. Meskipun implementasi fitur untuk aktor Karyawan pada tahap prototype ini belum sepenuhnya lengkap, perancangan sistem telah mempertimbangkan peran tersebut untuk pengembangan di masa mendatang.</p>

<h2>4.2 Analisis Sistem</h2>

<h3>4.2.1 Analisis Sistem Berjalan</h3>
<p>Berdasarkan hasil observasi selama pelaksanaan Kerja Praktik, proses bisnis yang berjalan di PT. Golden Intan Berlian masih menggunakan metode konvensional dalam beberapa aspek operasionalnya. Pencatatan aktivitas karyawan, pengelolaan data pengguna, dan pembuatan laporan dilakukan secara manual menggunakan spreadsheet atau dokumen tercetak. Hal ini menimbulkan beberapa permasalahan, antara lain:</p>
<ol>
<li><b>Inefisiensi waktu</b> &mdash; Proses pencarian data dan penyusunan laporan memerlukan waktu yang cukup lama karena harus dilakukan secara manual.</li>
<li><b>Risiko kesalahan data</b> &mdash; Input data secara manual rentan terhadap human error seperti duplikasi data, kesalahan pengetikan, atau inkonsistensi format.</li>
<li><b>Keterbatasan akses informasi</b> &mdash; Data yang tersebar di berbagai dokumen menyulitkan proses monitoring dan pengambilan keputusan secara real-time.</li>
<li><b>Tidak adanya audit trail</b> &mdash; Tidak tersedia mekanisme pencatatan otomatis atas setiap perubahan dan aktivitas yang terjadi dalam proses bisnis.</li>
</ol>

<h3>4.2.2 Analisis Sistem Usulan</h3>
<p>Berdasarkan permasalahan pada sistem berjalan, diusulkan pengembangan Sistem ERP Prototype berbasis web yang mampu mengintegrasikan proses pengelolaan data pengguna, pencatatan aktivitas, dan pembuatan laporan ke dalam satu platform terpusat. Sistem usulan ini memiliki karakteristik sebagai berikut:</p>
<ol>
<li><b>Sentralisasi data</b> &mdash; Seluruh data tersimpan dalam satu basis data MySQL yang terstruktur dan dapat diakses secara terpusat.</li>
<li><b>Otomatisasi pencatatan aktivitas</b> &mdash; Setiap aksi yang dilakukan pengguna dalam sistem dicatat secara otomatis melalui service ActivityLogger.</li>
<li><b>Manajemen pengguna berbasis role</b> &mdash; Sistem mendukung pembagian hak akses berdasarkan role (Super Admin dan Karyawan) sehingga keamanan data terjaga.</li>
<li><b>Pembuatan laporan digital</b> &mdash; Laporan dapat dibuat, disimpan, dan diakses kembali secara digital melalui modul Reports Center.</li>
<li><b>Antarmuka responsif dan modern</b> &mdash; Tampilan sistem dirancang responsif menggunakan TailwindCSS sehingga dapat diakses dari berbagai perangkat.</li>
</ol>

<h2>4.3 Perancangan Sistem</h2>
<p>Tahap perancangan sistem merupakan fase kedua dalam metode RAD setelah fase perencanaan kebutuhan (<i>Requirements Planning</i>). Pada tahap ini dilakukan pemodelan proses bisnis dan struktur data menggunakan diagram UML (Unified Modeling Language) serta Entity Relationship Diagram (ERD). Perancangan ini menjadi acuan dalam proses implementasi sistem.</p>

<h3>4.3.1 Use Case Diagram</h3>
<p>Use Case Diagram digunakan untuk memodelkan interaksi antara aktor dengan sistem. Dalam Sistem ERP Prototype ini terdapat dua aktor utama, yaitu Super Admin dan Karyawan. Setiap aktor memiliki hak akses dan fungsionalitas yang berbeda sesuai dengan perannya masing-masing dalam sistem.</p>

<p>Super Admin sebagai aktor dengan hak akses tertinggi dapat melakukan seluruh fungsionalitas sistem yang meliputi: login ke sistem, mengakses dashboard admin yang menampilkan Key Performance Indicator (KPI), mengelola data pengguna (tambah user dan reset password), melakukan monitoring aktivitas seluruh pengguna, membuat dan mengelola laporan (Reports), serta mengelola profil akun. Sementara itu, aktor Karyawan memiliki akses terbatas yang meliputi: login ke sistem, mengakses dashboard karyawan, melihat profil pribadi, dan melakukan logout. Meskipun pada tahap prototype ini fitur Karyawan belum diimplementasikan secara lengkap, use case untuk aktor tersebut tetap dimodelkan sebagai bagian dari perancangan sistem secara keseluruhan.</p>

{img(1, "Use Case Diagram Sistem ERP")}

<h3>4.3.2 Activity Diagram</h3>
<p>Activity Diagram digunakan untuk memodelkan alur kerja (<i>workflow</i>) dari proses-proses utama dalam sistem. Diagram aktivitas ini menggambarkan urutan langkah-langkah yang dilakukan oleh aktor dalam menjalankan fungsionalitas tertentu, termasuk percabangan kondisi dan alur alternatif.</p>

<p>Alur utama yang dimodelkan dalam Activity Diagram meliputi: (1) Proses Login, yang dimulai dari pengisian kredensial oleh pengguna, validasi oleh sistem, pengecekan role, hingga redirect ke dashboard yang sesuai; (2) Proses Manajemen User, yang mencakup alur penambahan user baru dengan validasi input dan pencatatan aktivitas; (3) Proses Monitoring Aktivitas, yang menggambarkan alur akses dan filter data aktivitas; serta (4) Proses Pembuatan Laporan, yang meliputi pemilihan jenis laporan, pengisian detail, preview, konfirmasi, dan penyimpanan.</p>

{img(2, "Activity Diagram Sistem ERP")}

<h3>4.3.3 Entity Relationship Diagram (ERD)</h3>
<p>Entity Relationship Diagram (ERD) digunakan untuk memodelkan struktur basis data serta hubungan antar entitas dalam sistem. ERD Sistem ERP Prototype ini menggambarkan lima entitas utama beserta atribut dan relasinya. Entitas-entitas tersebut adalah: <b>users</b> (menyimpan data pengguna), <b>roles</b> (menyimpan data hak akses), <b>divisions</b> (menyimpan data divisi perusahaan), <b>activities</b> (menyimpan log aktivitas), dan <b>reports</b> (menyimpan data laporan).</p>

<p>Relasi yang terdapat dalam ERD meliputi: relasi one-to-many antara tabel roles dan users (satu role dimiliki oleh banyak user), relasi one-to-many antara tabel divisions dan users (satu divisi memiliki banyak user), relasi one-to-many antara tabel users dan activities (satu user memiliki banyak aktivitas), serta relasi one-to-many antara tabel users dan reports (satu user dapat membuat banyak laporan). Seluruh relasi diimplementasikan menggunakan foreign key constraint pada MySQL.</p>

{img(3, "Entity Relationship Diagram (ERD) Sistem ERP")}

<h2>4.4 Implementasi Sistem</h2>
<p>Tahap implementasi merupakan fase ketiga dalam metode RAD yang disebut <i>Construction Phase</i>. Pada tahap ini, hasil perancangan diterjemahkan ke dalam kode program menggunakan teknologi yang telah ditentukan. Berikut adalah uraian implementasi setiap halaman dan fitur utama dalam Sistem ERP Prototype.</p>

<h3>4.4.1 Halaman Login</h3>
<p>Halaman login merupakan titik masuk utama (<i>entry point</i>) bagi seluruh pengguna untuk mengakses Sistem ERP. Halaman ini menampilkan formulir autentikasi yang terdiri dari dua field input, yaitu email dan password. Desain halaman login menggunakan layout dua kolom (<i>split screen</i>) dengan bagian kiri menampilkan formulir login dan bagian kanan menampilkan branding perusahaan PT. Golden Intan Berlian dengan skema warna emas (gold) sebagai identitas visual.</p>

<p>Fungsi utama halaman login adalah melakukan proses autentikasi pengguna menggunakan facade <code>Auth::attempt()</code> pada Laravel. Setelah kredensial divalidasi, sistem melakukan regenerasi session untuk mencegah serangan session fixation, kemudian mengarahkan pengguna ke halaman dashboard sesuai dengan role yang dimiliki. Apabila Super Admin yang login, sistem akan melakukan redirect ke <code>/admin/dashboard</code>, sedangkan Karyawan akan diarahkan ke <code>/karyawan/dashboard</code>. Jika kredensial tidak valid, sistem menampilkan pesan error "Email atau password salah."</p>

{img(4, "Halaman Login Sistem ERP")}

<h3>4.4.2 Dashboard Admin</h3>
<p>Dashboard Admin merupakan halaman utama yang ditampilkan setelah Super Admin berhasil melakukan login. Halaman ini berfungsi sebagai pusat informasi (<i>control center</i>) yang menyajikan ringkasan data operasional sistem secara real-time. Dashboard dirancang untuk memberikan gambaran menyeluruh tentang kondisi sistem melalui visualisasi data yang informatif dan mudah dipahami.</p>

<p>Dashboard Admin terdiri dari tiga komponen utama: (1) <b>System Snapshot</b> yang menampilkan enam Key Performance Indicator (KPI) meliputi Total Users, Active Employees, Total Activities, Today Activity, Productivity (estimasi persentase produktivitas bulanan), dan Growth (persentase pertumbuhan); (2) <b>Activity Analytics</b> yang menampilkan grafik line chart menggunakan library Chart.js untuk memvisualisasikan tren aktivitas karyawan dan super admin selama 12 bulan terakhir; serta (3) <b>Quick Actions</b> yang menyediakan pintasan navigasi cepat ke fitur Manage Users, Review Activities, dan Generate Reports.</p>

{img(5, "Halaman Dashboard Admin")}

<h3>4.4.3 Dashboard Karyawan</h3>
<p>Dashboard Karyawan merupakan halaman yang dirancang untuk pengguna dengan role karyawan setelah berhasil login. Halaman ini bertujuan untuk menyediakan antarmuka yang memungkinkan karyawan melihat informasi terkait tugas, aktivitas harian, dan profil pribadi mereka. Pada tahap prototype saat ini, dashboard karyawan telah memiliki halaman dasar dengan tampilan informasi pengguna yang sedang login.</p>

<p>Secara konseptual, dashboard karyawan dirancang untuk menyediakan fitur-fitur seperti: ringkasan aktivitas pribadi, pencatatan aktivitas harian, notifikasi dari admin, dan akses ke profil. Meskipun implementasi fitur-fitur tersebut belum sepenuhnya lengkap pada tahap prototype ini, arsitektur sistem telah disiapkan untuk mengakomodasi pengembangan di masa mendatang melalui routing terpisah dengan prefix <code>/karyawan</code> dan controller yang independen.</p>

{img(6, "Halaman Dashboard Karyawan")}

<h3>4.4.4 Manajemen User</h3>
<p>Halaman Manajemen User (<i>User Management</i>) merupakan fitur yang memungkinkan Super Admin untuk mengelola seluruh akun pengguna yang terdaftar dalam sistem. Fitur ini merupakan salah satu komponen inti dari modul Human Resource Management dalam konsep ERP.</p>

<p>Halaman ini terdiri dari beberapa komponen: (1) <b>Summary Cards</b> yang menampilkan statistik Total Users, Active Users, Super Admin, dan Pending Approval; (2) <b>Filter dan Pencarian</b> berupa toolbar untuk mencari pengguna berdasarkan nama atau email; (3) <b>Tabel Data Pengguna</b> yang menampilkan daftar pengguna dengan informasi nama, email, role, divisi, status, dan tombol aksi; (4) <b>Tambah User</b> melalui modal form dengan field nama, email, role, dan divisi, dimana password default ditetapkan sebagai "password123" yang di-hash menggunakan bcrypt; serta (5) <b>Reset Password</b> untuk mereset password pengguna ke default melalui modal konfirmasi.</p>

{img(7, "Halaman Manajemen User")}

<h3>4.4.5 Monitoring Aktivitas</h3>
<p>Halaman Monitoring Aktivitas berfungsi sebagai pusat pemantauan seluruh log aktivitas yang tercatat dalam sistem. Fitur ini memungkinkan Super Admin untuk melacak dan mengawasi setiap aksi yang dilakukan oleh pengguna, sehingga mendukung fungsi audit trail dan pengawasan operasional.</p>

<p>Komponen utama halaman ini meliputi: (1) <b>KPI Summary</b> berupa empat kartu indikator yang menampilkan Total Aktivitas, Aktivitas Hari Ini, Aktivitas Admin, dan Aktivitas Karyawan; (2) <b>Filter Bar</b> berupa toolbar pencarian dengan filter berdasarkan role, divisi, dan tanggal; serta (3) <b>Activity Table</b> berupa tabel yang menampilkan informasi user, role, divisi, deskripsi aktivitas, dan waktu dengan paginasi 15 item per halaman menggunakan fitur pagination bawaan Laravel.</p>

{img(8, "Halaman Monitoring Aktivitas")}

<h3>4.4.6 Halaman Reports</h3>
<p>Halaman Reports Center merupakan pusat pembuatan dan pengelolaan laporan strategis dalam sistem ERP. Fitur ini memungkinkan Super Admin untuk menghasilkan berbagai jenis laporan yang mendukung proses pengambilan keputusan manajemen.</p>

<p>Halaman ini terdiri dari: (1) <b>KPI Strip</b> yang menampilkan lima indikator berupa Total Reports, Generated This Month, Scheduled, System Coverage, dan Storage Used; (2) <b>Report Builder</b> dengan empat modul pembuatan laporan yaitu Financial Intelligence, Department Performance, Growth &amp; Trends, dan Custom Intelligence, dimana masing-masing modul membuka modal form untuk mengisi detail laporan; (3) <b>Recent Reports</b> berupa tabel laporan terbaru dengan informasi judul, tipe, periode, status, dan aksi (View/Download) dengan paginasi 10 item per halaman; serta (4) <b>System Insight</b> yang menampilkan statistik penggunaan laporan.</p>

{img(9, "Halaman Reports Center")}

<h3>4.4.7 Halaman Profil Pengguna</h3>
<p>Halaman Profil menampilkan informasi detail akun pengguna yang sedang login. Halaman ini dapat diakses oleh seluruh pengguna baik Super Admin maupun Karyawan. Fitur ini berfungsi untuk memberikan transparansi informasi akun kepada pengguna.</p>

<p>Komponen halaman profil meliputi: (1) <b>Profile Overview</b> berupa avatar inisial, nama, email, badge role, dan status aktif; (2) <b>Account Information</b> menampilkan detail role, divisi, dan tanggal registrasi; serta (3) <b>Activity Summary</b> yang menampilkan ringkasan total aktivitas dan aktivitas bulan berjalan.</p>

{img(10, "Halaman Profil Pengguna")}
"""

with open(os.path.join(os.path.dirname(__file__), '_bab4_part1.html'), 'w', encoding='utf-8') as f:
    f.write(html)
print("Part 1 done")
