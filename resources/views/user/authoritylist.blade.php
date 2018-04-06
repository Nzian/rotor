@extends('layout')

@section('title')
    Рейтинг репутации (Стр. {{ $page->current }})
@stop

@section('content')

    <h1>Рейтинг репутации</h1>

    @if ($users->isNotEmpty())
        @foreach($users as $key => $data)
            <div class="b">
                <div class="img">{!! userAvatar($data) !!}</div>

                {{ ($page->offset + $key + 1) }}.

                @if ($user == $data->login)
                    <b>{!! profile($data, '#ff0000') !!}</b>
                @else
                    <b>{!! profile($data) !!}</b>
                @endif
                (Репутация: {{ $data->rating }})<br>
                {!! userStatus($data) !!} {!! userOnline($data) !!}
            </div>

            <div>
                Плюсов: {{ $data->posrating }} / Минусов: {{ $data->negrating }}<br>
                Дата регистрации: {{ dateFixed($data->created_at, 'd.m.Y') }}
            </div>
        @endforeach

        {!! pagination($page) !!}

        <div class="form">
            <b>Поиск пользователя:</b><br>
            <form action="/authoritylist" method="post">
                <input type="text" name="user" value="{{ $user }}">
                <input type="submit" value="Искать">
            </form>
        </div>
        <br>

        Всего пользователей: <b>{{ $page->total }}</b><br><br>
    @else
        {!! showError('Пользователей еще нет!') !!}
    @endif
@stop
