@extends('layout')

@section('title')
    Блоги - Новые комментарии (Стр. {{ $page->current }})
@stop

@section('content')

    <h1>Новые комментарии</h1>

    @if ($comments->isNotEmpty())
        @foreach ($comments as $data)
            <div class="post">
                <div class="b">
                    <i class="fa fa-comment"></i> <b><a href="/article/comments/{{ $data->relate_id }}">{{ $data->title }}</a></b> ({{ $data->count_comments }})

                    <div class="float-right">
                        @if (isAdmin())
                            <a href="#" onclick="return deleteComment(this)" data-rid="{{ $data->relate_id }}" data-id="{{ $data->id }}" data-type="{{ App\Models\Blog::class }}" data-token="{{ $_SESSION['token'] }}" data-toggle="tooltip" title="Удалить"><i class="fa fa-times text-muted"></i></a>
                        @endif
                    </div>
                </div>

                <div>
                    {!! bbCode($data->text) !!}<br>

                    Написал: {!! profile($data->user) !!} <small>({{ dateFixed($data->created_at) }})</small><br>
                    @if (isAdmin())
                        <span class="data">({{ $data->brow }}, {{ $data->ip }})</span>
                    @endif
                </div>
            </div>
        @endforeach

        {!! pagination($page) !!}
    @else
        {!! showError('Комментарии не найдены!') !!}
    @endif
@stop
