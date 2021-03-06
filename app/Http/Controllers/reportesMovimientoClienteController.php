<?php

namespace reportes\Http\Controllers;

use Illuminate\Http\Request;
use reportes\Http\Requests\ReportesFormRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use reportes\Reportes;
use DB;

class reportesMovimientoClienteController extends Controller {

    protected $id=4;
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        $user=Auth::user()->id;
        $tienePermiso=$this->validarPermisos($this->id,$user);
        if ($tienePermiso) {
           return view('reportes.reportesMovimientosCliente');
        }else{
            return view('home');
        }
        
    }

    public function store(ReportesFormRequest $request) {
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_fin = $request->get('fecha_fin');
        $idEmpresa = $request->get('idEmpresa');
        
        $reporte = new Reportes();
        $reporte->fecha_inicio= $request->get('fecha_inicio');
        $reporte->fecha_fin = $request->get('fecha_fin');
        $reporte->id_empresa= $request->get('idEmpresa');
        $reporte->user_id= Auth::user()->id;
        $reporte->tipo_reporte_id=3;
        $reporte->tipo_log='Descargado';
        $reporte->save();


        $movimientos = DB::select('SELECT t.uuid, m.fecha, m.monto, m.acumulado, IF(m.task_id > 1, REPLACE(m.descripcion, m.task_id, " - "),m.descripcion) "descripcion", m.usuario, m.empresas_id, m.factura FROM movimientos m LEFT JOIN task t ON m.task_id = t.id WHERE m.empresas_id = :id AND fecha BETWEEN  :between  AND :and', ["between" => $fecha_inicio, "and" => $fecha_fin, "id" => $idEmpresa]);

        Excel::create('reporte movimientos empresa ' . $idEmpresa . '', function($excel)use($movimientos) {
            $excel->sheet('reporte movimientos', function($sheet)use($movimientos) {



                $resultado = $movimientos;

                foreach ($resultado as &$sf) {
                    $sf = (array) $sf;
                }
                $sheet->fromArray($resultado);
            });
        })->export('xls');
    }

}
