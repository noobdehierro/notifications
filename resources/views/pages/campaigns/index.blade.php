@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Campañas'])
    <div class="container-fluid py-4">
        @if (session()->has('success'))
            <div id="alert">
                @include('components.alert', ['type' => 'success', 'message' => session('success')])
            </div>
        @elseif (session()->has('error'))
            <div id="alert">
                @include('components.alert', ['type' => 'danger', 'message' => session('error')])
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6>Campañas</h6>
                            <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Agregar</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nombre</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Query</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Plantillas</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Día</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Hora</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Activo</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Fecha de creación</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaigns as $campaign)
                                        <tr>
                                            <td>
                                                <p class="text-xs text-secondary mb-0 px-3">{{ $campaign->id }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $campaign->name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $campaign->querydata->query }}
                                                </p>
                                            </td>
                                            <td style="max-width: 400px; overflow: hidden; text-overflow: ellipsis;">
                                                @if ($campaign->templates->isEmpty())
                                                    No templates
                                                @else
                                                    <ul>
                                                        @foreach ($campaign->templates as $template)
                                                            <li>{{ $template->name }} - {{ $template->channel->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $days = json_decode($campaign->days);
                                                @endphp
                                                @if (is_array($days))
                                                    <ul>
                                                        @foreach ($days as $day)
                                                            <li>{{ $day }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ $campaign->days }}
                                                @endif
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $campaign->hour }}</p>
                                            </td>
                                            <td>
                                                @if ($campaign->is_active == 1)
                                                    <span class="badge badge-sm bg-gradient-success">Activo</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span
                                                    class="badge badge-sm bg-gradient-success">{{ $campaign->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('campaigns.edit', $campaign->id) }}"
                                                    class="btn btn-info btn-sm">Editar</a>
                                                <form action="{{ route('campaigns.destroy', $campaign->id) }}"
                                                    class="form-check-inline py-0" method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta campaña?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="far fa-trash-alt me-2"></i>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
