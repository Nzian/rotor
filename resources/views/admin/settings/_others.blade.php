@section('header')
    <h1>{{ __('settings.others') }}</h1>
@stop

<form method="post">
    @csrf
    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[errorlog]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[errorlog]" id="errorlog"{{ getInput('sets.errorlog', $settings['errorlog']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="errorlog">{{ __('settings.log_enable') }}</label>
    </div>

    <div class="form-group{{ hasError('sets[description]') }}">
        <label for="description">{{ __('settings.description') }}:</label>
        <input type="text" class="form-control" id="description" name="sets[description]" maxlength="250" value="{{ getInput('sets.description', $settings['description']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[description]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[nocheck]') }}">
        <label for="nocheck">{{ __('settings.unscannable_ext') }}:</label>
        <input type="text" class="form-control" id="nocheck" name="sets[nocheck]" maxlength="100" value="{{ getInput('sets.nocheck', $settings['nocheck']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[nocheck]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[moneyname]') }}">
        <label for="moneyname">{{ __('settings.moneys') }}:</label>
        <input type="text" class="form-control" id="moneyname" name="sets[moneyname]" maxlength="100" value="{{ getInput('sets.moneyname', $settings['moneyname']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[moneyname]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[scorename]') }}">
        <label for="scorename">{{ __('settings.points') }}:</label>
        <input type="text" class="form-control" id="scorename" name="sets[scorename]" maxlength="100" value="{{ getInput('sets.scorename', $settings['scorename']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[scorename]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[statusdef]') }}">
        <label for="statusdef">{{ __('settings.default_status') }}:</label>
        <input type="text" class="form-control" id="statusdef" name="sets[statusdef]" maxlength="20" value="{{ getInput('sets.statusdef', $settings['statusdef']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[statusdef]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[guestsuser]') }}">
        <label for="guestsuser">{{ __('settings.guestsuser') }}:</label>
        <input type="text" class="form-control" id="guestsuser" name="sets[guestsuser]" maxlength="20" value="{{ getInput('sets.guestsuser', $settings['guestsuser']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[guestsuser]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[deleted_user]') }}">
        <label for="deleted_user">{{ __('settings.deleted_user') }}:</label>
        <input type="text" class="form-control" id="deleted_user" name="sets[deleted_user]" maxlength="20" value="{{ getInput('sets.deleted_user', $settings['deleted_user']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[deleted_user]') }}</div>
    </div>

    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="sets[addbansend]">
        <input type="checkbox" class="custom-control-input" value="1" name="sets[addbansend]" id="addbansend"{{ getInput('sets.addbansend', $settings['addbansend']) ? ' checked' : '' }}>
        <label class="custom-control-label" for="addbansend">{{ __('settings.ban_explanation') }}</label>
    </div>

    <button class="btn btn-primary">{{ __('main.save') }}</button>
</form>
