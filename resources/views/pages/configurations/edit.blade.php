@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Configuraciones'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <form role="form" method="POST"
                                        action={{ route('configurations.update', $configuration->id) }}
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header pb-0">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0">Editar Configuración</p>
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm ms-auto">Actualizar</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-uppercase text-sm">Informacion Del Plantilla</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="example-text-input"
                                                            class="form-control-label">Nombre</label>

                                                        <input class="form-control" type="text" name="label"
                                                            value="{{ $configuration->label }}">

                                                        @if ($errors->has('label'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('label') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input"
                                                            class="form-control-label">Codigo</label>
                                                        <input class="form-control" type="text" name="code"
                                                            value="{{ $configuration->code }}">

                                                        @if ($errors->has('code'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('code') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input"
                                                            class="form-control-label">Valor</label>
                                                        <input class="form-control" type="text" name="value"
                                                            value="{{ $configuration->value }}">

                                                        @if ($errors->has('value'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('value') }}
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
