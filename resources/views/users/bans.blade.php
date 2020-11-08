@extends('layout')

@section('title', __('index.banned'))

@section('content')
    @if ($banhist)
        <b><span style="color:#ff0000">{{ __('users.reason_ban') }}: {!! bbCode($banhist->reason) !!}</span></b><br><br>

        @if (! $banhist->explain && setting('addbansend'))
            <div class="section-form shadow">
                <form method="post" action="/ban">
                    <div class="form-group{{ hasError('msg') }}">
                        <label for="msg">{{ __('users.explanation') }}:</label>
                        <textarea class="form-control" id="msg" rows="5" name="msg" required>{{ getInput('msg') }}</textarea>
                        <div class="invalid-feedback">{{ textError('msg') }}</div>
                    </div>
                    <button class="btn btn-primary">{{ __('main.send') }}</button>
                </form>
            </div>

            {!! __('users.ban_text') !!}<br>
        @endif
    @endif

    {{ __('users.ending_ban') }}: <b>{{ formatTime($user->timeban - SITETIME) }}</b><br><br>
    {{ __('users.read_section') }} <b><a href="/rules">{{ __('main.site_rules') }}</a></b><br><br>
    {!! __('users.ban_text2') !!}<br>
@stop
