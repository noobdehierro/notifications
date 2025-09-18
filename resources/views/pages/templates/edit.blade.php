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
                                <form role="form" method="POST"
                                    action={{ route('templates.update', $template->id) }} enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-header pb-0">
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">Editar Plantilla</p>
                                            <button type="submit"
                                                class="btn btn-primary btn-sm ms-auto">Actualizar</button>
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
                                                    <select class="form-select" name="channel_id" id="channel_id">
                                                        @foreach ($channels as $channel)
                                                        @if ($channel->id == $template->channel_id)
                                                        <option value="{{ $channel->id }}" selected>
                                                            {{ $channel->name }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $channel->id }}">
                                                            {{ $channel->name }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('channel_id'))
                                                    <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                        role="alert">{{ $errors->first('channel_id') }}
                                                    </div>
                                                    @endif

                                                </div>

                                                <div class="form-group" id="content" hidden>
                                                    <label for="example-text-input"
                                                        class="form-control-label">Contenido</label>

                                                    <textarea class="form-control" name="placeholder" id="placeholder" rows="3">{{ $template->placeholder }}</textarea>
                                                    <span class="text-success">maximo de caracteres: <b
                                                            id="max_characters"></b></span>
                                                    @if ($errors->has('placeholder'))
                                                    <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                        role="alert">{{ $errors->first('placeholder') }}
                                                    </div>
                                                    @endif
                                                </div>

                                                <div class="form-group" id="template_name_div" hidden>
                                                    <label for="example-text-input" class="form-control-label">Nombre de la plantilla</label>
                                                    <input class="form-control" type="text" name="template_name" value="{{ $template->template_name }}">

                                                    @if ($errors->has('template_name'))
                                                    <div class="alert alert-warning alert-dismissible fade show mt-1"
                                                        role="alert">{{ $errors->first('template_name') }}
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
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            type: "POST",
            url: "{{ route('get-chanel') }}",
            data: {
                channel_id: $("#channel_id").val(),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                if (response.id != 1) {
                    console.log(response.max_characters);

                    // $("#content").attr('hidden', false);

                    $("#placeholder").attr('maxlength', response.max_characters);

                    $("#max_characters").text(response.max_characters);

                    $("#template_name_div").attr('hidden', true);
                    $("#content").attr('hidden', false);


                } else {
                    $("#template_name_div").attr('hidden', false);
                    $("#content").attr('hidden', true);


                }

            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }

        });

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

                        if (response.id != 1) {
                            $("#content").attr('hidden', false);

                            $("#placeholder").attr('maxlength', response.max_characters);

                            $("#max_characters").text(response.max_characters);

                            $("#template_name_div").attr('hidden', true);

                        } else {
                            $("#content").attr('hidden', true);
                            $("#template_name_div").attr('hidden', false);
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }

                });
            } else {
                $("#content").attr('hidden', true);
            }
        });
    });
</script>
@endsection