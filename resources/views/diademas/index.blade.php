<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<meta charset="utf-8">
@extends('layouts.admin')

@section('titulo')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">    
<h3 class="box-title"><span class="glyphicon glyphicon-headphones"></span> Diademas</h3>
@endsection

@section('content')
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="row1">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4>Nuevo <a href="diademas/create"><i class="fa fa-plus"></i></a></h4>
    </div>
</div>
<div class="row1">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tabcontent">
        <div class="table-responsive">
            <table id="tablausuarios" class="table table-condensed table-hover display">
            <!-- <caption><h4>Diademas</h4></caption> -->
                <thead>
                <th>N°</th>
                <th>Codigo</th>
                <th>Fecha Compra</th>
                <th>Asignada a</th>
                <th>Notas</th>
                <th colspan="2">Opciones</th>

                </thead>
                <input type="hidden" value="{{$var=0}}"/>
               @foreach ($diademas as $d)
                <tbody>
                    <tr>
                        <td>{{++$var}}</td>
                        <td>{{$d->codigo_d}}</td>
                        <td>{{$d->fecha_compra}}</td>
                        <td>
                        @if($d->codigoe)
                        <a href="{{URL::action('EquiposController@show',$d->id_equipos)}}">{{$d->codigoe}}</a>
                        @else
                        <p class="text-danger">sin asignar</p>
                        @endif
                        </td>
                        <td></td>
                        <td>
                        <a href="{{URL::action('DiademasController@show',$d->id_diadema)}}"></a><button class="btn btn-info" data-toggle="tooltip"><span class="glyphicon glyphicon-eye-open"></span> </button>
                        <a href="{{URL::action('AsignarDiademasController@edit',$d->id_diadema)}}"><button class="btn btn-primary" data-toggle="tooltip" title="Asignar a un equipo"><span class="glyphicon glyphicon-paperclip"></span></button></a>
                            <button class="btn btn-success" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span> </button>
                            <button class="btn btn-danger" data-toggle="tooltip"><span class="glyphicon glyphicon-trash"></span> </button>
                        </td>
                    </tr>
                </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>
<!-- tooltip actions button-->
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
@endsection

