@extends('layout')

@section('title')
    {{ __('blogs.title_create') }}
@stop

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/blogs">{{ __('index.blogs') }}</a></li>
            <li class="breadcrumb-item active">{{ __('blogs.title_create') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="form cut">
        <form action="/blogs/create" method="post">
            @csrf
            <div class="form-group{{ hasError('cid') }}">
                <label for="inputCategory">{{ __('blogs.blog') }}</label>

                <?php $inputCategory = getInput('cid', $cid); ?>
                <select class="form-control" id="inputCategory" name="cid">

                    @foreach ($cats as $data)
                        <option value="{{ $data->id }}"{{ ($inputCategory === $data->id && ! $data->closed) ? ' selected' : '' }}{{ $data->closed ? ' disabled' : '' }}>{{ $data->name }}</option>

                        @if ($data->children->isNotEmpty())
                            @foreach($data->children as $datasub)
                                <option value="{{ $datasub->id }}"{{ ($inputCategory === $datasub->id && ! $datasub->closed) ? ' selected' : '' }}{{ $datasub->closed ? ' disabled' : '' }}>– {{ $datasub->name }}</option>
                            @endforeach
                        @endif
                    @endforeach

                </select>
                <div class="invalid-feedback">{{ textError('cid') }}</div>
            </div>

            <div class="form-group{{ hasError('title') }}">
                <label for="inputTitle">{{ __('blogs.name') }}:</label>
                <input type="text" class="form-control" id="inputTitle" name="title" maxlength="50" value="{{ getInput('title') }}" required>
                <div class="invalid-feedback">{{ textError('title') }}</div>
            </div>

            <div class="form-group{{ hasError('text') }}">
                <label for="text">{{ __('blogs.article') }}:</label>
                <textarea class="form-control markItUp" maxlength="{{ setting('maxblogpost') }}" id="text" rows="5" name="text" required>{{ getInput('text') }}</textarea>
                <div class="invalid-feedback">{{ textError('text') }}</div>
                <span class="js-textarea-counter"></span>
            </div>

            <div class="form-group{{ hasError('tags') }}">
                <label for="inputTags">{{ __('blogs.tags') }}:</label>
                <input type="text" class="form-control" id="inputTags" name="tags" maxlength="100" value="{{ getInput('tags') }}" required>
                <div class="invalid-feedback">{{ textError('tags') }}</div>
            </div>

            @include('app/_upload', ['files' => $files, 'type' => App\Models\Blog::$morphName, 'paste' => true])

            <button class="btn btn-primary">{{ __('blogs.add') }}</button>
        </form>
    </div><br>

    {{ __('blogs.text_create1') }}<br><br>

    <a href="/rules">{{ __('main.rules') }}</a> /
    <a href="/stickers">{{ __('main.stickers') }}</a> /
    <a href="/tags">{{ __('main.tags') }}</a><br><br>
@stop
