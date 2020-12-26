@extends('layout')

@section('title', __('blogs.authors'))

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/blogs">{{ __('index.blogs') }}</a></li>
            <li class="breadcrumb-item active">{{ __('blogs.authors') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    @if ($articles->isNotEmpty())
        @foreach ($articles as $article)
            <div class="section mb-3 shadow">
                <div class="section-title">
                    <i class="fa fa-pencil-alt"></i>
                    <a href="/blogs/active/articles?user={{ $article->login }}">{{ $article->login }}</a>
                </div>

                {{ $article->cnt }} {{ __('blogs.all_articles') }} / {{ $article->count_comments }} {{ __('main.comments') }}
            </div>
        @endforeach

        {{ __('blogs.total_authors') }}: <b>{{ $articles->total() }}</b><br>
    @else
        {!! showError(__('blogs.empty_articles')) !!}
    @endif

    {{ $articles->links() }}
@stop
