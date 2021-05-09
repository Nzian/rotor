<?php $countFiles = $model->files->count(); ?>
<div class="media-file">
    <div id="myCarousel{{ $model->id }}" class="carousel slide" data-bs-ride="carousel">
        @if ($countFiles > 1)
            <div class="carousel-indicators">
                @for ($i = 0; $i < $countFiles; $i++)
                    <button type="button" data-bs-target="#myCarousel{{ $model->id }}" data-bs-slide-to="{{ $i }}"{!! empty($i) ? ' class="active"' : '' !!}></button>
                @endfor
            </div>
        @endif

        <div class="carousel-inner">
            @foreach ($model->files as $file)
                <div class="carousel-item{{ $loop->first ? ' active' : '' }}">
                    @php
                        $image = resizeImage($file->hash, ['alt' => $model->title, 'class' => 'w-100']);
                    @endphp

                    @if (isset($path))
                        <a href="{{ $path }}/{{ $model->id }}">{{ $image }}</a>
                    @else
                        {{ $image }}
                    @endif
                </div>
            @endforeach
        </div>

        @if ($countFiles > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel{{ $model->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel{{ $model->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
</div>
