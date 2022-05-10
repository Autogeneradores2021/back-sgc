@extends('layouts.app')

@section('content')
    <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: system-ui, -apple-system, /* Firefox supports this but not yet `system-ui` */ 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';">
    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div style="max-width: 600px; margin: 0 auto; padding: 30px;" class="email-container">
    <!-- BEGIN BODY -->
    <div style="background-color: rgb(241 245 249); padding: 50px;">
        <div style="border-radius: 10px; background-color: #ffffff;">
            <div style="width: 300px; max-width: 600px; height: auto; margin: auto; display: block; padding-top: 30px;">
                <img src="https://raw.githubusercontent.com/YUND4/images/main/logoelectrohuila.png" alt="Electrohuila SA ESP"/>
            </div>
            <h1 style="color: rgb(63, 63, 63)"><strong>Sistema de Gestion de Calidad</strong></h1 style="color: gray">
            <div style="width: 300px; max-width: 600px; height: auto; margin: auto; display: block;">
                <img style="width: 300px; max-width: 600px; height: auto; margin: auto; display: block;" src="https://raw.githubusercontent.com/YUND4/images/main/Screenshot_from_2022-03-11_14-00-30-removebg-preview.png" alt="SGC">
            </div>
            <div class="text" style="padding: 0 2.5em; text-align: center;">
                <h2><strong>{!! $title !!}</strong></h2>
                <h3>{!! $msg !!}</h3>
                <p>
                    <div><strong>SOLICITUD</strong></div>
                    <div><span>#{!! $record['request_code'] !!}</span></div>
                    <div><strong>LÍDER AUDITADO</strong></div>
                    <div><span>{!! $record['process_lead_name'] !!}</span></div>
                    <div><strong>DETECTADO POR</strong></div>
                    <div><span>{!! $record['detected_for_name'] !!}</span></div>
                </p>
                <p style="padding-bottom: 30px;"><a class="btn" href="{!!$linkUrl!!}">Ir a la aplicacion</a></p>
                <!-- HTML !-->
            </div>
        </div>
    </div>

    </div>
@endsection
