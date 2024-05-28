@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Notificaciones'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <form role="form" method="POST" action={{ route('notifications.store') }}
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header pb-0">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0">Crear Campaña</p>
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm ms-auto">Guardar</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-uppercase text-sm">Informacion De Campaña</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="example-text-input"
                                                            class="form-control-label">Campaña</label>
                                                        <select class="form-select" name="campaign_id">
                                                            @foreach ($campaigns as $campaign)
                                                                <option value="{{ $campaign->id }}">
                                                                    {{ $campaign->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @if ($errors->has('name'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('name') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">Hora de
                                                            envio</label>
                                                        <input class="form-control" type="time" name="sent_at">

                                                        @if ($errors->has('sent_at'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('sent_at') }}
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
