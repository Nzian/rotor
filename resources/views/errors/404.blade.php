@extends('layout')

@section('title', __('errors.error') . ' 404')

@section('header', '')
@section('description', __('errors.error') . ' 404')

@section('content')
    <?php $images = glob(public_path('assets/img/errors/*.png')); ?>

    <div class="row">
        <div class="col-12 text-center">
            <img src="/assets/img/errors/{{ basename($images[array_rand($images)]) }}" alt="error 404">
        </div>
        <div class="col-12 text-center">
            <h1>{{ __('errors.error') }} 404!</h1>

            <div class="lead">{{ $exception->getMessage() ?: __('errors.not_found') }}</div>

            <div class="my-3">
                @if (url()->previous() === url()->current())
                    <a class="btn btn-primary" href="/"><i class="fa fa-arrow-circle-left"></i> {{ __('errors.to_main') }}</a>
                @else
                    <a class="btn btn-primary" href="{{ url()->previous() }}"><i class="fa fa-arrow-circle-left"></i> {{ __('errors.return') }}</a>
                @endif
            </div>
        </div>
    </div>
@stop
