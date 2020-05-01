@extends('layout_rss')

@section('title')
    {{ __('blogs.title_rss') }}
@stop

@section('content')

    @foreach ($articles as $article)
        <?php $article->text = bbCode($article->text); ?>
        <?php $article->text = str_replace('/uploads/stickers', siteUrl().'/uploads/stickers', $article->text); ?>

        <item>
            <title>{{ $article->title }}</title>
            <link>{{ siteUrl() }}/articles/{{ $article->id }}</link>
            <description>{{ $article->text }}</description>
            <author>{{ $article->user->login }}</author>
            <pubDate>{{ date('r', $article->created_at) }}</pubDate>
            <category>{{ __('index.blogs') }}</category>
            <guid>{{ siteUrl() }}/articles/{{ $article->id }}</guid>
        </item>
    @endforeach
@stop
