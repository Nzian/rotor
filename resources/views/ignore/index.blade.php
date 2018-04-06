@extends('layout')

@section('title')
    Игнор-лист
@stop

@section('content')

    <h1>Игнор-лист</h1>

    @if ($ignores->isNotEmpty())

        <form action="/ignore/delete?page={{ $page->current }}" method="post">
            <input type="hidden" name="token" value="{{ $_SESSION['token'] }}">

            @foreach ($ignores as $data)
                <div class="b">

                    <div class="float-right">
                        <a href="/private/send?user={{ $data->ignoring->login }}" title="Написать"><i class="fa fa-reply text-muted"></i></a>
                        <a href="/ignore/note/{{ $data->id }}" title="Заметка"><i class="fa fa-sticky-note text-muted"></i></a>
                        <input type="checkbox" name="del[]" value="{{ $data->id }}">
                    </div>

                    <div class="img">{!! userAvatar($data->ignoring) !!}</div>

                    <b>{!! profile($data->ignoring) !!}</b> <small>({{ dateFixed($data->created_at) }})</small><br>
                    {!! userStatus($data->ignoring) !!} {!! userOnline($data->ignoring) !!}
                </div>

                <div>
                    @if ($data->text)
                        Заметка: {!! bbCode($data->text) !!}<br>
                    @endif
                </div>
            @endforeach

            <div class="float-right">
                <button class="btn btn-sm btn-danger">Удалить выбранное</button>
            </div>
        </form>

        {!! pagination($page) !!}

        Всего в игноре: <b>{{ $page->total }}</b><br>
    @else
        {!! showError('Игнор-лист пуст!') !!}
    @endif

    <div class="form my-3">
        <form method="post">
            <input type="hidden" name="token" value="{{ $_SESSION['token'] }}">
            <div class="form-inline">
                <div class="form-group{{ hasError('user') }}">
                    <input type="text" class="form-control" id="user" name="user" maxlength="20" value="{{ getInput('user') }}" placeholder="Логин пользователя" required>
                </div>

                <button class="btn btn-primary">Добавить</button>
            </div>
            {!! textError('user') !!}
        </form>
    </div>

    <i class="fa fa-users"></i> <a href="/contact">Контакт-лист</a><br>
    <i class="fa fa-envelope"></i> <a href="/private">Сообщения</a><br>
@stop
