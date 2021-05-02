@section('header')
    <h1>{{ __('settings.protects') }}</h1>
@stop

<form method="post">
    @csrf
    <div class="form-group{{ hasError('sets[captcha_symbols]') }}">
        <label for="captcha_symbols">{{ __('settings.captcha_valid_symbols') }} [a-z0-9]:</label>
        <input pattern="[a-z0-9]+" type="text" class="form-control" id="captcha_symbols" name="sets[captcha_symbols]" maxlength="36" value="{{ getInput('sets.captcha_symbols', $settings['captcha_symbols']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[captcha_symbols]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[captcha_maxlength]') }}">
        <label for="captcha_maxlength">{{ __('settings.captcha_symbols') }} [4-6]:</label>
        <input type="number" min="4" max="6" class="form-control" id="captcha_maxlength" name="sets[captcha_maxlength]" maxlength="1" value="{{ getInput('sets.captcha_maxlength', $settings['captcha_maxlength']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[captcha_maxlength]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[captcha_angle]') }}">
        <label for="captcha_angle">{{ __('settings.captcha_rotate') }} [0-30]:</label>
        <input type="number" min="0" max="30" class="form-control" id="captcha_angle" name="sets[captcha_angle]" maxlength="2" value="{{ getInput('sets.captcha_angle', $settings['captcha_angle']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[captcha_angle]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[captcha_offset]') }}">
        <label for="captcha_offset">{{ __('settings.captcha_amplitude') }} [0-10]:</label>
        <input type="number" min="0" max="10" class="form-control" id="captcha_offset" name="sets[captcha_offset]" maxlength="2" value="{{ getInput('sets.captcha_offset', $settings['captcha_offset']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[captcha_offset]') }}</div>
    </div>

    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[captcha_distortion]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[captcha_distortion]" id="captcha_distortion"{{ getInput('sets.captcha_distortion', $settings['captcha_distortion']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="captcha_distortion">{{ __('settings.captcha_distortion') }}</label>
    </div>

    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[captcha_interpolation]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[captcha_interpolation]" id="captcha_interpolation"{{ getInput('sets.captcha_interpolation', $settings['captcha_interpolation']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="captcha_interpolation">{{ __('settings.captcha_blur') }}</label>
    </div>

    <h3 class="mt-3">reCaptcha</h3>

    <?php $inputCaptcha = getInput('sets.captcha_type', $settings['captcha_type']); ?>

    <div class="form-group{{ hasError('sets[captcha_type]') }}">
        <label for="captcha_type">{{ __('main.type') }}:</label>
        <select class="form-control" id="captcha_type" name="sets[captcha_type]">

            @foreach ($protects as $key => $captcha)
                <?php $selected = ($key === $inputCaptcha) ? ' selected' : ''; ?>
                <option value="{{ $key }}"{{ $selected }}>{{ $captcha }}</option>
            @endforeach

        </select>
        <div class="invalid-feedback">{{ textError('sets[captcha_type]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[recaptcha_public]') }}">
        <label for="recaptcha_public">{{ __('settings.captcha_public') }}:</label>
        <input type="hidden" name="opt[recaptcha_public]" value="1">
        <input type="text" class="form-control" id="recaptcha_public" name="sets[recaptcha_public]" value="{{ getInput('sets.recaptcha_public', $settings['recaptcha_public']) }}">
        <div class="invalid-feedback">{{ textError('sets[recaptcha_public]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[recaptcha_private]') }}">
        <label for="recaptcha_private">{{ __('settings.captcha_private') }}:</label>
        <input type="hidden" name="opt[recaptcha_private]" value="1">
        <input type="text" class="form-control" id="recaptcha_private" name="sets[recaptcha_private]" value="{{ getInput('sets.recaptcha_private', $settings['recaptcha_private']) }}">
        <div class="invalid-feedback">{{ textError('sets[recaptcha_private]') }}</div>
    </div>

    <button class="btn btn-primary">{{ __('main.save') }}</button>
</form>
