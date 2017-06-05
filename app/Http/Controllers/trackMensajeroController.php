<?php

namespace reportes\Http\Controllers;

use Illuminate\Http\Request;
use reportes\Http\Requests\ReportesFormRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use reportes\Reportes;
use DB;

class trackMensajeroController extends Controller {

    public function index() {
        return view('reportes.trackMensajero');
    }

    public function track(ReportesFormRequest $request) {
        $id_mensajero = $request->get('id_mensajero');

        $id_resource = DB::select('select t.id_resource from task t where t.id = ' . $id_mensajero . '');
        $id_resource = $id_resource[0]->id_resource;
        
        $fecha_asignacion = DB::select('select th.datecreate from task_history th where th.task_id = ' . $id_mensajero . ' and th.type_task_status_id = 3 order by id desc limit 1');
        $fecha_asignacion = $fecha_asignacion[0]->datecreate;
        
        $fecha_finalizacion = DB::select('select th.datecreate from task_history th where th.task_id = ' . $id_mensajero . ' and th.type_task_status_id = 5 order by id desc limit 1');
        $fecha_finalizacion = $fecha_finalizacion[0]->datecreate;

        $track = DB::select('SELECT * FROM track WHERE datecreate BETWEEN "' . $fecha_asignacion . '" AND "' . $fecha_finalizacion . '" AND tbl_users_id = ' . $id_resource . '');

        return view("reportes/trackMensajero",["track"=>$track]);
    }

}
