@extends('layout')

@section('title', __('index.ignores'))

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/menu">{{ __('main.menu') }}</a></li>
            <li class="breadcrumb-item active">{{ __('index.ignores') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    @if ($ignores->isNotEmpty())
        <form action="/ignores/delete?page={{ $ignores->currentPage() }}" method="post">
            @csrf
            @foreach ($ignores as $data)
                <div class="b">

                    <div class="float-right">
                        <a href="/messages/talk/{{ $data->ignoring->login }}" data-toggle="tooltip" title="{{ __('main.write') }}"><i class="fa fa-reply text-muted"></i></a>
                        <a href="/ignores/note/{{ $data->id }}" data-toggle="tooltip" title="{{ __('main.note') }}"><i class="fa fa-sticky-note text-muted"></i></a>
                        <input type="checkbox" name="del[]" value="{{ $data->id }}">
                    </div>

                    <div class="img">
                        {!! $data->ignoring->getAvatar() !!}
                        {!! $data->ignoring->getOnline() !!}
                    </div>

                    <b>{!! $data->ignoring->getProfile() !!}</b> <small>({{ dateFixed($data->created_at) }})</small><br>
                    {!! $data->ignoring->getStatus() !!}
                </div>

                <div>
                    @if ($data->text)
                        {{ __('main.note') }}: {!! bbCode($data->text) !!}<br>
                    @endif
                </div>
            @endforeach

            <div class="float-right">
                <button class="btn btn-sm btn-danger">{{ __('main.delete_selected') }}</button>
            </div>
        </form>

        <br>{{ __('main.total') }}: <b>{{ $ignores->total() }}</b><br>
    @else
        {!! showError(__('ignores.empty_list')) !!}
    @endif

    {{ $ignores->links() }}

    <div class="section-form shadow my-3">
        <form method="post">
            @csrf
            <div class="input-group{{ hasError('user') }}">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                </div>

                <input type="text" class="form-control" id="user" name="user" maxlength="20" value="{{ getInput('user', $login) }}" placeholder="{{ __('main.user_login') }}" required>

                <span class="input-group-btn">
                    <button class="btn btn-primary">{{ __('main.add') }}</button>
                </span>
            </div>
            <div class="invalid-feedback">{{ textError('user') }}</div>
        </form>
    </div>

    <i class="fa fa-users"></i> <a href="/contacts">{{ __('index.contacts') }}</a><br>
    <i class="fa fa-envelope"></i> <a href="/messages">{{ __('index.messages') }}</a><br>
@stop
