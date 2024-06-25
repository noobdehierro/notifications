@extends('emails.layout')

@section('title', $campaignName)

@section('content')
    <p>Hola {{ $name }},</p>
    {!! $placeholder !!}
@endsection
