@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Canales'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <form role="form" method="POST" action={{ route('channels.update', $channel->id) }}
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header pb-0">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0">Editar Canal</p>
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm ms-auto">Actualizar</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-uppercase text-sm">Informacion Del Canal</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">Nombre
                                                            del canal</label>
                                                        <input class="form-control" type="text" name="name"
                                                            value="{{ $channel->name }}">

                                                        @if ($errors->has('name'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('name') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">maximo de
                                                            caracteres</label>

                                                        <input class="form-control" type="number" name="max_characters"
                                                            value="{{ $channel->max_characters }}">

                                                        @if ($errors->has('max_characters'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('max_characters') }}
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
