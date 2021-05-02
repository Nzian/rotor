@section('header')
    <h1>{{ __('settings.guestbook') }}</h1>
@stop

<form method="post">
    @csrf
    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[bookadds]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[bookadds]" id="bookadds"{{ getInput('sets.bookadds', $settings['bookadds']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="bookadds">{{ __('settings.guestbook_guests_allow') }}</label>
    </div>

    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[bookscores]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[bookscores]" id="bookscores"{{ getInput('sets.bookscores', $settings['bookscores']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="bookscores">{{ __('settings.guestbook_points_add') }}</label>
    </div>

    <div class="form-group{{ hasError('sets[bookpost]') }}">
        <label for="bookpost">{{ __('settings.guestbook_per_page') }}:</label>
        <input type="number" class="form-control" id="bookpost" name="sets[bookpost]" maxlength="2" value="{{ getInput('sets.bookpost', $settings['bookpost']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[bookpost]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[guesttextlength]') }}">
        <label for="guesttextlength">{{ __('settings.guestbook_symbols') }}:</label>
        <input type="number" class="form-control" id="guesttextlength" name="sets[guesttextlength]" maxlength="5" value="{{ getInput('sets.guesttextlength', $settings['guesttextlength']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[guesttextlength]') }}</div>
    </div>

    <button class="btn btn-primary">{{ __('main.save') }}</button>
</form>
