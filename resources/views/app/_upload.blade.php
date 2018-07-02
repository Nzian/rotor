<?php
$cond  = ! empty($paste);
$click = $cond ? 'return pasteImage(this);' : false;
?>

<div class="js-images">
    @if ($files->isNotEmpty())
        @foreach ($files as $file)
            <span class="js-image">
                {!! resizeImage($file->hash, ['width' => 100, 'onclick' => $click]) !!}
                <a href="#" onclick="return deleteImage(this);" data-id="{{ $file->id }}" data-type="{{ $type }}" data-token="{{ $_SESSION['token'] }}"><i class="fas fa-times"></i></a>
            </span>
        @endforeach
    @endif
</div>

<div class="js-image-template d-none">
    <span class="js-image">
        <img src="#" width="100" onclick="{{ $click }}" alt="" class="img-fluid">
        <a href="#" onclick="return deleteImage(this);" data-type="{{ $type }}" data-token="{{ $_SESSION['token'] }}"><i class="fas fa-times"></i></a>
    </span>
</div>

<label class="btn btn-sm btn-secondary" for="image">
    <input id="image" type="file" name="image" accept="image/*" onchange="return submitImage(this, {{ $cond }});" data-id="{{ $id or 0 }}" data-type="{{ $type }}" data-token="{{ $_SESSION['token'] }}" hidden>
    Прикрепить картинку&hellip;
</label><br>
