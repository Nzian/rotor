@extends('layout')

@section('title')
    Форум - Список тем {{ $user->login }} (Стр. {{ $page->current }})
@stop

@section('content')

    <h1>Список тем {{ $user->login }}</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/forum">Форум</a></li>
            <li class="breadcrumb-item active">Список тем {{ $user->login }}</li>
        </ol>
    </nav>

    @foreach ($topics as $data)
        <div class="b">
            <i class="fa {{ $data->getIcon() }} text-muted"></i>
            <b><a href="/topic/{{ $data->id }}">{{ $data->title }}</a></b> ({{ $data->count_posts }})
        </div>

        <div>
            {!! $data->pagination() !!}
            Форум: <a href="/forum/{{ $data->forum->id }}">{{ $data->forum->title }}</a><br>
            Автор: {{ $data->user->login }} / Посл.: {{ $data->lastPost->user->login }} ({{ dateFixed($data->lastPost->created_at) }})
        </div>
    @endforeach

    {!! pagination($page) !!}
@stop
