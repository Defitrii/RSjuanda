<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\BayarController;
use App\Http\Controllers\Backend\ChatPaymentController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DokterController;
use App\Http\Controllers\Backend\FisioterapiController as BackendFisioterapiController;
use App\Http\Controllers\Backend\GantiPasswordController;
use App\Http\Controllers\Backend\HomecareController;
use App\Http\Controllers\Backend\JabatanController;
use App\Http\Controllers\Backend\KategoriController;
use App\Http\Controllers\backend\LaporanTransaksiFisioeterapiController;
use App\Http\Controllers\Backend\LaporanTransaksiHomecareController;
use App\Http\Controllers\Backend\LaporanTransaksiPaketHomecareController;
use App\Http\Controllers\Backend\LayananController;
use App\Http\Controllers\Backend\PasienController;
use App\Http\Controllers\Backend\PerawatController;
use App\Http\Controllers\Backend\PoliController;
use App\Http\Controllers\Backend\RatingTelemedicineController;
use App\Http\Controllers\backend\RekamMedisController;
use App\Http\Controllers\Backend\TransaksiFisioterapiController;
use App\Http\Controllers\Backend\TransaksiHomecareController;
use App\Http\Controllers\Backend\TransaksiHomecarePerawatController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\BerandaController;
use App\Http\Controllers\Frontend\FisioterapiController;
use App\Http\Controllers\Frontend\HomecareController as FrontendHomecareController;
use App\Http\Controllers\Frontend\PaketHomecareController;
use App\Http\Controllers\Frontend\TelemedicineController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('/layanan/fisioterapi', [FisioterapiController::class, 'index'])->name('frontend.fisioterapi');
Route::get('/layanan/fisioterapi/detail/{fisioterapi}', [FisioterapiController::class, 'detail'])->name('frontend.fisioterapi.detail');

Route::get('/layanan/paket-homecare', [PaketHomecareController::class, 'index'])->name('frontend.paket-homecare');
Route::get('/layanan/paket-homecare/detail/{paketHomecare}', [PaketHomecareController::class, 'detail'])->name('frontend.paket-homecare.detail');

Route::get('/layanan/homecare', [FrontendHomecareController::class, 'index'])->name('frontend.homecare');
Route::get('/layanan/homecare/detail/{homecare}', [FrontendHomecareController::class, 'detail'])->name('frontend.homecare.detail');

Route::get('/layanan/telemedicine', [TelemedicineController::class, 'index'])->name('frontend.telemedicine');
Route::get('/layanan/telemedicine/detail/{telemedicine}', [TelemedicineController::class, 'detail'])->name('frontend.telemedicine.detail');

Route::post('/register/getKabupaten', [RegisterController::class, 'getKabupaten'])->name('register.get-kabupaten');
Route::post('/register/getKecamatan', [RegisterController::class, 'getKecamatan'])->name('register.get-kecamatan');
Route::post('/register/getDesa', [RegisterController::class, 'getDesa'])->name('register.get-desa');

Auth::routes();

Route::group(['middleware' => ['auth', 'user-access:Pasien,Administrator,Perawat,Dokter']], function () {
    // Rating
    Route::post('/layanan/telemedicine/rating', [TelemedicineController::class, 'store'])->name('frontend.telemedicineRating');
    Route::post('/layanan/fisioterapi/rating', [FisioterapiController::class, 'store'])->name('frontend.fisioterapiRating');
    Route::post('/layanan/paket-homecare/rating', [PaketHomecareController::class, 'store'])->name('frontend.paketHomecareRating');
    Route::post('/layanan/homecare/rating', [FrontendHomecareController::class, 'store'])->name('frontend.homecareRating');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Ganti Password
    Route::get('/ganti-password', [GantiPasswordController::class, 'index'])->name('ganti-password.index');
    Route::post('/ganti-password', [GantiPasswordController::class, 'update'])->name('ganti-password.update');

    //Profile
    Route::get('/pengguna/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/pengguna/profile/{user}', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/pengguna/getKabupaten', [TransaksiHomecareController::class, 'getKabupaten'])->name('user.get-kabupaten');
    Route::post('/pengguna/getKecamatan', [TransaksiHomecareController::class, 'getKecamatan'])->name('user.get-kecamatan');
    Route::post('/pengguna/getDesa', [TransaksiHomecareController::class, 'getDesa'])->name('user.get-desa');

    //Transaksi Paket Homecare
    Route::post('/transaksi-homecare/getKabupaten', [TransaksiHomecareController::class, 'getKabupaten'])->name('transaksi-homecare.get-kabupaten');
    Route::post('/transaksi-homecare/getKecamatan', [TransaksiHomecareController::class, 'getKecamatan'])->name('transaksi-homecare.get-kecamatan');
    Route::post('/transaksi-homecare/getDesa', [TransaksiHomecareController::class, 'getDesa'])->name('transaksi-homecare.get-desa');
    Route::post('/transaksi-homecare/getHomecare', [TransaksiHomecareController::class, 'getHomecare'])->name('transaksi-homecare.get-homecare');
    Route::post('/transaksi-homecare/delete-multiple-transaksi-homecare', [TransaksiHomecareController::class, 'deleteMultiple'])->name('delete-multiple-transaksi-homecare');
    Route::post('/transaksi-homecare/aktif/{homecare}', [TransaksiHomecareController::class, 'aktif'])->name('transaksi-homecare.aktif');
    Route::post('/transaksi-homecare/nonaktif/{homecare}', [TransaksiHomecareController::class, 'nonaktif'])->name('transaksi-homecare.nonaktif');
    Route::get('/transaksi-homecare', [TransaksiHomecareController::class, 'index'])->name('transaksi-homecare.index');
    Route::get('/transaksi-homecare/tambah', [TransaksiHomecareController::class, 'create'])->name('transaksi-homecare.create');
    Route::post('/transaksi-homecare', [TransaksiHomecareController::class, 'store'])->name('transaksi-homecare.store');
    Route::get('/transaksi-homecare/print/{id}', [TransaksiHomecareController::class, 'print'])->name('transaksi-homecare.print');
    Route::post('/transaksi-homecare/detail/{homecare}', [TransaksiHomecareController::class, 'detail'])->name('transaksi-homecare.detail');
    Route::delete('/transaksi-homecare/{homecare}', [TransaksiHomecareController::class, 'destroy'])->name('transaksi-homecare.destroy');
    Route::get('/transaksi-homecare/printPDF', [TransaksiHomecareController::class, 'printPDF'])->name('transaksi-homecare.printPDF');
    Route::get('/transaksi-homecare/exportExcel', [TransaksiHomecareController::class, 'exportExcel'])->name('transaksi-homecare.exportExcel');

    //Transaksi Homecare
    Route::post('/transaksi-homecare-perawat/getHomecarePrice', [TransaksiHomecarePerawatController::class, 'getHomecarePrice'])->name('transaksi-homecare-perawat.get-homecare-price');
    Route::post('/transaksi-homecare-perawat/delete-multiple-transaksi-homecare-perawat', [TransaksiHomecarePerawatController::class, 'deleteMultiple'])->name('delete-multiple-transaksi-homecare-perawat');
    Route::post('/transaksi-homecare-perawat/aktif/{homecare}', [TransaksiHomecarePerawatController::class, 'aktif'])->name('transaksi-homecare-perawat.aktif');
    Route::post('/transaksi-homecare-perawat/nonaktif/{homecare}', [TransaksiHomecarePerawatController::class, 'nonaktif'])->name('transaksi-homecare-perawat.nonaktif');
    Route::get('/transaksi-homecare-perawat', [TransaksiHomecarePerawatController::class, 'index'])->name('transaksi-homecare-perawat.index');
    Route::get('/transaksi-homecare-perawat/tambah', [TransaksiHomecarePerawatController::class, 'create'])->name('transaksi-homecare-perawat.create');
    Route::post('/transaksi-homecare-perawat', [TransaksiHomecarePerawatController::class, 'store'])->name('transaksi-homecare-perawat.store');
    Route::get('/transaksi-homecare-perawat/print/{id}', [TransaksiHomecarePerawatController::class, 'print'])->name('transaksi-homecare-perawat.print');
    Route::post('/transaksi-homecare-perawat/detail/{homecare}', [TransaksiHomecarePerawatController::class, 'detail'])->name('transaksi-homecare-perawat.detail');
    Route::delete('/transaksi-homecare-perawat/{homecare}', [TransaksiHomecarePerawatController::class, 'destroy'])->name('transaksi-homecare-perawat.destroy');
    Route::get('/transaksi-homecare-perawat/printPDF', [TransaksiHomecarePerawatController::class, 'printPDF'])->name('transaksi-homecare-perawat.printPDF');
    Route::get('/transaksi-homecare-perawat/exportExcel/', [TransaksiHomecarePerawatController::class, 'exportExcel'])->name('transaksi-homecare-perawat.exportExcel');

    //Transaksi Fisioterapi
    Route::post('/transaksi-fisioterapi/getKabupaten', [TransaksiFisioterapiController::class, 'getKabupaten'])->name('transaksi-fisioterapi.get-kabupaten');
    Route::post('/transaksi-fisioterapi/getKecamatan', [TransaksiFisioterapiController::class, 'getKecamatan'])->name('transaksi-fisioterapi.get-kecamatan');
    Route::post('/transaksi-fisioterapi/getDesa', [TransaksiFisioterapiController::class, 'getDesa'])->name('transaksi-fisioterapi.get-desa');
    Route::post('/transaksi-fisioterapi/getFisioterapi', [TransaksiFisioterapiController::class, 'getFisioterapi'])->name('transaksi-fisioterapi.get-fisioterapi');
    Route::post('/transaksi-fisioterapi/delete-multiple-transaksi-fisioterapi', [TransaksiFisioterapiController::class, 'deleteMultiple'])->name('delete-multiple-transaksi-fisioterapi');
    Route::post('/transaksi-fisioterapi/aktif/{fisioterapi}', [TransaksiFisioterapiController::class, 'aktif'])->name('transaksi-fisioterapi.aktif');
    Route::post('/transaksi-fisioterapi/nonaktif/{fisioterapi}', [TransaksiFisioterapiController::class, 'nonaktif'])->name('transaksi-fisioterapi.nonaktif');
    Route::get('/transaksi-fisioterapi', [TransaksiFisioterapiController::class, 'index'])->name('transaksi-fisioterapi.index');
    Route::get('/transaksi-fisioterapi/tambah', [TransaksiFisioterapiController::class, 'create'])->name('transaksi-fisioterapi.create');
    Route::post('/transaksi-fisioterapi', [TransaksiFisioterapiController::class, 'store'])->name('transaksi-fisioterapi.store');
    Route::get('/transaksi-fisioterapi/print/{id}', [TransaksiFisioterapiController::class, 'print'])->name('transaksi-fisioterapi.print');
    Route::post('/transaksi-fisioterapi/detail/{fisioterapi}', [TransaksiFisioterapiController::class, 'detail'])->name('transaksi-fisioterapi.detail');
    Route::delete('/transaksi-fisioterapi/{fisioterapi}', [TransaksiFisioterapiController::class, 'destroy'])->name('transaksi-fisioterapi.destroy');
    Route::get('/transaksi-fisioterapi/printPDF', [TransaksiFisioterapiController::class, 'printPDF'])->name('transaksi-fisioterapi.printPDF');
    Route::get('/transaksi-fisioterapi/exportExcel', [TransaksiFisioterapiController::class, 'exportExcel'])->name('transaksi-fisioterapi.exportExcel');
});

Route::group(['middleware' => ['auth', 'user-access:Pasien,Administrator,Dokter']], function () {
    //Chatpayment
    Route::post('/chatpayment/delete-multiple-chatpayment', [ChatPaymentController::class, 'deleteMultiple'])->name('delete-multiple-chatpayment');
    Route::get('/chatpayment', [ChatPaymentController::class, 'index'])->name('chatpayment.index');
    Route::get('/chatpayment/tambah', [ChatPaymentController::class, 'create'])->name('chatpayment.create');
    Route::post('/chatpayment', [ChatPaymentController::class, 'store'])->name('chatpayment.store');
    Route::post('/chatpayment/bukti/{chatpayment}', [ChatPaymentController::class, 'uploadBukti'])->name('chatpayment.bukti');
    Route::get('/chatpayment/{chatpayment}/edit', [ChatPaymentController::class, 'edit'])->name('chatpayment.edit');
    Route::post('/chatpayment/{chatpayment}', [ChatPaymentController::class, 'update'])->name('chatpayment.update');
    Route::get('/chatpayment/detail/{chatpayment}', [ChatPaymentController::class, 'detail'])->name('chatpayment.detail');
    Route::delete('chatpayment/{chatpayment}', [ChatPaymentController::class, 'destroy'])->name('chatpayment.destroy');
    Route::get('/chatpayment/printPDF', [ChatPaymentController::class, 'printPDF'])->name('chatpayment.printPDF');
    Route::get('/chatpayment/exportExcel', [ChatPaymentController::class, 'exportExcel'])->name('chatpayment.exportExcel');

    // Pasien
    Route::post('/pasien/getKabupaten', [PasienController::class, 'getKabupaten'])->name('pasien.get-kabupaten');
    Route::post('/pasien/getKecamatan', [PasienController::class, 'getKecamatan'])->name('pasien.get-kecamatan');
    Route::post('/pasien/getDesa', [PasienController::class, 'getDesa'])->name('pasien.get-desa');
    Route::post('/pasien/delete-multiple-pasien', [PasienController::class, 'deleteMultiple'])->name('delete-multiple-pasien');
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/tambah', [PasienController::class, 'create'])->name('pasien.create');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    Route::get('/pasien/{pasien}/edit', [PasienController::class, 'edit'])->name('pasien.edit');
    Route::post('/pasien/{pasien}', [PasienController::class, 'update'])->name('pasien.update');
    Route::post('/pasien/detail/{pasien}', [PasienController::class, 'detail'])->name('pasien.detail');
    Route::delete('pasien/{pasien}', [PasienController::class, 'destroy'])->name('pasien.destroy');
    Route::get('/pasien/printPDF', [PasienController::class, 'printPDF'])->name('pasien.printPDF');
    Route::get('/pasien/exportExcel', [PasienController::class, 'exportExcel'])->name('pasien.exportExcel');
});

Route::group(['middleware' => ['auth', 'user-access:Administrator,Dokter']], function () {
    //Rekam Medis
    Route::post('/rekam-medis/delete-multiple-rekam-medis', [RekamMedisController::class, 'deleteMultiple'])->name('delete-multiple-rekam-medis');
    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/tambah', [RekamMedisController::class, 'create'])->name('rekam-medis.create');
    Route::post('/rekam-medis', [RekamMedisController::class, 'store'])->name('rekam-medis.store');
    Route::get('/rekam-medis/{id}/edit', [RekamMedisController::class, 'edit'])->name('rekam-medis.edit');
    Route::post('/rekam-medis/{id}', [RekamMedisController::class, 'update'])->name('rekam-medis.update');
    Route::post('/rekam-medis/detail/{id}', [RekamMedisController::class, 'detail'])->name('rekam-medis.detail');
    Route::delete('/rekam-medis/{id}', [RekamMedisController::class, 'destroy'])->name('rekam-medis.destroy');
    Route::get('/rekam-medis/printPDF', [RekamMedisController::class, 'printPDF'])->name('rekam-medis.printPDF');
    Route::get('/rekam-medis/exportExcel', [RekamMedisController::class, 'exportExcel'])->name('rekam-medis.exportExcel');
});

Route::group(['middleware' => ['auth', 'user-access:Dokter']], function () {
    //Rekam Medis
    Route::post('/rekam-medis/delete-multiple-rekam-medis', [RekamMedisController::class, 'deleteMultiple'])->name('delete-multiple-rekam-medis');
    Route::get('/rekam-medis/tambah', [RekamMedisController::class, 'create'])->name('rekam-medis.create');
    Route::post('/rekam-medis', [RekamMedisController::class, 'store'])->name('rekam-medis.store');
    Route::get('/rekam-medis/{id}/edit', [RekamMedisController::class, 'edit'])->name('rekam-medis.edit');
    Route::post('/rekam-medis/{id}', [RekamMedisController::class, 'update'])->name('rekam-medis.update');
    Route::delete('/rekam-medis/{id}', [RekamMedisController::class, 'destroy'])->name('rekam-medis.destroy');
});


Route::middleware(['auth', 'user-access:Administrator'])->group(function () {
    // Dokter
    Route::post('/dokter/getKabupaten', [DokterController::class, 'getKabupaten'])->name('dokter.get-kabupaten');
    Route::post('/dokter/getKecamatan', [DokterController::class, 'getKecamatan'])->name('dokter.get-kecamatan');
    Route::post('/dokter/getDesa', [DokterController::class, 'getDesa'])->name('dokter.get-desa');
    Route::post('/dokter/delete-multiple-dokter', [DokterController::class, 'deleteMultiple'])->name('delete-multiple-dokter');
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('/dokter/tambah', [DokterController::class, 'create'])->name('dokter.create');
    Route::post('/dokter', [DokterController::class, 'store'])->name('dokter.store');
    Route::get('/dokter/{dokter}/edit', [DokterController::class, 'edit'])->name('dokter.edit');
    Route::post('/dokter/{dokter}', [DokterController::class, 'update'])->name('dokter.update');
    Route::post('/dokter/detail/{dokter}', [DokterController::class, 'detail'])->name('dokter.detail');
    Route::delete('dokter/{dokter}', [DokterController::class, 'destroy'])->name('dokter.destroy');
    Route::get('/dokter/printPDF', [DokterController::class, 'printPDF'])->name('dokter.printPDF');
    Route::get('/dokter/exportExcel', [DokterController::class, 'exportExcel'])->name('dokter.exportExcel');

    //Perawat
    Route::post('/perawat/getKabupaten', [PerawatController::class, 'getKabupaten'])->name('perawat.get-kabupaten');
    Route::post('/perawat/getKecamatan', [PerawatController::class, 'getKecamatan'])->name('perawat.get-kecamatan');
    Route::post('/perawat/getDesa', [PerawatController::class, 'getDesa'])->name('perawat.get-desa');
    Route::post('/perawat/delete-multiple-perawat', [PerawatController::class, 'deleteMultiple'])->name('delete-multiple-perawat');
    Route::get('/perawat', [PerawatController::class, 'index'])->name('perawat.index');
    Route::get('/perawat/tambah', [PerawatController::class, 'create'])->name('perawat.create');
    Route::post('/perawat', [PerawatController::class, 'store'])->name('perawat.store');
    Route::get('/perawat/{perawat}/edit', [PerawatController::class, 'edit'])->name('perawat.edit');
    Route::post('/perawat/{perawat}', [PerawatController::class, 'update'])->name('perawat.update');
    Route::post('/perawat/detail/{perawat}', [PerawatController::class, 'detail'])->name('perawat.detail');
    Route::delete('perawat/{perawat}', [PerawatController::class, 'destroy'])->name('perawat.destroy');
    Route::get('/perawat/printPDF', [PerawatController::class, 'printPDF'])->name('perawat.printPDF');
    Route::get('/perawat/exportExcel', [PerawatController::class, 'exportExcel'])->name('perawat.exportExcel');

    // User
    Route::post('/pengguna/delete-multiple-user', [UserController::class, 'deleteMultiple'])->name('delete-multiple-user');
    Route::get('/pengguna', [UserController::class, 'index'])->name('user.index');
    Route::get('/pengguna/tambah', [UserController::class, 'create'])->name('user.create');
    Route::post('/pengguna', [UserController::class, 'store'])->name('user.store');
    Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/pengguna/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('pengguna/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/pengguna/printPDF', [UserController::class, 'printPDF'])->name('pengguna.printPDF');
    Route::get('/pengguna/exportExcel', [UserController::class, 'exportExcel'])->name('pengguna.exportExcel');

    //Layanan
    Route::post('/layanan/delete-multiple-layanan', [LayananController::class, 'deleteMultiple'])->name('delete-multiple-layanan');
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
    Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
    Route::get('/layanan/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
    Route::post('/layanan/detail/{layanan}', [LayananController::class, 'detail'])->name('layanan.detail');
    Route::delete('layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    Route::get('/layanan/printPDF', [LayananController::class, 'printPDF'])->name('layanan.printPDF');
    Route::get('/layanan/exportExcel', [LayananController::class, 'exportExcel'])->name('layanan.exportExcel');

    //Bayar
    Route::post('/bayar/delete-multiple-bayar', [BayarController::class, 'deleteMultiple'])->name('delete-multiple-bayar');
    Route::get('/bayar', [BayarController::class, 'index'])->name('bayar.index');
    Route::post('/bayar', [BayarController::class, 'store'])->name('bayar.store');
    Route::get('/bayar/{bayar}/edit', [BayarController::class, 'edit'])->name('bayar.edit');
    Route::delete('bayar/{bayar}', [BayarController::class, 'destroy'])->name('bayar.destroy');
    Route::get('/bayar/printPDF', [BayarController::class, 'printPDF'])->name('bayar.printPDF');
    Route::get('/bayar/exportExcel', [BayarController::class, 'exportExcel'])->name('bayar.exportExcel');

    //Kategori
    Route::post('/kategori/delete-multiple-kategori', [KategoriController::class, 'deleteMultiple'])->name('delete-multiple-kategori');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/kategori/printPDF', [KategoriController::class, 'printPDF'])->name('kategori.printPDF');
    Route::get('/kategori/exportExcel', [KategoriController::class, 'exportExcel'])->name('kategori.exportExcel');

    //Poli
    Route::post('/poli/delete-multiple-poli', [PoliController::class, 'deleteMultiple'])->name('delete-multiple-poli');
    Route::get('/poli', [PoliController::class, 'index'])->name('poli.index');
    Route::post('/poli', [PoliController::class, 'store'])->name('poli.store');
    Route::get('/poli/{poli}/edit', [PoliController::class, 'edit'])->name('poli.edit');
    Route::delete('poli/{poli}', [PoliController::class, 'destroy'])->name('poli.destroy');
    Route::get('/poli/printPDF', [PoliController::class, 'printPDF'])->name('poli.printPDF');
    Route::get('/poli/exportExcel', [PoliController::class, 'exportExcel'])->name('poli.exportExcel');

    //Jabatan
    Route::post('/jabatan/delete-multiple-jabatan', [JabatanController::class, 'deleteMultiple'])->name('delete-multiple-jabatan');
    Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('/jabatan/{jabatan}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::delete('jabatan/{jabatan}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
    Route::get('/jabatan/printPDF', [JabatanController::class, 'printPDF'])->name('jabatan.printPDF');
    Route::get('/jabatan/exportExcel', [JabatanController::class, 'exportExcel'])->name('jabatan.exportExcel');

    //Homecare
    Route::post('/homecare/delete-multiple-homecare', [HomecareController::class, 'deleteMultiple'])->name('delete-multiple-homecare');
    Route::get('/homecare', [HomecareController::class, 'index'])->name('homecare.index');
    Route::get('/homecare/tambah', [HomecareController::class, 'create'])->name('homecare.create');
    Route::post('/homecare', [HomecareController::class, 'store'])->name('homecare.store');
    Route::get('/homecare/{homecare}/edit', [HomecareController::class, 'edit'])->name('homecare.edit');
    Route::post('/homecare/{homecare}', [HomecareController::class, 'update'])->name('homecare.update');
    Route::post('/homecare/detail/{homecare}', [HomecareController::class, 'detail'])->name('homecare.detail');
    Route::delete('/homecare/{homecare}', [HomecareController::class, 'destroy'])->name('homecare.destroy');
    Route::get('/homecare/printPDF', [HomecareController::class, 'printPDF'])->name('homecare.printPDF');
    Route::get('/homecare/exportExcel', [HomecareController::class, 'exportExcel'])->name('homecare.exportExcel');

    //Fisioterapi
    Route::post('/fisioterapi/delete-multiple-fisioterapi', [BackendFisioterapiController::class, 'deleteMultiple'])->name('delete-multiple-fisioterapi');
    Route::get('/fisioterapi', [BackendFisioterapiController::class, 'index'])->name('fisioterapi.index');
    Route::post('/fisioterapi', [BackendFisioterapiController::class, 'store'])->name('fisioterapi.store');
    Route::get('/fisioterapi/{fisioterapi}/edit', [BackendFisioterapiController::class, 'edit'])->name('fisioterapi.edit');
    Route::post('/fisioterapi/detail/{fisioterapi}', [BackendFisioterapiController::class, 'detail'])->name('fisioterapi.detail');
    Route::delete('fisioterapi/{fisioterapi}', [BackendFisioterapiController::class, 'destroy'])->name('fisioterapi.destroy');
    Route::get('/fisioterapi/printPDF', [BackendFisioterapiController::class, 'printPDF'])->name('fisioterapi.printPDF');
    Route::get('/fisioterapi/exportExcel', [BackendFisioterapiController::class, 'exportExcel'])->name('fisioterapi.exportExcel');

    //Laporan Fisioterapi
    Route::post('/laporan-fisioterapi/getKabupaten', [LaporanTransaksiFisioeterapiController::class, 'getKabupaten'])->name('laporan-fisioterapi.get-kabupaten');
    Route::post('/laporan-fisioterapi/getKecamatan', [LaporanTransaksiFisioeterapiController::class, 'getKecamatan'])->name('laporan-fisioterapi.get-kecamatan');
    Route::post('/laporan-fisioterapi/getDesa', [LaporanTransaksiFisioeterapiController::class, 'getDesa'])->name('laporan-fisioterapi.get-desa');
    Route::get('/laporan-fisioterapi/waktu', [LaporanTransaksiFisioeterapiController::class, 'waktu'])->name('laporan-fisioterapi.waktu');
    Route::get('/laporan-fisioterapi/wilayah', [LaporanTransaksiFisioeterapiController::class, 'wilayah'])->name('laporan-fisioterapi.wilayah');
    Route::get('/laporan-fisioterapi/printPDF', [LaporanTransaksiFisioeterapiController::class, 'printPDF'])->name('laporan-fisioterapi.printPDF');
    Route::post('/laporan-fisioterapi/exportExcel', [LaporanTransaksiFisioeterapiController::class, 'exportExcel'])->name('laporan-fisioterapi.exportExcel');
    Route::get('/laporan-fisioterapi/printPDFWilayah', [LaporanTransaksiFisioeterapiController::class, 'printPDFWilayah'])->name('laporan-fisioterapi.printPDFWilayah');
    Route::post('/laporan-fisioterapi/exportExcelWilayah', [LaporanTransaksiFisioeterapiController::class, 'exportExcelWilayah'])->name('laporan-fisioterapi.exportExcelWilayah');

    //Laporan Homecare
    Route::post('/laporan-homecare/getKabupaten', [LaporanTransaksiHomecareController::class, 'getKabupaten'])->name('laporan-homecare.get-kabupaten');
    Route::post('/laporan-homecare/getKecamatan', [LaporanTransaksiHomecareController::class, 'getKecamatan'])->name('laporan-homecare.get-kecamatan');
    Route::post('/laporan-homecare/getDesa', [LaporanTransaksiHomecareController::class, 'getDesa'])->name('laporan-homecare.get-desa');
    Route::get('/laporan-homecare/waktu', [LaporanTransaksiHomecareController::class, 'waktu'])->name('laporan-homecare.waktu');
    Route::get('/laporan-homecare/wilayah', [LaporanTransaksiHomecareController::class, 'wilayah'])->name('laporan-homecare.wilayah');
    Route::get('/laporan-homecare/printPDF', [LaporanTransaksiHomecareController::class, 'printPDF'])->name('laporan-homecare.printPDF');
    Route::post('/laporan-homecare/exportExcel', [LaporanTransaksiHomecareController::class, 'exportExcel'])->name('laporan-homecare.exportExcel');
    Route::get('/laporan-homecare/printPDFWilayah', [LaporanTransaksiHomecareController::class, 'printPDFWilayah'])->name('laporan-homecare.printPDFWilayah');
    Route::post('/laporan-homecare/exportExcelWilayah', [LaporanTransaksiHomecareController::class, 'exportExcelWilayah'])->name('laporan-homecare.exportExcelWilayah');

    //Laporan Homecare
    Route::post('/laporan-paket-homecare/getKabupaten', [LaporanTransaksiPaketHomecareController::class, 'getKabupaten'])->name('laporan-paket-homecare.get-kabupaten');
    Route::post('/laporan-paket-homecare/getKecamatan', [LaporanTransaksiPaketHomecareController::class, 'getKecamatan'])->name('laporan-paket-homecare.get-kecamatan');
    Route::post('/laporan-paket-homecare/getDesa', [LaporanTransaksiPaketHomecareController::class, 'getDesa'])->name('laporan-paket-homecare.get-desa');
    Route::get('/laporan-paket-homecare/waktu', [LaporanTransaksiPaketHomecareController::class, 'waktu'])->name('laporan-paket-homecare.waktu');
    Route::get('/laporan-paket-homecare/wilayah', [LaporanTransaksiPaketHomecareController::class, 'wilayah'])->name('laporan-paket-homecare.wilayah');
    Route::get('/laporan-paket-homecare/printPDF', [LaporanTransaksiPaketHomecareController::class, 'printPDF'])->name('laporan-paket-homecare.printPDF');
    Route::post('/laporan-paket-homecare/exportExcel', [LaporanTransaksiPaketHomecareController::class, 'exportExcel'])->name('laporan-paket-homecare.exportExcel');
    Route::get('/laporan-paket-homecare/printPDFWilayah', [LaporanTransaksiPaketHomecareController::class, 'printPDFWilayah'])->name('laporan-paket-homecare.printPDFWilayah');
    Route::post('/laporan-paket-homecare/exportExcelWilayah', [LaporanTransaksiPaketHomecareController::class, 'exportExcelWilayah'])->name('laporan-paket-homecare.exportExcelWilayah');
});
