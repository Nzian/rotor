@section('header')
    <h1>{{ __('settings.files') }}</h1>
@stop

<form method="post">
    @csrf
    <div class="form-group{{ hasError('sets[filesize]') }}">
        <label for="filesize">{{ __('main.max_file_weight') }} (Mb):</label>
        <input type="number" class="form-control" id="filesize" name="sets[filesize]" maxlength="3" value="{{ getInput('sets.filesize', round($settings['filesize'] / 1048576)) }}" required>
        <div class="invalid-feedback">{{ textError('sets[filesize]') }}</div>

        <input type="hidden" value="1048576" name="mods[filesize]">
        <span class="text-muted font-italic">{{ __('main.server_limit') }}: {{ ini_get('upload_max_filesize') }}</span>
    </div>

    <div class="form-group{{ hasError('sets[maxfiles]') }}">
        <label for="maxfiles">{{ __('settings.loads_max_files') }}:</label>
        <input type="number" class="form-control" id="maxfiles" name="sets[maxfiles]" maxlength="2" value="{{ getInput('sets.maxfiles', $settings['maxfiles']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[maxfiles]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[file_extensions]') }}">
        <label for="file_extensions">{{ __('main.valid_file_extensions') }}:</label>
        <textarea class="form-control" id="file_extensions" name="sets[file_extensions]" required>{{ getInput('sets.file_extensions', $settings['file_extensions']) }}</textarea>
        <div class="invalid-feedback">{{ textError('sets[file_extensions]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[screensize]') }}">
        <label for="screensize">{{ __('settings.images_reduction_size') }} (px):</label>
        <input type="number" class="form-control" id="screensize" name="sets[screensize]" maxlength="4" value="{{ getInput('sets.screensize', $settings['screensize']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[screensize]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[previewsize]') }}">
        <label for="previewsize">{{ __('settings.images_preview_size') }} (px):</label>
        <input type="number" class="form-control" id="previewsize" name="sets[previewsize]" maxlength="3" value="{{ getInput('sets.previewsize', $settings['previewsize']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[previewsize]') }}</div>
    </div>

    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[copyfoto]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[copyfoto]" id="copyfoto"{{ getInput('sets.copyfoto', $settings['copyfoto']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="copyfoto">{{ __('settings.images_copyright') }}</label>
    </div>

    <img src="/assets/img/images/watermark.png" alt="watermark" title="{{ siteUrl() }}/assets/img/images/watermark.png"><br>

    <p class="text-muted font-italic">
        {{ __('settings.images_hint') }}
    </p>

    <button class="btn btn-primary">{{ __('main.save') }}</button>
</form>
