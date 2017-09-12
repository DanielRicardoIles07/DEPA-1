<?php

namespace reportes\Http\Controllers;

use Illuminate\Http\Request;
use reportes\Diademas;
use reportes\User;
use Illuminate\Support\Facades\Redirect;
use reportes\Http\Requests\DiademasFormRequest;
use Illuminate\Support\Facades\Auth;
use DB;

class AsignarDiademasController extends Controller
{
    public function store(DiademasFormRequest $request) {
    	$codigo = $request->get('codigo');
        $id_diadema = $request->get('id');
        $equipos = DB::connection('reportesmensajeros')->select("select id_equipos, codigo, tipo, marca, modelo,serial, os_instalado from equipos ". "where codigo = '$codigo'");

        $diademas_asignadas = DB::connection('reportesmensajeros')->select("select codigo_d, fecha_compra, fecha_asignacion from equipos_diademas ed 
            inner join diademas d on ed. diademas_id=d. id_diadema
            inner join equipos e on ed.equipos_id = e.id_equipos
            where codigo_d = $id_diadema and fecha_asignacion is not null and fecha_desasignacion is null ");
       
        return view('asignardiademas.diademas', ["diadema" => Diademas::findOrFail($id_diadema), "equipos" => $equipos, "diademas_asignadas" => $diademas_asignadas]);
    }

    public function edit($id_diadema) {
        $equipos = DB::connection('reportesmensajeros')->select("select id_equipos, codigo,tipo, marca, modelo,serial, os_instalado, diademas_id, fecha_asignacion, fecha_desasignacion from equipos e inner
            join equipos_diademas ed on e.id_equipos=ed.equipos_id where diademas_id=$id_diadema and fecha_desasignacion is null");
        return view('asignardiademas.edit', ["diadema" => Diademas::findOrFail($id_diadema), "equipos" => $equipos]);
    }
    public function show($id_diadema) {
        return view("asignardiademas.show", ["diademas" => Diademas::findOrFail($id_diadema)]);
    }

    public function update(DiademasFormRequest $request, $id_diadema) {
        $equipo = $request->get('id_equipos');
        $fecha_asignacion = date('Y-m-d H:i:s');
        $asignador = Auth::id();
        $validacion = DB::connection('reportesmensajeros')->select("select id_equipos, codigo,tipo, marca, modelo,serial, os_instalado, diademas_id
        	, fecha_asignacion, fecha_desasignacion from equipos e inner
            join equipos_diademas ed on e.id_equipos=ed.equipos_id where diademas_id=$id_diadema and id_equipos=$equipo and fecha_asignacion is not null and fecha_desasignacion is null");
        if(count($validacion)>0){
            return redirect()->back()->with('computador_ya_asignado', 'La diadema esta asignada a el equipo');
        }else{
            DB::connection('reportesmensajeros')->insert("insert into equipos_diademas(diademas_id,equipos_id, fecha_asignacion, asignador)values ($id_diadema , $equipo,'$fecha_asignacion',$asignador)");
            return redirect()->back()->with('computador_asignado', 'Equipo asignado');
        }
    }    
    public function destroy(DiademasFormRequest $request, $id_diadema) {
        $equipo = $request->get('id_equipos');
        $fecha_desasignacion = date('Y-m-d H:i:s');
        DB::connection('reportesmensajeros')->delete("UPDATE equipos_diademas
        SET fecha_desasignacion = '$fecha_desasignacion'
        WHERE diademas_id=$id_diadema and equipos_id=$equipo");
        return redirect()->back()->with('computador_desasignado', 'Se desasigno el equipo');
    }
}
