@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Campaigns'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <form role="form" method="POST"
                                        action="{{ route('campaigns.update', $campaign->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header pb-0">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0">Editar Campaña</p>
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm ms-auto">Guardar</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-uppercase text-sm">Información De Campaña</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-control-label">Nombre de la
                                                            campaña</label>
                                                        <input class="form-control" type="text" name="name"
                                                            value="{{ $campaign->name }}">
                                                        @if ($errors->has('name'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">
                                                                {{ $errors->first('name') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    {{-- @if ($errors->any())
                                                        <div class="alert alert-danger alert-dismissible fade show mt-1"
                                                            role="alert">
                                                            <ul class="mb-0">
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif --}}


                                                    @if ($templates->count() > 0)
                                                        @php
                                                            $templatesGroupedByChannel = $templates->groupBy(
                                                                'channel_id',
                                                            );
                                                        @endphp

                                                        @foreach ($templatesGroupedByChannel as $channel_id => $templatesGroup)
                                                            <div class="form-group">
                                                                <label class="form-control-label"
                                                                    for="template_{{ $channel_id }}">
                                                                    Plantillas de {{ $templatesGroup[0]->channel->name }}
                                                                </label>
                                                                <select class="form-select"
                                                                    id="template_{{ $channel_id }}"
                                                                    name="templates_id[{{ $channel_id }}]">
                                                                    <option value="">Seleccione una plantilla</option>
                                                                    @foreach ($templatesGroup as $template)
                                                                        {{-- <option value="{{ $template->id }}">
                                                                            {{ $template->name . ' - ' . $template->channel->name }}
                                                                        </option> --}}
                                                                        <option value="{{ $template->id }}"
                                                                            @foreach ($campaign->templates as $campaignTemplate)
                                                                                {{ $campaignTemplate->id == $template->id ? 'selected' : '' }} @endforeach>
                                                                            {{ $template->name . ' - ' . $template->channel->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        @endforeach
                                                        @if ($errors->has('templates_id'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">
                                                                {{ $errors->first('templates_id') }}
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="query_id" class="form-control-label">Consulta</label>
                                                        <select class="form-select" name="query_id">
                                                            <option value="">Seleccione una consulta</option>
                                                            @foreach ($queries as $query)
                                                                <option value="{{ $query->id }}"
                                                                    {{ $query->id == $campaign->query_id ? 'selected' : '' }}>
                                                                    {{ $query->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('query_id'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">
                                                                {{ $errors->first('query_id') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="days" class="form-control-label">Días de la
                                                            campaña</label>
                                                        @php
                                                            $days = json_decode($campaign->days);
                                                        @endphp
                                                        <select class="form-select" name="days[]" multiple size="7">
                                                            <option value="monday"
                                                                {{ in_array('monday', $days) ? 'selected' : '' }}>
                                                                Lunes</option>
                                                            <option value="tuesday"
                                                                {{ in_array('tuesday"', $days) ? 'selected' : '' }}>
                                                                Martes</option>
                                                            <option value="wednesday"
                                                                {{ in_array('wednesday"', $days) ? 'selected' : '' }}>
                                                                Miércoles</option>
                                                            <option value="thursday"
                                                                {{ in_array('thursday"', $days) ? 'selected' : '' }}>
                                                                Jueves</option>
                                                            <option value="friday"
                                                                {{ in_array('friday"', $days) ? 'selected' : '' }}>
                                                                Viernes</option>
                                                            <option value="saturday"
                                                                {{ in_array('saturday"', $days) ? 'selected' : '' }}>
                                                                Sábado</option>
                                                            <option value="sunday"
                                                                {{ in_array('sunday', $days) ? 'selected' : '' }}>
                                                                Domingo</option>
                                                        </select>

                                                        @if ($errors->has('days'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">
                                                                {{ $errors->first('days') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hour" class="form-control-label">Horario de la
                                                            campaña</label>
                                                        <input class="form-control" type="time" name="hour"
                                                            value="{{ $campaign->hour }}">
                                                        @if ($errors->has('hour'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">
                                                                {{ $errors->first('hour') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" name="is_active" type="checkbox"
                                                            id="is_active" @if ($campaign->is_active) checked @endif>
                                                        <label class="form-check-label" for="is_active">Activo</label>
                                                        @if ($errors->has('is_active'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">
                                                                {{ $errors->first('is_active') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- @include('layouts.footers.auth.footer') --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
