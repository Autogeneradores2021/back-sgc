@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Documento inicial {{$id}}</h1>
        <div class="row">
            <div class="col col-3">
                <label class="form-label">Fecha de inicio</label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Fecha detecta</label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
            <div class="col col-6">
                <label class="form-label">Detectado en </label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
        </div>
        <div class="row">
            <div class="col col-9">
                <label class="form-label">Detectado por </label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Requisito aplicable</label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
        </div>
        <div class="row">
            <div class="col col-9">
                <label class="form-label">Lider del proceso </label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Proceso en que se detecta</label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
        </div>
        <div class="row">
            <div class="col col-9">
                <label class="form-label">Como se detecta </label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Tipo de accion</label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="form-label">Evidencia</label>
                <input type="text" disabled class="form-control" value="{{$id}}">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="form-label">Evidencia</label>
                <textarea type="text" disabled class="form-control" value="{{$id}}">
            </div>
        </div>
    </div>
@endsection
