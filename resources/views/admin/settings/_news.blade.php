@section('header')
    <h1>{{ __('settings.news') }}</h1>
@stop

<form method="post">
    @csrf
    <div class="form-group{{ hasError('sets[sendprivatmailday]') }}">
        <label for="lastnews">{{ __('settings.news_main') }}:</label>
        <input type="number" class="form-control" id="lastnews" name="sets[lastnews]" maxlength="2" value="{{ getInput('sets.lastnews', $settings['lastnews']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[lastnews]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[postnews]') }}">
        <label for="postnews">{{ __('settings.news_per_page') }}:</label>
        <input type="number" class="form-control" id="postnews" name="sets[postnews]" maxlength="2" value="{{ getInput('sets.postnews', $settings['postnews']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[postnews]') }}</div>
    </div>

    <button class="btn btn-primary">{{ __('main.save') }}</button>
</form>
