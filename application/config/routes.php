<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes (three in CI3):
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| CI3:
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'users/login';
//$route['404_override'] = '';

// Authentication
Route::any(LOGINDS_URL, 'users/loginds', array('as' => 'loginds'));
Route::any(LOGIN_URL, 'users/login', array('as' => 'login'));
Route::any(REGISTER_URL, 'users/register', array('as' => 'register'));
Route::block('users/login');
Route::block('users/register');
Route::any('api2', 'api');
Route::any('logout', 'users/logout');
Route::any('forgot_password', 'users/forgot_password');
Route::any('reset_password/(:any)/(:any)', 'users/reset_password/$1/$2');

Route::any('rekap/bup-usia','rekap/bup_usia');
Route::any('rekap/golongan-usia','rekap/golongan_usia');
Route::any('rekap/gender-usia','rekap/jenis_kelamin_per_usia');
Route::any('rekap/pendidikan-usia','rekap/pendidikan_usia');
Route::any('rekap/golongan-gender','rekap/golongan_gender');
Route::any('rekap/golongan-pendidikan','rekap/golongan_pendidikan');
Route::any('rekap/pendidikan-gender','rekap/pendidikan_gender');
Route::any('rekap/agama-gender','rekap/agama_gender');
Route::any('rekap/stats-pegawai-jabatan','rekap/stats_jabatan');

// mutasi
Route::any('kpo/kpo-instansi','kpo/kpo_instansi');
Route::any('kpo/kpo-instansi/(:any)','kpo/kpo_instansi/$1');
Route::any('kpo/kpo-instansi/(:any)/(:any)','kpo/kpo_instansi/$1/$2');
Route::any('kpo/kpo-satker','kpo/kpo_satker');
Route::any('kpo/kpo-satker/(:any)','kpo/kpo_satker/$1');
Route::any('kpo/kpo-satker/(:any)/(:any)','kpo/kpo_satker/$1/$2');
Route::any('kpo/kpo-sekretariat','kpo/kpo_sekretariat');
Route::any('kpo/kpo-sekretariat/(:any)','kpo/kpo_sekretariat/$1');
Route::any('kpo/kpo-sekretariat/(:any)/(:any)','kpo/kpo_sekretariat/$1/$2');

Route::any('ppo/ppo-instansi','ppo/ppo_instansi');
Route::any('ppo/ppo-instansi/(:any)','ppo/ppo_instansi/$1');
Route::any('ppo/ppo-instansi/(:any)/(:any)','ppo/ppo_instansi/$1/$2');
Route::any('ppo/ppo-satker','ppo/ppo_satker');
Route::any('ppo/ppo-satker/(:any)','ppo/ppo_satker/$1');
Route::any('ppo/ppo-satker/(:any)/(:any)','ppo/ppo_satker/$1/$2');
Route::any('ppo/ppo-deputi','ppo/ppo_deputi');
Route::any('ppo/ppo-deputi/(:any)','ppo/ppo_deputi/$1');
Route::any('ppo/ppo-deputi/(:any)/(:any)','ppo/ppo_deputi/$1/$2');

Route::any('kgb/kgb-satker','kgb/kgb_satker');
Route::any('kgb/kgb-satker/(:any)','kgb/kgb_satker/$1');
Route::any('kgb/kgb-satker/(:any)/(:any)','kgb/kgb_satker/$1/$2');

Route::any('kgb/pensiun-satker','kgb/pensiun_satker');
Route::any('kgb/pensiun-satker/(:any)','kgb/pensiun_satker/$1');
Route::any('kgb/pensiun-satker/(:any)/(:any)','kgb/pensiun_satker/$1/$2');
// end mutasi



// Activation
Route::any('activate', 'users/activate');
Route::any('activate/(:any)', 'users/activate/$1');
Route::any('resend_activation', 'users/resend_activation');

// Contexts
Route::prefix(SITE_AREA, function(){
    Route::context('content', array('home' => SITE_AREA .'/content/index'));
    Route::context('reports', array('home' => SITE_AREA .'/reports/index'));
    Route::context('lokasi', array('home' => SITE_AREA .'/reports/index'));
    Route::context('developer');
    Route::context('settings');
    Route::context('layanan');
    Route::context('sk');
    Route::context('izin');
    Route::context('arsip');
    Route::context('kepegawaian', array('masters' => SITE_AREA .'/masters/index'));
    Route::context('masters', array('masters' => SITE_AREA .'/masters/index'));
    Route::context('tte');
});

$route = Route::map($route);

if (defined(CI_VERSION) && substr(CI_VERSION, 0, 1) != '2') {
    $route['translate_uri_dashes'] = false;
}
