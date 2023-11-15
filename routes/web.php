<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;



//agregamos los siguientes controladores
use App\Http\Controllers\Obras\ObrasController;
use App\Http\Controllers\Obras\ObrasCertifController;

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\Barrio\BarrioController;
use App\Http\Controllers\Barrio\Fc_concosxbarrioController;
use App\Http\Controllers\Barrio\barrio_terrenoController;
use App\Http\Controllers\Barrio\BarrioXOrgController;
use App\Http\Controllers\Barrio\fc_conxbarrioController;
use App\Http\Controllers\ob_licitacionController;


use App\Http\Controllers\CategorialaboralController;
use App\Http\Controllers\Obras84Controller;

//--Fernando
use App\Http\Controllers\JorgeController;
//--

use App\Http\Controllers\Terrenos\TerrenosController;

//sol
use App\Http\Controllers\pAlmacenController;
use App\Http\Controllers\sectorController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\Coordinacion\Digesto\DigestoController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\App\AppNotificacionesController;

//--Fer Jose
use App\Http\Controllers\Generales\ProtocoloController;
//--

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
// Route::get('/ipusuario', function(Request $request){
//     echo request()->ip();
// });

Route::get('/sintaxis',function()
    {return view('zsintaxis.LaravelCollective');})->name('sintaxis.index');

Route::get('/registerEmpIprodha',function(){
        // return view('auth.registerEmp');
        return view('auth.register');
})->name('register.emp');

Route::get('/loginIprodha',function(){
    // return view('auth.loginEmp');
    return view('auth.login');
})->name('login.iprodha');

// Jorge
//Route::get('/terrenos', [TerrenosController::class, 'index']);
Route::group(['middleware' => ['auth']], function(){
    Route::resource('terrenos', 'App\Http\Controllers\Terrenos\TerrenosController');
});

Auth::routes();

//Lisandro
Route::group(['middleware' => ['auth','role_or_permission:ADMIN|VER-CATEGORIALABORAL']],function() {
    //Route::get('/lisandro', [EjemplolisandroController::class, 'index']);
    Route::get('/categorialaboral/pdf', [CategorialaboralController::class, 'pdf'])->name('categorialaboral.pdf');
    Route::resource('categorialaboral', CategorialaboralController::class);
});


//Alumno
Route::group(['middleware' => ['auth','role_or_permission:ADMIN|VER-ALUMNOS']],function() {
    Route::resource('alumnos', AlumnoController::class);
});

//Ob_Concepto --Quqiue
use App\Http\Controllers\ge_Obras\di_Certificaciones\Conceptos\Ct_Ob_ConceptoController;
// Enrutamiento
Route::group(['middleware' => ['auth','role_or_permission:ADMIN|VER-OB_CONCEPTO']],function() {
    Route::get('/editar_con/{id_concepto}',[Ct_Ob_ConceptoController::class, 'edit'])->name('ob_concepto.editar');
    Route::resource('r_ob_concepto', Ct_Ob_ConceptoController::class);
});

//Sol
//Ruta p_Almacen
Route::group(['middleware' => ['auth', 'role_or_permission:ADMIN|VER-ALMACENES']], function () {
    Route::get('p_almacen/{p_almacen}/imagen', [pAlmacenController::class, 'imagen'])->name('almacen.imagen');
    Route::get('p_almacen/{p_almacen}/asignar', [pAlmacenController::class, 'asignar'])->name('almacen.asignar');
    Route::put('p_almacen/{p_almacen}/imagen', [pAlmacenController::class, 'guardarImagen'])->name('almacen.guardarImagen');
    Route::put('p_almacen/{p_almacen}/sectores', [pAlmacenController::class, 'asignarSector'])->name('almacen.asignarSector');
    Route::get('p_almacen/crear', [pAlmacenController::class, 'create'])->name('almacen.crear');
    Route::get('p_almacen/index', [pAlmacenController::class, 'index'])->name('almacen.index');
    Route::get('p_almacen/{p_almacen}/editar', [pAlmacenController::class, 'edit'])->name('almacen.editar');
    Route::put('p_almacen/{p_almacen}',[pAlmacenController::class, 'update'])->name('almacen.update');
    Route::delete('p_almacen/eliminar/{id_almacen}', [pAlmacenController::class, 'destroy'])->name('almacen.eliminar');
    
    Route::resource('p_almacen', pAlmacenController::class); 
});
//Ruta sector
Route::group(['middleware'=> ['auth', 'role_or_permission:ADMIN|VER-SECTORES']], function(){
    Route::get('sector/crear', [sectorController::class, 'create'])->name('sector.crear');
    Route::get('sector/index', [sectorController::class, 'index'])->name('sector.index');
    Route::get('sector/{sector}/editar', [sectorController::class, 'edit'])->name('sector.editar');
    Route::put('sector/{sector}',[sectorController::class, 'update'])->name('sector.update');
    Route::delete('sector/eliminar/{id_sector}', [sectorController::class, 'destroy'])->name('sector.eliminar');
    Route::resource('sector', sectorController::class);
});


Route::group(['middleware' => ['auth','role_or_permission:ADMIN|VER-OBRAS']], function () {
    Route::get('/obras', [ObrasController::class, 'index'])->name('index');
    Route::get('/obras/{id_obr}/editar', [ObrasController::class, 'edit'])->name('obras.editar');
    Route::put('/obras/{id_obr}', [ObrasController::class, 'update']);
    Route::delete('/obras/eliminar/{id_obr}', [ObrasController::class, 'destroy'])->name('obras.eliminar');
    //Route::get('/obras', [ObrasCertifController::class, 'index'])->name('index');
    Route::get('/obras/{id_obr}/detalle', [ObrasCertifController::class, 'detalle'])->name('obrasCertif.detalle');
    Route::get('/obras/crear', [ObrasController::class, 'create'])->name('obras.crear');
    Route::post('/obras', [ObrasController::class, 'store']);
    Route::resource('obras', ObrasController::class);
    //Route::get('/obras/{id_obr?}', [ObrasController::class, 'update'])->name('update');;
    //->middleware('auth');  //solo para asegurar el login. Aleternativo

});
Route::group(['middleware'=>['auth','role_or_permission:ADMIN|VER-BARRIO']],function(){
    Route::get('/barrio',[BarrioController::class,'index'])->name('index');
    Route::get('/barrio/{barrio}/editar',[BarrioController::class,'edit'])->name('barrio.editar');
    Route::post('/barrio/{barrio}',[BarrioController::class,'update']);
    Route::delete('/barrio/eliminar/{barrio}',[BarrioController::class,'destroy'])->name('barrio.eliminar');    
    Route::get('/barrio/buscar',[BarrioController::class,'buscar'])->name('barrio.buscar');
    Route::get('/barrio/crear',[BarrioController::class,'create'])->name('barrio.crear');
    Route::post('/barrio',[BarrioController::class,'store']);
    Route::get('/barrio/costos/editar',[BarrioController::class,'edit'])->name('barrio.costos.editar');
    Route::resource('barrio',BarrioController::class);
});

Route::group(['middleware'=>['auth','role_or_permission:ADMIN|VER-BARRIO']],function(){
    Route::get('/barrio/{barrio}/vercostos',[Fc_concosxbarrioController::class,'edit'])->name('barrio.verCostos');        
});

//ob_licitacion
Route::group(['middleware'=>['auth','role_or_permission:ADMIN|VER-OB_LIC']],function(){
    Route::get('/ob_lic',[ob_licitacionController::class,'index'])->name('ob_lic.index');    
    Route::get('/ob_lic/crear',[ob_licitacionController::class,'create'])->name('ob_lic.crear');    
    Route::post('/ob_lic',[ob_licitacionController::class,'store'])->name('ob_lic.store');
    Route::post('/ob_lic/subir1/{path?}',[ob_licitacionController::class,'subir1'])->name('ob_lic.subir1');
    Route::get('/ob_lic/subir/{path?}',[ob_licitacionController::class,'subir'])->name('ob_lic.subir');      
});

Route::delete('/terrenoSup/eliminar/{barrio}/{id}',[barrio_terrenoController::class,'destroy'])->name('terrenoSup.eliminar');    
Route::get('/barrio/{barrio}/terrenoSup',[barrio_terrenoController::class,'index'])->name('barrio.terrenoSup');
Route::post('/terrenoSup',[barrio_terrenoController::class,'store']);
Route::resource('terrenoSup',barrio_terrenoController::class);

Route::delete('/dormXTerr/eliminar/{barrio}/{dor}/{terr}',[BarrioXOrgController::class,'destroy'])->name('dormXTerr.eliminar');
Route::get('/barrio/{barrio}/dormXTerr',[BarrioXOrgController::class,'index'])->name('barrio.dormXTerr');
Route::resource('dormXTerr',BarrioXOrgController::class);

Route::get('/obras84',function()
        {//return view('welcome');
       return view('Obras84.VistaObras84');
    });


//Quique
/*
Route::group(['middleware' => ['auth','role_or_permission:Admin']], function () {

    Route::resource('estadocivil', EstadocivilController::class);
	Route::get('/estadocivil', [App\Http\Controllers\EstadocivilController::class, 'index'])->name('index');
    
    
});*/

Route::get('/estadocivil', [App\Http\Controllers\EstadocivilController::class, 'index'])->name('index');

Route::group(['middleware' => ['auth']], function(){
    Route::get('/archivo', [ArchivoController::class, 'consultar'])->name('archivo.consultar');
    Route::get('/archivo/filtro', [ArchivoController::class, 'consultar'])->name('archivo.buscar');
    Route::get('/tipoarchivo/{id_tipoarchivo}/subtipos', [ArchivoController::class, 'subtipos'])->name('archivo.subtipos');
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('{usuario}/notificaciones', [NotificacionController::class, 'verTodo'])->name('notif.verTodo');
    Route::get('{idnotificacion}/ver', [NotificacionController::class, 'visto'])->name('notif.ver');
    });//agregar noti

Route::group(['middleware' => ['auth']], function () {
    Route::get('archivos', [ArchivoController::class, 'consultar'])->name('archivos.consultar');  
    Route::get('archivo/boletin', [ArchivoController::class, 'consultarBoletin'])->name('archivos.consultarBoletin');    
    Route::get('archivo/check', [ArchivoController::class, 'check'])->name('archivos.check');
    Route::get('archivo/selects', [ArchivoController::class, 'getSelects'])->name('archivos.selects');
    Route::get('archivo/campos', [ArchivoController::class, 'getCampos'])->name('archivos.campos');
    Route::get('archivo/derivados', [ArchivoController::class, 'derivados'])->name('archivos.derivados');
    Route::get('archivo/tags', [ArchivoController::class, 'tags'])->name('archivos.tags');
    Route::get('archivo/tag', [ArchivoController::class, 'obtenerTagFormato'])->name('archivos.tag');
    Route::get('archivo/complejos', [ArchivoController::class, 'complejos'])->name('archivos.complejos');
    Route::get('archivo/busquedadirigida', [ArchivoController::class, 'busquedaDirigida'])->name('archivos.busquedaDirigida');
    Route::get('archivo/getArchivos', [ArchivoController::class, 'getArchivos'])->name('archivos.getArchivos');
    Route::get('archivo/pdf', [ArchivoController::class, 'getpdf'])->name('archivos.getpdf');
    Route::get('archivo/buscar', [ArchivoController::class, 'buscar'])->name('archivos.buscar');
    Route::get('archivo/buscarID', [ArchivoController::class, 'buscarPorId'])->name('archivos.buscarID');
});

Route::group(['middleware' => ['auth','role_or_permission:ADMIN|RRHH']], function () {
    Route::get('archivo/rrhh', [ArchivoController::class, 'buscarArchivosRRHH'])->name('archivos.rrhh');
    Route::get('archivo/dni', [ArchivoController::class, 'buscarDNI'])->name('archivos.dni');
});

Route::group(['middleware' => ['auth','role_or_permission:ADMIN|CREAR-ARCHIVOS|DIGITALIZADOR']], function () {
    Route::get('archivo/digitalizar', [ArchivoController::class, 'digitalizar'])->name('archivos.digitalizar');
    Route::post('archivo/crear', [ArchivoController::class, 'crear'])->name('archivos.crear');
    Route::put('archivo/modificar', [ArchivoController::class, 'modificar'])->name('archivos.modificar');
});

Route::group(['middleware' => ['auth','role_or_permission:ADMIN|DIGESTO']], function () {
    Route::get('digesto', [DigestoController::class, 'index'])->name('digesto.index');
    Route::get('digesto/buscador', [DigestoController::class, 'buscarArchivo'])->name('digesto.buscador'); //borrar esto
    Route::get('digesto/buscar', [DigestoController::class, 'buscarArchivo'])->name('digesto.buscar');
    Route::post('digesto/guardar', [DigestoController::class, 'guardar'])->name('digesto.guardar');
    Route::get('digesto/areas', [DigestoController::class, 'areas'])->name('digesto.areas');
    Route::delete('digesto/sacarArea', [DigestoController::class, 'remove_area'])->name('digesto.sacarArea');
    Route::post('digesto/añadirArea', [DigestoController::class, 'add_area'])->name('digesto.añadirArea');    
    Route::get('digesto/relacionados', [DigestoController::class, 'relacionados'])->name('digesto.relacionados');    
    Route::get('digesto/modificaciones', [DigestoController::class, 'modificaciones'])->name('digesto.modificaciones');
    Route::get('digesto/buscador', [DigestoController::class, 'buscador'])->name('digesto.buscador');
    Route::get('digesto/check', [DigestoController::class, 'check'])->name('digesto.check');
    Route::put('digesto/modificar', [DigestoController::class, 'modificar'])->name('digesto.modificar');
});

Route::group(['middleware' => ['auth','role_or_permission:ADMIN']], function () {
    Route::get('notificaciones/boletas', [AppNotificacionesController::class, 'notificacionBoletas'])->name('notificaciones.boletas');
    Route::get('notificaciones/boletas/pendientes', [AppNotificacionesController::class, 'pendientes'])->name('notificaciones.boletasPendientes');
    Route::post('notificaciones/boletas/enviar', [AppNotificacionesController::class, 'enviarBoletas'])->name('notificaciones.boletasEnviar');
});

Route::group(['middleware' => ['auth','role_or_permission:ADMIN']], function () {
    Route::get('generales/protocolo', [ProtocoloController::class, 'protocolo'])->name('generales.protocolo');
});

