<?php

namespace reportes\Http\Controllers;

use Illuminate\Http\Request;
use reportes\Http\Requests\ReportesFormRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use reportes\Reportes;
use DB;

class ServActivChiaController extends Controller {
    
    protected $id=9;
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $user=Auth::user()->id;
        $tienePermiso=$this->validarPermisos($this->id, $user);
        if ($tienePermiso) {
            return view('reportes.reportesChia');
        }else{
            return view('home');
        }
        
    }

    public function activacionChia(ReportesFormRequest $request) {
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_final = $request->get('fecha_final');
        $consulta = DB::select('SELECT DATE_FORMAT(a.start_real_datetime,"%Y-%m-%d") "fecha inicio real",
DATE_FORMAT(a.start_real_datetime,"%H:%i") "hora inicio real",  
DATE_FORMAT(a.end_real_datetime,"%Y-%m-%d") "fecha final real",
DATE_FORMAT(a.end_real_datetime,"%H:%i") "hora final real",
c.nombre "ciudad", p.id "idpunto", p.name "punto",r.nombre FROM activation_task a
LEFT JOIN recursos r ON a.tbl_user_id = r.tbl_users_id
LEFT JOIN ciudad c ON c.id = a.city_id
LEFT JOIN points p ON p.id = a.point_id
WHERE a.start_real_datetime BETWEEN :between AND :and
AND p.id IN( 33,58)', ["between" => $fecha_inicio, "and" => $fecha_final]);

        $reporte = new Reportes();
        $reporte->fecha_inicio= $request->get('fecha_inicio');
        $reporte->fecha_fin = $request->get('fecha_final');
        $reporte->tipo_reporte_id=4;
        $reporte->user_id= Auth::user()->id;
        $reporte->tipo_log='Descargado';
        $reporte->save();

        Excel::create('reporte activaciones chia '.$fecha_inicio.' a '.$fecha_final.'', function($excel)use($consulta) {
            $excel->sheet('reporte servicios', function($sheet)use($consulta) {



                $resultado = $consulta;

                foreach ($resultado as &$sf) {
                    $sf = (array) $sf;
                }
                $sheet->fromArray($resultado);
            });
        })->export('xls');
    }

    public function mostrar(ReportesFormRequest $request) {
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_final = $request->get('fecha_final');
        $activacionChia = DB::select('SELECT DATE_FORMAT(a.start_real_datetime,"%Y-%m-%d") "fecha_inicio_real",
          DATE_FORMAT(a.start_real_datetime,"%H:%i") "hora_inicio_real",
          DATE_FORMAT(a.end_real_datetime,"%Y-%m-%d") "fecha_final_real",
          DATE_FORMAT(a.end_real_datetime,"%H:%i") "hora_final_real",
          c.nombre "ciudad", p.id "idpunto", p.name "punto",r.nombre FROM activation_task a
          LEFT JOIN recursos r ON a.tbl_user_id = r.tbl_users_id
          LEFT JOIN ciudad c ON c.id = a.city_id
          LEFT JOIN points p ON p.id = a.point_id
          WHERE a.start_real_datetime BETWEEN  :between AND :and
          AND p.id IN( 33,58)
          ', ["between" => $fecha_inicio, "and" => $fecha_final]);
        return view('reportes.recibir', ["activacionChia" => $activacionChia]);
    }

}
