@extends('layout')

@section('title')
    {{ trans('blogs.title_edit_article') }}
@stop

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/blogs">{{ trans('blogs.blogs') }}</a></li>

            @if ($blog->category->parent->id)
                <li class="breadcrumb-item"><a href="/blogs/{{ $blog->category->parent->id }}">{{ $blog->category->parent->name }}</a></li>
            @endif

            <li class="breadcrumb-item"><a href="/blogs/{{ $blog->category->id }}">{{ $blog->category->name }}</a></li>
            <li class="breadcrumb-item"><a href="/articles/{{ $blog->id }}">{{ $blog->title }}</a></li>
            <li class="breadcrumb-item active">{{ trans('blogs.title_edit_article') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="form next">
        <form action="/articles/edit/{{ $blog->id }}" method="post">
            <input type="hidden" name="token" value="{{ $_SESSION['token'] }}">

            <div class="form-group{{ hasError('cid') }}">
                <label for="inputCategory">{{ trans('blogs.blog') }}</label>

                <?php $inputCategory = getInput('cid', $blog->category_id); ?>
                <select class="form-control" id="inputCategory" name="cid">

                    @foreach ($categories as $data)
                        <option value="{{ $data->id }}"{{ ($inputCategory === $data->id && ! $data->closed) ? ' selected' : '' }}{{ $data->closed ? ' disabled' : '' }}>{{ $data->name }}</option>

                        @if ($data->children->isNotEmpty())
                            @foreach($data->children as $datasub)
                                <option value="{{ $datasub->id }}"{{ ($inputCategory === $datasub->id && ! $data->closed) ? ' selected' : '' }}{{ $datasub->closed ? ' disabled' : '' }}>– {{ $datasub->name }}</option>
                            @endforeach
                        @endif
                    @endforeach

                </select>
                {!! textError('cid') !!}
            </div>

            <div class="form-group{{ hasError('title') }}">
                <label for="inputTitle">{{ trans('blogs.name') }}:</label>
                <input type="text" class="form-control" id="inputTitle" name="title" maxlength="50" value="{{ getInput('title', $blog->title) }}" required>
                {!! textError('title') !!}
            </div>

            <div class="form-group{{ hasError('text') }}">
                <label for="text">{{ trans('blogs.article') }}:</label>
                <textarea class="form-control markItUp" maxlength="{{ setting('maxblogpost') }}" data-hint="{{ trans('common.characters_left') }}" id="text" rows="5" name="text" required>{{ getInput('text', $blog->text) }}</textarea>
                <span class="js-textarea-counter"></span>
                {!! textError('text') !!}
            </div>

            <div class="form-group{{ hasError('tags') }}">
                <label for="inputTags">{{ trans('blogs.tags') }}:</label>
                <input type="text" class="form-control" id="inputTags" name="tags" maxlength="100" value="{{ getInput('tags', $blog->tags) }}" required>
                {!! textError('tags') !!}
            </div>

            @include('app._upload', ['id' => $blog->id, 'files' => $blog->files, 'type' => App\Models\Blog::class, 'paste' => true])

            <button class="btn btn-primary">{{ trans('blogs.change') }}</button>
        </form>
    </div><br>

    <a href="/rules">{{ trans('common.rules') }}</a> /
    <a href="/stickers">{{ trans('common.stickers') }}</a> /
    <a href="/tags">{{ trans('common.tags') }}</a><br><br>
@stop
