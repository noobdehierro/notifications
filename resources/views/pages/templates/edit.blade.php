@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Plantillas'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <form role="form" method="POST" action={{ route('templates.update', $template->id) }}
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header pb-0">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0">Crear Plantilla</p>
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm ms-auto">Guardar</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-uppercase text-sm">Informacion Del Plantilla</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">Nombre
                                                            del Plantilla</label>
                                                        <input class="form-control" type="text" name="name"
                                                            value="{{ $template->name }}">

                                                        @if ($errors->has('name'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('name') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">Nombre
                                                            del Canal</label>
                                                        <select class="form-select" name="channel_id">
                                                            @foreach ($channels as $channel)
                                                                @if ($channel->id == $template->channel_id)
                                                                    <option value="{{ $channel->id }}" selected>
                                                                        {{ $channel->name }}</option>
                                                                @else
                                                                    <option value="{{ $channel->id }}">
                                                                        {{ $channel->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                        @if ($errors->has('channel_id'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('channel_id') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input"
                                                            class="form-control-label">Contenido</label>

                                                        <textarea class="form-control" name="placeholder" id="exampleFormControlTextarea1" rows="3">{{ $template->placeholder }}</textarea>

                                                        @if ($errors->has('placeholder'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('placeholder') }}
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
