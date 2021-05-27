<?php

namespace App\Http\Middleware;

use App\Models\Login;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CookieAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*if (empty(defaultSetting('app_installed')) && file_exists(HOME . '/install/')) {
            redirect('/install/index.php');
        }*/

        $this->cookieAuth($request);
        $this->setSetting($request);

        return $next($request);
    }

    /**
     * Авторизует по кукам
     *
     * @param Request $request
     *
     * @return void
     */
    private function cookieAuth(Request $request): void
    {
        //if (empty($_SESSION['id']) && isset($_COOKIE['login'], $_COOKIE['password'])) {
        if ($request->hasCookie('login') &&
            $request->hasCookie('password') &&
            $request->session()->missing('id')
        ) {
            //$login    = $_COOKIE['login'];
            $login    = $request->cookie('login');
           // $password = $_COOKIE['password'];
            $password = $request->cookie('password');

            $user = getUserByLogin($login);

            if ($user && $login === $user->login && $password === md5($user->password . config('app.key'))) {
                //$_SESSION['id']       = $user->id;
                //$_SESSION['password'] = md5(config('app.key') . $user->password);
                //$_SESSION['online']   = null;

                session()->put('id', $user->id);
                session()->put('password', md5(config('app.key') . $user->password));

                $user->saveVisit(Login::COOKIE);
            }
        }
    }

    /**
     * Устанавливает настройки
     *
     * @param Request $request
     *
     * @return void
     */
    private function setSetting(Request $request): void
    {
        $user = getUser();

        $userSets['language'] = $user->language ?? defaultSetting('language');
        $userSets['themes'] = $user->themes ?? defaultSetting('themes');

        if ($request->session()->has('language')) {
            $userSets['language'] = $request->session()->get('language');
        }

        if (! file_exists(RESOURCES . '/lang/' . $userSets['language'])) {
            $userSets['language'] = defaultSetting('language');
        }

        if (! file_exists(HOME . '/themes/' . $userSets['themes'])) {
            $userSets['themes'] = defaultSetting('themes');
        }

        Setting::setUserSettings($userSets);

        App::setLocale($userSets['language']);

        if ($user) {
            $user->checkAccess();
            $user->updatePrivate();
            $user->gettingBonus();
        }

        //dd($request->session()->all());

        /* Установка сессионных переменных */
        /*if (empty($_SESSION['hits'])) {
            $_SESSION['hits'] = 0;
        }*/
    }
}
