@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Configuraciones'])
    <div class="container-fluid py-4">
        @if ($message = session()->has('success') || ($message = session()->has('error')))
            <div id="alert">
                @include('components.alert')
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6>Configuraciones</h6>
                            <!-- Agregar botón -->
                            <a href="{{ route('configurations.create') }}" class="btn btn-primary">Agregar</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            #</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nombre</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Código</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Valor</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configurations as $configuration)
                                        <tr>
                                            <td>
                                                <p class="text-xs text-secondary mb-0  px-3 ">{{ $configuration->id }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $configuration->label }}</p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $configuration->code }}</p>
                                                </p>
                                            </td>
                                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                <p class="text-xs text-center font-weight-bold mb-0">
                                                    {{ $configuration->value }}
                                                </p>
                                            </td>


                                            <td class="align-middle text-center">
                                                <a href="{{ route('configurations.edit', $configuration->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    Editar
                                                </a>
                                                <form action="{{ route('configurations.destroy', $configuration->id) }}"
                                                    class="form-check-inline py-0" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta campaña?')">
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
