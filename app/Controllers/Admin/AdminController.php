<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Log;
use App\Models\User;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use Illuminate\Database\Capsule\Manager as DB;

class AdminController extends BaseController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (! isAdmin()) {
            abort(403, __('errors.forbidden'));
        }

        Log::query()->create([
            'user_id'    => getUser('id'),
            'request'    => request()->getRequestUri(),
            'referer'    => server('HTTP_REFERER'),
            'ip'         => getIp(),
            'brow'       => getBrowser(),
            'created_at' => SITETIME,
        ]);
    }

    /**
     * Главная страница
     *
     * @return string
     */
    public function main(): string
    {
        $existBoss = User::query()
            ->where('level', User::BOSS)
            ->count();

        return view('admin/index', compact('existBoss'));
    }

    /**
     * Проверка обновлений
     *
     * @param PhinxApplication $app
     *
     * @return string
     */
    public function upgrade(PhinxApplication $app): string
    {
        $wrap = new TextWrapper($app);

        $app->setName('Rotor by Vantuz - https://visavi.net');
        $app->setVersion(VERSION);

        $wrap->setOption('configuration', BASEDIR.'/app/migration.php');
        $wrap->setOption('parser', 'php');
        $wrap->setOption('environment', 'default');

        return view('admin/upgrade', compact('wrap'));
    }

    /**
     * Просмотр информации о PHP
     *
     * @return string
     */
    public function phpinfo(): string
    {
        if (! isAdmin(User::ADMIN)) {
            abort(403, __('errors.forbidden'));
        }

        $iniInfo = null;
        $gdInfo  = null;

        if (function_exists('ini_get_all')) {
            $iniInfo = ini_get_all();
        }

        if ($gdInfo = gd_info()) {
            $gdInfo = parseVersion($gdInfo['GD Version']);
        }

        return view('admin/phpinfo', compact('iniInfo', 'gdInfo'));
    }
}
