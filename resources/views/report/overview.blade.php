@extends('layouts.app')

@section('content')

    <div id="exportable">

    @foreach ($collection as $r )
        <div class="container">
        <div class="text-center"><h1>Nueva Solicitud</h1></div>
        <div class="row">
            <div class="col col-3">
                <label class="form-label">Fecha de inicio</label>
                <input type="text" disabled class="form-control" value="{{date('d-m-Y', strtotime($r['init_date']))}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Fecha detecta *</label>
                <input type="text" disabled class="form-control" value="{{date('d-m-Y', strtotime($r['detected_date']))}}">
            </div>
            <div class="col col-6">
                <label class="form-label">Detectado en *</label>
                <input type="text" disabled class="form-control" value="{{$r['detected_in_name']}}">
            </div>
        </div>
        <div class="row">
            <div class="col col-9">
                <label class="form-label">Detectado por *</label>
                <input type="text" disabled class="form-control" value="{{$r['detected_for_name']}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Requisito aplicable *</label>
                <input type="text" disabled class="form-control" value="{{$r['action_type_name']}}">
            </div>
        </div>
        <div class="row">
            <div class="col col-9">
                <label class="form-label">Lider del proceso *</label>
                <input type="text" disabled class="form-control" value="{{$r['process_lead_name']}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Proceso en que se detecta *</label>
                <input type="text" disabled class="form-control" value="{{$r['affected_process_name']}}">
            </div>
        </div>
        <div class="row">
            <div class="col col-9">
                <label class="form-label">Como se detecta *</label>
                <input type="text" disabled class="form-control" value="{{$r['how_detected_name']}}">
            </div>
            <div class="col col-3">
                <label class="form-label">Tipo de accion *</label>
                <input type="text" disabled class="form-control" value="{{$r['action_type_name']}}">
            </div>
        </div>
            <div class="row">
                <div class="col">
                <label class="form-label">Evidencia *</label>
                <input type="text" disabled class="form-control" value="{{$r['evidence_file']}}">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="form-label">Evidencia *</label>
                    <div class="container form-control">
                        {!!$r['request_description']!!}
                    </div>
                </div>
            </div>
        </div>

        
        <div class="container">
            @if ($r['work_team_lead'])
            <div class="text-center"><h1>ATENDER SOLICITUD</h1></div>
            <div class="text-center"><h3>Equipo de Trabajo<h3></div>
                <h5 class="text-center">Lider</h5>
                <div class="row">
                <div class="col col-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" disabled class="form-control" value="{{$r['work_team_lead']->name}}">
                </div>
                <div class="col col-4">
                    <label class="form-label">Area</label>
                    <input type="text" disabled class="form-control" value="{{$r['work_team_lead']->area}}">
                </div>
                <div class="col col-4">
                    <label class="form-label">Cargo</label>
                    <input type="text" disabled class="form-control" value="{{$r['work_team_lead']->position}}">
                </div>
                </div>
                @foreach ( $r['work_team'] as $member)
                    <div class="text-center">
                        <h5>Integrante 1<h5>
                    </div>
                    <div class="row">
                        <div class="col col-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" disabled class="form-control" value="{{$member['name']}}">
                        </div>
                        <div class="col col-4">
                            <label class="form-label">Area</label>
                            <input type="text" disabled class="form-control" value="{{$member['area']}}">
                        </div>
                        <div class="col col-4">
                            <label class="form-label">Cargo</label>
                            <input type="text" disabled class="form-control" value="{{$member['position']}}">
                        </div>
                    </div>
                @endforeach
            @endif
            @if ($r['immediately_upgrade_plan'])
                @foreach ($r['immediately_upgrade_plan'] as $inm_uplan)
                    <h3 class="text-center">Accion de Correccion Inmediata</h3>
                    <div class="row">
                        <div class="col col-6">
                            <label class="form-label">Descripcion breve de la
                            Accion Correctiva</label>
                            <input type="text" disabled class="form-control" value="{{$inm_uplan->goal_description}}">
                        </div>
                        <div class="col col-6">
                            <label class="form-label">Nombre del Responsable</label>
                            <input type="text" disabled class="form-control" value="{{$inm_uplan->person_assigned_name}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="text" disabled class="form-control" value="{{$inm_uplan->init_date}}">
                        </div>
                        <div class="col col-4">
                            <label class="form-label">Fecha Fin</label>
                            <input type="text" disabled class="form-control" value="{{$inm_uplan->end}}">
                        </div>
                        <div class="col col-4">
                            <label class="form-label">Plazo Semana</label>
                            <input type="text" disabled class="form-control" value="{{$inm_uplan->unit_measurement}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Evidencia *</label>
                            <div class="container form-control">
                                {!!$inm_uplan->follow_process_description!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Evidencia</label>
                            <input type="text" disabled class="form-control" value="{{$inm_uplan->evidence_file}}">
                        </div>
                    </div>
                @endforeach
            @endif
            @if ($r['questionary_answers'])
                @foreach ($r['questionary_answers'] as $ans)
                    <h3 class="text-center">Analisis de causas<h3>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">{{$ans->question}}</label>
                            <input type="text" disabled class="form-control" value="{{$ans->answer}}">
                        </div>
                    </div>
                @endforeach
            @endif
            @if ($r['definitive_upgrade_plan'])
                @foreach ($r['definitive_upgrade_plan'] as $def_uplan)
                    <h3 class="text-center">Accion Correctiva</h3>
                    <div class="row">
                        <div class="col col-6">
                            <label class="form-label">Descripcion breve de la
                            Accion Correctiva</label>
                            <input type="text" disabled class="form-control" value="{{$def_uplan->goal_description}}">
                        </div>
                        <div class="col col-6">
                            <label class="form-label">Nombre del Responsable</label>
                            <input type="text" disabled class="form-control" value="{{$def_uplan->person_assigned_name}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-4">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="text" disabled class="form-control" value="{{$def_uplan->init_date}}">
                        </div>
                        <div class="col col-4">
                            <label class="form-label">Fecha Fin</label>
                            <input type="text" disabled class="form-control" value="{{$def_uplan->end}}">
                        </div>
                        <div class="col col-4">
                            <label class="form-label">Plazo Semana</label>
                            <input type="text" disabled class="form-control" value="{{$def_uplan->unit_measurement}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Evidencia *</label>
                            <div class="container form-control">
                                {!!$def_uplan->follow_process_description!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Evidencia</label>
                            <input type="text" disabled class="form-control" value="{{$def_uplan->evidence_file}}">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        
        <div class="container">
            @foreach ( $r['tracking'] as $tracking )
                <h1 class="text-center">Seguimiento</h1>
                <div class="row">
                    <div class="col col-9">
                        <label class="form-label">Breve descripcion del
                            seguimiento *</label>
                        <input type="text" disabled class="form-control" value="{{$tracking->goal_description}}">
                    </div>
                    <div class="col col-3">
                        <label class="form-label">Avance *</label>
                        <input type="text" disabled class="form-control" value="{{$tracking->percentage}}">
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Evidencia *</label>
                            <div class="container form-control">
                                {!!$tracking->follow_process_description!!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="container">
            @if ($r['finish_request'])
                <div class="text-center"><h1>Evaluacion</h1></div>
                <div class="row">
                    <div class="col col-4">
                        <label class="form-label">Fecha de Evaluacion *</label>
                        <input type="text" disabled class="form-control"  value="{{$r['finish_request']->tracking_date}}">
                    </div>
                    <div class="col col-4">
                        <label class="form-label">Desde* </label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->tracking_date_period_init}}">
                    </div>
                    <div class="col col-4">
                        <label class="form-label">Hasta *</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->tracking_date_period_end}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label class="form-label">Evidencia *</label>
                        <div class="container form-control">
                            {!!$r['finish_request']->descriptions!!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-2.4">
                        <label class="form-label">Objectivo *</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->objective}}">
                    </div>
                    <div class="col col-2.4">
                        <label class="form-label">Total Conformes *</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->total_agree}}">
                    </div>
                    <div class="col col-2.4">
                        <label class="form-label">Total no Conformes*</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->total_disagree}}">
                    </div>
                    <div class="col col-2.4">
                        <label class="form-label">Total evaluado*</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->total_review}}">
                    </div>
                    <div class="col col-2.4">
                        <label class="form-label">Cumplimiento*</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->tracking_date_period_init}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col-9">
                        <label class="form-label">Responsable del seguimiento*</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->total_fulfilment}}">
                    </div>
                    <div class="col col-3">
                        <label class="form-label">Fecha del seguimiento *</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->tracking_date_period_init}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col-9">
                        <label class="form-label">Responsable de la evaluacion*</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->created_at}}">
                    </div>
                    <div class="col col-3">
                        <label class="form-label">Resultado de la evaluacion *</label>
                        <input type="text" disabled class="form-control" value="{{$r['finish_request']->result_analysis}}"    >
                    </div>
                </div>
            @endif
        </div>

    @endforeach

    <div class="container">
        <div class="row justify-content-end p-4">
            <div class="col-1">
                {{-- <button type="button" class="btn btn-primary" onclick="generatePDF()">Imprimir</button> --}}
            </div>
        </div>
    </div>
    </div>

    <script type="text/javascript">
        var element = document.getElementById('exportable');
        element.style.width = '700px';
        element.style.height = '900px';
        var opt = {
            margin:       0.5,
            filename:     'Resumen.pdf',
            image:        { type: 'jpeg', quality: 1 },
            html2canvas:  { scale: 1 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait',precision: '12' }
        };
        
        // choose the element and pass it to html2pdf() function and call the save() on it to save as pdf.
        html2pdf().set(opt).from(element).save().then((_) => {
        });
      </script>

@endsection
