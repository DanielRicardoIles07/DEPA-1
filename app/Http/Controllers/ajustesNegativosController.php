<?php

namespace reportes\Http\Controllers;

use Illuminate\Http\Request;
use reportes\Http\Requests\ReportesFormRequest;
use Illuminate\Support\Facades\Auth;
use reportes\Reportes;
use DB;

class ajustesNegativosController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('reportes.ajustesNegativos');
    }

    public function store(ReportesFormRequest $request) {
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_fin = $request->get('fecha_fin');
        
        $reporte = new Reportes();
        $reporte->fecha_inicio= $request->get('fecha_inicio');
        $reporte->fecha_fin = $request->get('fecha_fin');
        $reporte->user_id= Auth::user()->id;
        $reporte->tipo_reporte_id=5;
        $reporte->tipo_log='Mostrado';
        $reporte->save();

        $ajustes = DB::select('SELECT u.id, u.username, u.email, r.nombre, x.descuento FROM tbl_users u 
RIGHT JOIN recursos r ON r.tbl_users_id = u.id 
RIGHT JOIN (
SELECT m.usuario, round(SUM(m.monto) *-1,2) "descuento"  FROM movimientos m WHERE m.task_id IN(
SELECT t.id FROM task t WHERE t.estado IN (5,7) AND t.ciudad_id = 6 AND t.fecha_inicio BETWEEN :between AND :and 
AND t.solicitante IN (10392,19116)) AND m.type_movimientos_id NOT IN (22,23)
GROUP BY m.usuario) x ON x.usuario =  u.id ORDER BY descuento',["between"=>$fecha_inicio, "and"=>$fecha_fin]);
        
        
        return view('reportes.ajustesNegativos', ["ajustes" => $ajustes]);
    }

}
