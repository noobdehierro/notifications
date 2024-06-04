@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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
                                    <form role="form" method="POST" action={{ route('templates.store') }}
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
                                            <p class="text-uppercase text-sm">Informacion De Plantilla</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">Nombre
                                                            del Plantilla</label>
                                                        <input class="form-control" type="text" name="name">

                                                        @if ($errors->has('name'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('name') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="example-text-input" class="form-control-label">Nombre
                                                            del Canal</label>
                                                        <select class="form-select" name="channel_id" id="channel_id">
                                                            <option value="" selected>Seleccionar Canal</option>
                                                            @foreach ($channels as $channel)
                                                                <option value="{{ $channel->id }}">
                                                                    {{ $channel->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        @if ($errors->has('channel_id'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('channel_id') }}
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="form-group" hidden id="content">
                                                        <label for="example-text-input"
                                                            class="form-control-label">Contenido</label>

                                                        <textarea class="form-control" name="placeholder" id="placeholder" rows="3"></textarea>

                                                        @if ($errors->has('placeholder'))
                                                            <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                                role="alert">{{ $errors->first('placeholder') }}
                                                            </div>
                                                        @endif
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
    <script type="text/javascript">
        $(document).ready(function() {


            $("#channel_id").change(function() {
                $("#placeholder").val("");
                if ($(this).val() != "") {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('get-chanel') }}",
                        data: {
                            channel_id: $(this).val(),
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response.max_characters);

                            $("#content").attr('hidden', false);

                            $("#placeholder").attr('maxlength', response.max_characters);

                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }

                    });
                } else {
                    $("#content").attr('hidden', true);
                }
            });

        })
    </script>
@endsection
