<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers\Generales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \PDF;



class ProtocoloController extends Controller
{
    public function protocolo()
    {
        $pathResolucion= public_path()."\storage\pdf\\resolucion.pdf";
        $pathFormulario= public_path()."\storage\pdf\\formulario_denuncia.pdf";

        return view('Generales.protocolo', compact('pathResolucion', 'pathFormulario'));
    }
}

/*
<?php

namespace App\Http\Controllers\Coordinacion\Informatica\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//agregamos
use App\Models\Iprodha\Tic_Tarea;
use App\Models\Iprodha\Tic_Catproblema;
use App\Models\Iprodha\Tic_Catproblemasub;
use App\Models\Iprodha\Tic_Estado;
use App\Models\Iprodha\Tic_Estados_x_Tarea;
use App\Models\Iprodha\Tic_Imagen_x_Tarea;
use App\Models\Iprodha\Tic_Solucionador;
use App\Models\Iprodha\Tic_Reasignaciontarea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use \PDF;


class TicketController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:VER-TICKET', ['only' => ['index']]);
        $this->middleware('permission:CREAR-TICKET', ['only' => ['create','store','cancelticket', 'reAsignarTicket', 'validarTicket']]);
        $this->middleware('permission:EDITAR-TICKET', ['only' => ['edit','update']]);
        $this->middleware('permission:BORRAR-TICKET', ['only' => ['destroy']]);
        $this->middleware('permission:ATENDER-TICKET', ['only' => ['vertickets', 'atencionticket', 'completarticket']]);
        $this->middleware('permission:ASIGNAR-TICKET', ['only' => ['asignadores','asigna', 'cambiarCategTicket', 'editarCategTicket']]);
    }

    public function index(Request $request)
    {        
        $name = $request->query->get('name');
        if (!isset($name)) {    
            //Con paginación
            $Tickets = Tic_Tarea::where('idusuario', '=', Auth::user()->id)->orderBy('idtarea', 'desc')->simplePaginate(10);
            //al usar esta paginacion, recordar poner en el el index.blade.php este codigo  {!! $roles->links() !!}
        } else {
            $Tickets = Tic_Tarea::whereRaw('UPPER(idtarea) LIKE ?', ['%' . strtoupper($name) . '%'])->orderBy('idtarea', 'asc')->paginate(10);
        }
        return view('Coordinacion.Informatica.ticket.index',compact('Tickets'));
    }
    
    public function create(Request $request)
    {
        $Categorias = Tic_Catproblema::all();
        return view('Coordinacion.Informatica.ticket.crear',compact('Categorias'));
    }

    public function getClientIP(){       
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
               return  $_SERVER["HTTP_X_FORWARDED_FOR"];  
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) { 
               return $_SERVER["REMOTE_ADDR"]; 
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
               return $_SERVER["HTTP_CLIENT_IP"]; 
        } 
   
        return '';
   }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subcateg' => 'required|string',
            'interno' => 'required|string',
            'descrip' => 'required|string',
            'image' => 'file|image|mimes:jpg,png,jpeg"]',
        ]);

        $mobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        $input = $request->all();

        $modelo = new Tic_Tarea;

        //Nombre
        $modelo->descripciontarea = strtoupper($request->input('descrip'));
        $modelo->usuario = Auth::user()->name;
        $modelo->idusuario = Auth::user()->id;
        $modelo->idcatprobsub = $request->input('subcateg');
        if(!$mobile){
            // $modelo->iporigentarea = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $modelo->iporigentarea = $this->getClientIP();
        }else{
            $modelo->iporigentarea = $_SERVER['REMOTE_ADDR'];
        }
        // $modelo->iporigentarea = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $modelo->interno = $request->input('interno');
        $modelo->prioridad = null;
        $modelo->tiempoestimado = null;
        $modelo->idsolucionador = 0;

        $data = Tic_Tarea::latest('idtarea')->first();
       

        if(is_null($data)){
            $modelo->idtarea = 1;
        }else{
            $modelo->idtarea = $data['idtarea'] + 1;
        }

        $modeloEstado = new Tic_Estados_x_Tarea;
        $modeloEstado->idtarea = $modelo->idtarea;
        $modeloEstado->idestado = 1;
        $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $modeloEstado->observacion = null;
           
        $modelo->save();
        $modeloEstado->save();

        if($request->hasFile('image')){
            $cadenaConvert = str_replace(" ", "_", Auth::user()->name);
            $filename = time().'-'. $cadenaConvert . '.' . $request->file('image')->extension();
            $path = $request->file('image')->storeAs('images/ticket', $filename, 'public_uploads');

            // $path = $request->file('image')->getRealPath();    
            // $logo = file_get_contents($path);
            // $base64 = base64_encode($logo);
    
            $save = new Tic_Imagen_x_Tarea;

            $data = Tic_Imagen_x_Tarea::latest('idimagen')->first();
            if(is_null($data)){
                $save->idimagen = 1;
            }else{
                $save->idimagen = $data['idimagen'] + 1;
            }
            $save->idtarea = $modelo->idtarea;
            $save->ruta = 'storage/upload/' . $path;
            // $save->imgb = $base64;
            // return response()->json($base64);
            $save->save();
        } 
      
        return redirect()->route('ticket.index')->with('mensaje','El ticket '.$modelo->idtarea.' creado con exito.');                                                   
    }

    public function show($id)
    {
        $Ticket = Tic_Tarea::where('idtarea', '=', $id )->first();
        $Image = Tic_Imagen_x_Tarea::where('idtarea', '=', $id )->first();
        return view('Coordinacion.Informatica.ticket.show', compact('Ticket', 'Image'));
    }
   
    public function edit(Request $request, $id)
    {
        $Ticket = Tic_Tarea::findorfail($id);
        $Image = Tic_Imagen_x_Tarea::where('idtarea', '=', $id )->first();
        return view ('Coordinacion.Informatica.ticket.editar',compact('Ticket', 'Image'));                                                    
    }
    
    public function update(Request $request, $id)
    {             
        $this->validate($request, [
            'categ' => 'required|integer|between:1,999',
            'observ' => 'required|string',
        ], [
            'categ.between' => 'Seleccione el Solucionador',
        ]);

        $Ticket = Tic_Tarea::findOrFail($id);

        $modeloEstado = new Tic_Estados_x_Tarea;
        $modeloEstado->idtarea = $Ticket->idtarea;
        $modeloEstado->idestado = 2;
        $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $modeloEstado->observacion = $request->input('observ');
        $modeloEstado->save();

        $Ticket->idsolucionador = $request->input('categ');
        $Ticket->save();
        return redirect()->route('ticket.asigna')->with('mensaje','Se asigno el solucionador con exito.');                   
    }

    public function destroy($id)
    {  
    }
    
    public function cancelticket($id){
        $Ticket = Tic_Tarea::findOrFail($id);

        $modeloEstado = new Tic_Estados_x_Tarea;
        $modeloEstado->idtarea = $Ticket->idtarea;
        $modeloEstado->idestado = 6;
        $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $modeloEstado->observacion = 'Cancelado por el usuario';
        $modeloEstado->save();

        return redirect()->route('ticket.index')->with('mensaje','El ticket '.$Ticket->idtarea.' cancelado con exito.');  
    } 
    
    public function asigna($id)
    {
        $Ticket = Tic_Tarea::where('idtarea', '=', $id )->first();
        $Image = Tic_Imagen_x_Tarea::where('idtarea', '=', $id )->first();
        $idcat = Tic_Catproblemasub::where('idcatprobsub', '=', $Ticket->idcatprobsub)->select('idcatprob')->first();
        $idtipsolu = Tic_Catproblema::where('idcatprob', '=', $idcat->idcatprob)->select('idtipsolucionador')->first();
        $Solucionadores = Tic_Solucionador::where('idtipsolucionador', '=', $idtipsolu->idtipsolucionador)->pluck('nombre', 'idsolucionador')->prepend('Seleccionar', '0')->toArray();
        $Categorias = Tic_catproblema::all();
        $Subcategorias = Tic_Catproblemasub::all(); 
        return view('Coordinacion.Informatica.ticket.agregar', compact('Ticket','Image','Solucionadores','Categorias','Subcategorias'));
    }
    

    public function asignadores(Request $request)
    {
        //Variables
        $name = $request->query->get('name');
        $estado = $request->query->get('estado');
        $categoria = $request->query->get('categ');
        $solu = Tic_Solucionador::where('idusuario', '=', Auth::user()->id)->first();

        //Preguntar a que categoria pertenece el usuario.
        if($request->has('categ')){
            $tipo = $request->query->get('categ');
        }else{
            $tipo = $solu->getTipo->idtipsolucionador;
            $catesolu = Tic_Catproblema::select('idcatprob')->where('idtipsolucionador','=',$tipo)->get();
            $categoria = $catesolu[0]->idcatprob;
        }

        
        // echo($solu->getTipo);
        if (!isset($name)) {
            $estadosTarea = DB::table('iprodha.tic_estados_x_tarea')
                    ->select('idtarea', DB::raw('MAX(idestado) estado_actual'))
                    ->groupBy('idtarea');

            $estadosOrden = Tic_Estado::joinSub($estadosTarea, 'estados_Tarea', function ($join) {
                                        $join->on('iprodha.tic_estado.idestado', '=', 'estados_Tarea.estado_actual');})
                                        ->when($estado != 0, function($query) use ($estado){
                                            $query->where('idestado', $estado);
                                        })
                                        ->orderBy('ordvisualiz');

            $Tickets = Tic_tarea::joinSub($estadosOrden, 'estados_Orden', function ($join) {
                                        $join->on('iprodha.tic_tarea.idtarea', '=', 'estados_Orden.idtarea');})
                                        ->categoria($categoria)->get();
            
        } else {
            $Tickets = Tic_Tarea::whereRaw('UPPER(idtarea) LIKE ?', ['%' . strtoupper($name) . '%'])->orderBy('idtarea', 'asc')->simplePaginate(100);
        }

        $Categorias = Tic_Catproblema::all();

        return view('Coordinacion.Informatica.ticket.asigna',compact('Tickets','Categorias', 'tipo'));
    }

    function vertickets(Request $request)
    {
        $name = $request->query->get('name');
        $Solucionador = Tic_Solucionador::where('idusuario', '=', Auth::user()->id)->first();
        if (!isset($name)) {
            $Tickets = Tic_Tarea::where('idsolucionador', '=', $Solucionador->idsolucionador)->orderBy('idtarea', 'desc')->paginate(10);
        }else{
            $Tickets = Tic_Tarea::where('idsolucionador', '=', $Solucionador->idsolucionador)->whereRaw('UPPER(idtarea) LIKE ?', ['%' . strtoupper($name) . '%'])->orderBy('idtarea', 'asc')->simplePaginate(10);
        }
            // $Tickets = Tic_Tarea::where('idsolucionador', '=', $Solucionador->idsolucionador)->orderBy('idtarea', 'desc')->paginate(10);
            return view('Coordinacion.Informatica.ticket.Atencionturno.index', compact('Tickets'));
     
    }
    
    function atencionticket($id)
    {
        $Ticket = Tic_Tarea::findOrFail($id);
        $Image = Tic_Imagen_x_Tarea::where('idtarea', '=', $id )->first();
        $estado = $Ticket->getEstadoTarea->sortByDesc('idestado')->first()->idestado;
        if ($estado == 2) {
            $modeloEstado = new Tic_Estados_x_Tarea;
            $modeloEstado->idtarea = $Ticket->idtarea;
            $modeloEstado->idestado = 3;
            $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
            $modeloEstado->observacion = 'Esta siendo atendido por '. Auth::user()->name;
            $modeloEstado->save();
        }
        
        return view('Coordinacion.Informatica.ticket.Atencionturno.editar', compact('Ticket', 'Image'));
    }

    public function completarticket(Request $request, $id)
    {
        $Ticket = Tic_Tarea::findOrFail($id);

        $modeloEstado = new Tic_Estados_x_Tarea;
        $modeloEstado->idtarea = $Ticket->idtarea;
        $modeloEstado->idestado = 4;
        $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $modeloEstado->observacion = $request->input('observ');
        $modeloEstado->save();

        return redirect()->route('ticket.atencion')->with('mensaje','El ticket '.$Ticket->idtarea.' completado con exito.');  
    } 

    public function validarTicket(Request $request, $id)
    {
        $Ticket = Tic_Tarea::findOrFail($id);

        $modeloEstado = new Tic_Estados_x_Tarea;
        $modeloEstado->idtarea = $Ticket->idtarea;
        $modeloEstado->idestado = 5;
        $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $modeloEstado->observacion = $request->input('observ');
        $modeloEstado->save();

        return redirect()->route('ticket.index')->with('mensaje','El ticket '.$Ticket->idtarea.' se valido con exito.');
    }

    public function reAsignarTicket(Request $request, $id)
    {
        $Ticket = Tic_Tarea::findOrFail($id);

        $modeloEstado = new Tic_Estados_x_Tarea;
        $modeloEstado->idtarea = $Ticket->idtarea;
        $modeloEstado->idestado = 7;
        $modeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $modeloEstado->observacion = $request->input('observ');
        $modeloEstado->save();

        $nuevoTicket = new Tic_Tarea;

        $data = Tic_Tarea::latest('idtarea')->first();
        if(is_null($data)){
            $nuevoTicket->idtarea = 1;
        }else{
            $nuevoTicket->idtarea = $data['idtarea'] + 1;
        }

        $nuevoTicket->descripciontarea = $Ticket->descripciontarea;
        $nuevoTicket->idsolucionador = 0;
        $nuevoTicket->usuario = $Ticket->usuario;
        $nuevoTicket->idusuario = $Ticket->idusuario;
        $nuevoTicket->idcatprobsub = $Ticket->idcatprobsub;
        $nuevoTicket->iporigentarea = $Ticket->iporigentarea;
        $nuevoTicket->interno = $Ticket->interno;
        $nuevoTicket->prioridad = null;
        $nuevoTicket->tiempoestimado = null;
        $nuevoTicket->save();

        $imagenticket = Tic_Imagen_x_Tarea::where('idtarea', '=', $id)->first();
        $nuevaimagen = new Tic_Imagen_x_Tarea;

        if(!is_null($imagenticket)){
            $imagenactaul = Tic_Imagen_x_Tarea::latest('idimagen')->first();
            if(is_null($imagenactaul)){
                $nuevaimagen->idimagen = 1;
            }else{
                $nuevaimagen->idimagen = $imagenactaul['idimagen'] + 1;
            }
            $nuevaimagen->idtarea = $nuevoTicket->idtarea; 
            $nuevaimagen->ruta = $imagenticket->ruta;
            $nuevaimagen->save();
        }

        $nuevomodeloEstado = new Tic_Estados_x_Tarea;
        $nuevomodeloEstado->idtarea = $nuevoTicket->idtarea;
        $nuevomodeloEstado->idestado = 1;
        $nuevomodeloEstado->fecha = \Carbon\Carbon::today()->toDateString();
        $nuevomodeloEstado->observacion = "Este ticket fue reasignado. " . $request->input('observ');
        $nuevomodeloEstado->save();

        $nuevoReasigTarea = new Tic_Reasignaciontarea;
        $nuevoReasigTarea->idtarea = $nuevoTicket->idtarea;
        $nuevoReasigTarea->idtarea_vieja = $Ticket->idtarea;
        $nuevoReasigTarea->fecha = \Carbon\Carbon::today()->toDateString();
        $nuevoReasigTarea->save();

        return redirect()->route('ticket.index')->with('mensaje','El ticket '.$Ticket->idtarea.' se volvio a abrir con exito.');
    }

    public function editarTicket(Request $request, $id)
    {
        $this->validate($request, [
            'interno' => 'required|string',
            'descrip' => 'required|string',
            'image' => 'file|image|mimes:jpg,png,jpeg"]',
        ]);

        $Ticket = Tic_Tarea::findOrFail($id);
        $Ticket->descripciontarea = $request->input('descrip'); 
        $Ticket->interno = $request->input('interno');
        $Ticket->save();
        if($request->hasFile('image')){
            $imagen = Tic_Imagen_x_Tarea::where("idtarea", "=", $id)->first();
            if(!is_null($imagen)){
                unlink($imagen->ruta);
                Tic_Imagen_x_Tarea::destroy($imagen->idtarea);
            }

            $filename = time().'-'. Auth::user()->name . '.' . $request->file('image')->extension();
            $path = $request->file('image')->storeAs('images/ticket', $filename, 'public_uploads');
    
            $save = new Tic_Imagen_x_Tarea;

            $data = Tic_Imagen_x_Tarea::latest('idimagen')->first();
            if(is_null($data)){
                $save->idimagen = 1;
            }else{
                $save->idimagen = $data['idimagen'] + 1;
            }
            $save->idtarea = $Ticket->idtarea;
            $save->ruta = 'storage/upload/' . $path;
            $save->save();
        } 
      
        return redirect()->route('ticket.index')->with('mensaje','El ticket '.$Ticket->idtarea.' editado con exito.');
    }

    public function cambiarCategTicket($id)
    {
        $Ticket = Tic_Tarea::findorfail($id);
        $Image = Tic_Imagen_x_Tarea::where('idtarea', '=', $id )->first();
        $Categorias = Tic_Catproblema::all();
        $Subcategorias = Tic_CatproblemaSub::all();
        $Solucionadores = Tic_Solucionador::where('idtipsolucionador', '=', $Ticket->getSolucionador->idtipsolucionador)->orWhere('idtipsolucionador', '=', $Ticket->getCategoriaProb->getCatProblema->idcatprob)->orderBy('idsolucionador')->pluck('nombre', 'idsolucionador')->toArray();
        return view ('Coordinacion.Informatica.ticket.cambiar',compact('Ticket', 'Image', 'Categorias', 'Subcategorias', 'Solucionadores'));
    }

    public function editarCategTicket(Request $request, $id)
    {
        $Ticket = Tic_Tarea::findorfail($id);
        $Ticket->idcatprobsub = $request->input('subcateg');
        $Ticket->idsolucionador = $request->input('solu');
        if ($request->input('solu') == 0) {
            $Ticket->idsolucionador = 0;
            $borrado = Tic_Estados_x_Tarea::where('idtarea','=',$Ticket->idtarea)->where('idestado','=', 2)->delete();
        }
        else{
            $Ticket->idsolucionador = $request->input('solu');
            $estadoTareaMod = Tic_Estados_x_Tarea::where('idtarea','=', $Ticket->idtarea)->where('idestado','=', 2)->first();
            if(empty($estadoTareaMod)){
                $estadoTareaMod = new Tic_Estados_x_Tarea;
                $estadoTareaMod->idtarea = $Ticket->idtarea;
                $estadoTareaMod->idestado = 2;
                $estadoTareaMod->fecha = \Carbon\Carbon::today()->toDateString();
                $estadoTareaMod->observacion = $request->input('observ');
            }
            $estadoTareaMod->save();
        }
        $Ticket->save();

        return redirect()->route('ticket.asigna')->with('mensaje','El ticket '.$Ticket->idtarea.' editado con exito.');    
    }

    public function allTicket($id)
    {
        $solucionador = Tic_Solucionador::where('idsolucionador','=', $id)->first();

        $latestPosts = DB::table('iprodha.tic_estados_x_tarea')
                   ->select('idtarea', DB::raw('MAX(idestado) estado_actual'))
                   ->groupBy('idtarea');
    

            $TicketsAsignado = Tic_tarea::joinSub($latestPosts, 'latest_posts', function ($join) {
                $join->on('iprodha.tic_tarea.idtarea', '=', 'latest_posts.idtarea');
            })->where('estado_actual', '=', 2)
            ->where('idsolucionador','=', $solucionador->idsolucionador)
            ->orderBy('iprodha.tic_tarea.idtarea', 'desc')->get();

            $TotalTicketAsignado = $TicketsAsignado->count();

            $TicketsEnproceso = Tic_tarea::joinSub($latestPosts, 'latest_posts', function ($join) {
                $join->on('iprodha.tic_tarea.idtarea', '=', 'latest_posts.idtarea');
            })->where('estado_actual', '=', 3)
            ->where('idsolucionador','=', $solucionador->idsolucionador)
            ->orderBy('iprodha.tic_tarea.idtarea', 'desc')->get();

            $TotalTicketEnproceso = $TicketsEnproceso->count();
            $Total = $TotalTicketEnproceso + $TotalTicketAsignado;

            $prueba = ['nombre' => $solucionador->nombre, 'totalAsign' => $TotalTicketAsignado, 'totalEnproc' => $TotalTicketEnproceso, 'total' => $Total];
        return $prueba;
    }

    
    public function isMobileDevice() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    
}

*/