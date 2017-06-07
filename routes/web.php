<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('auth/login');
});

Route::resource('reportes/reportesServiciosFinalizados', 'reporteServiciosFinalizadosController');
Route::resource('reportes/reportesAdmin', 'reporteAdminController');
Route::resource('reportes/reportesChia', 'ServActivChiaController');
Route::resource('reportes/recibir', 'pruebaController');
Route::resource('reportes/reportesHoraJuego', 'HorasJuegoController');
Route::resource('reportes/horasJuego', 'mostrarHorasJuegoController');
Route::resource('reportes/reportesMovimientosCliente', 'reportesMovimientoClienteController');
Route::resource('reportes/reportesAjustesNegativos', 'reportesAjustesNegativosController');
Route::resource('reportes/ajustesNegativos', 'ajustesNegativosController');
Route::resource('reportes/reportesTotalServicios', 'reportesTotalServiciosController');
Route::resource('reportes/totalServicios', 'totalServiciosController');
Route::resource('usuario', 'UserController');
Route::resource('reportes/reportesTotalServiciosChia', 'reportesTotalServiciosChiaController');
Route::resource('reportes/reportesListadoMensajeros', 'reportesListadoMensajerosController');
Route::resource('reportes/reportesAppVersiones', 'reportesAppVersionesController');
Route::resource('reportes/trackMensajero','trackMensajeroController');

/* Route::get('reportes/reportesAdmin','reporteAdminController@exportarAdmin'); */
Route::post('reportes/reportesServiciosFinalizados', 'reporteServiciosFinalizadosController@exportarServiciosFinalizados');
Route::post('reportes/reportesAdmin', 'reporteAdminController@exportarAdmin');
Route::post('reportes/reportesChia', 'ServActivChiaController@activacionChia');
Route::post('reportes/reportesHoraJuego', 'HorasJuegoController@reporteTiemposHJ');
Route::post('reportes/reportesMovimientosClientes', 'reportesMovimientoClienteController@exportarMovimientosCliente');
Route::post('reportes/reportesAjustesNegativos', 'reportesAjustesNegativosController@exportarAjustesNegativos');
Route::post('reportes/reportesTotalServicios', 'reportesTotalServiciosController@exportarTotalServicios');
Route::post('reportes/reportesTotalServiciosChia', 'reportesTotalServiciosChiaController@exportarTotalServiciosChia');
Route::post('reportes/reportesListadoMensajeros', 'reportesListadoMensajerosController@exportarListadoMensajeros');
Route::post('reportes/trackMensajero', 'trackMensajeroController@track');

Route::Auth();

Route::get('/home', 'HomeController@index');

Auth::routes();