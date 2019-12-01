<?php

use App\Commands\AppConfigure;
use App\Commands\AppState;
use App\Commands\CacheClear;
use App\Commands\ConfigClear;
use App\Commands\KeyGenerate;
use App\Commands\RouteClear;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

ob_start();
define('DIR', dirname(__DIR__, 2));
include_once DIR . '/app/bootstrap.php';

$request      = request();
$phpVersion   = '7.2.0';
$mysqlVersion = '5.5.3';

$app  = new Phinx\Console\PhinxApplication();
$wrap = new Phinx\Wrapper\TextWrapper($app);

$app->setName('Rotor by Vantuz - https://visavi.net');
$app->setVersion(VERSION);

$wrap->setOption('configuration', APP . '/migration.php');
$wrap->setOption('parser', 'php');
$wrap->setOption('environment', 'default');

$lang = check($request->input('lang', 'ru'));
Lang::setLocale($lang);

$languages = array_map('basename', glob(RESOURCES . '/lang/*', GLOB_ONLYDIR));

$keys = [
    'APP_ENV',
    'APP_NEW',
    'APP_DEBUG',
    'DB_DRIVER',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_ENGINE',
    'DB_CHARSET',
    'DB_COLLATION',
    'SITE_ADMIN',
    'SITE_EMAIL',
    'SITE_URL',
];

$errors = [];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>
        <?= config('APP_NEW') ? __('install.install') : __('install.update') ?> Rotor
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="image_src" href="/assets/img/images/icon.png">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="/themes/default/css/style.css">
</head>
<body>

<div class="cs" id="up">
    <a href="/"><img src="/assets/img/images/logo.png" alt=""></a>
</div>
<div class="site">
    <?php if (! $request->has('act')): ?>
        <form method="get">
            <label for="language">Выберите язык - Select language:</label>
            <div class="form-inline mb-3">
                <select class="form-control" name="lang" id="language">
                    <?php foreach ($languages as $language): ?>
                    <?php $selected = ($language === $lang) ? ' selected' : ''; ?>
                    <option value="<?= $language ?>"<?= $selected ?>><?= $language ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-primary"><?= __('main.select') ?></button>
            </div>
        </form>

        <h1><?= __('install.step1') ?></h1>

        <div class="alert alert-info">
            <?= __('install.debug') ?>
        </div>

        <div class="alert alert-info">
            <?= __('install.app_new') ?>
        </div>

        <p><?= __('install.env') ?></p>

        <?php foreach ($keys as $key): ?>
            <b><?= $key ?></b> - <?= trim(var_export(config($key), true), "'") ?><br>
        <?php endforeach; ?>
        <br>
        <p><?= __('install.app_key') ?></p>

        <p><?= __('install.requirements', ['php' => $phpVersion, 'mysql' => $mysqlVersion]) ?></p>

        <p style="font-size: 15px; font-weight: bold"><?= __('install.check_requirements') ?></p>

        <?php $errors['critical']['php'] = version_compare(PHP_VERSION, $phpVersion) >= 0 ?>
        <i class="fas fa-check-circle <?= $errors['critical']['php'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        PHP: <b><?= parseVersion(PHP_VERSION) ?></b><br>

        <?php $errors['critical']['pdo_mysql'] = extension_loaded('pdo_mysql') ?>
        <i class="fas fa-check-circle <?= $errors['critical']['pdo_mysql'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        <?php $version = strtok(getModuleSetting('pdo_mysql', ['Client API version', 'PDO Driver for MySQL, client library version']), '-'); ?>
        PDO-MySQL: <b><?= $version ?></b><br>

        <?php $errors['simple']['bcmath'] = extension_loaded('bcmath') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['bcmath'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        BCMath<br>

        <?php $errors['simple']['ctype'] = extension_loaded('ctype') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['ctype'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        Ctype<br>

        <?php $errors['simple']['json'] = extension_loaded('json') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['json'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        Json<br>

        <?php $errors['simple']['tokenizer'] = extension_loaded('tokenizer') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['tokenizer'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        Tokenizer<br>

        <?php $errors['simple']['mbstring'] = extension_loaded('mbstring') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['mbstring'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        <?php $version = getModuleSetting('mbstring', ['oniguruma version', 'Multibyte regex (oniguruma) version']); ?>
        MbString: <b><?= $version ?></b><br>

        <?php $errors['simple']['openssl'] = extension_loaded('openssl') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['openssl'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        <?php $version = getModuleSetting('openssl', ['OpenSSL Library Version', 'OpenSSL Header Version']); ?>
        OpenSSL: <b><?= $version ?></b><br>

        <?php $errors['simple']['xml'] = extension_loaded('xml') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['xml'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        <?php $version = getModuleSetting('xml', ['libxml2 Version']); ?>
        XML: <b><?= $version ?></b><br>

        <?php $errors['simple']['gd'] = extension_loaded('gd') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['gd'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        <?php $version = getModuleSetting('gd', ['GD headers Version', 'GD library Version']); ?>
        GD: <b><?= $version ?></b><br>

        <?php $errors['simple']['curl'] = extension_loaded('curl') ?>
        <i class="fas fa-check-circle <?= $errors['simple']['curl'] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
        <?php $version = getModuleSetting('curl', ['Curl Information', 'cURL Information']); ?>
        Curl: <b><?= $version ?></b><br>

        <?= __('install.ffmpeg') ?><br><br>

        <p style="font-size: 15px; font-weight: bold">Права доступа</p>

        <?php
        runCommand(new AppConfigure());

        $storage = glob(STORAGE . '/*', GLOB_ONLYDIR);
        $uploads = glob(UPLOADS . '/*', GLOB_ONLYDIR);
        $modules = [HOME . '/assets/modules'];

        $dirs = array_merge($storage, $uploads, $modules);
        ?>

        <?php foreach ($dirs as $dir): ?>
            <?php $chmod = decoct(fileperms($dir)) % 1000; ?>
            <?php $errors['chmod'][$dir] = is_writable($dir); ?>

            <i class="fas fa-check-circle <?= $errors['chmod'][$dir] ? 'text-success' : 'fa-times-circle text-danger' ?>"></i>
            <?= str_replace(DIR, '', $dir) ?>: <b><?= $chmod ?></b><br>
        <?php endforeach; ?>

        <br><?= __('install.chmod_views') ?><br><br>

        <?= __('install.chmod') ?><br>
        <?= __('install.errors') ?><br><br>

        <?php if (! in_array(false, $errors['critical'], true) && ! in_array(false, $errors['chmod'], true)): ?>
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> <?= __('install.continue') ?>
            </div>

            <?php if (! in_array(false, $errors['simple'], true)): ?>
                <?= __('install.requirements_pass') ?><br><br>
            <?php else: ?>
                <div class="alert alert-warning"><?= __('install.requirements_warning') ?><br>
                    <?= __('install.requirements_not_pass') ?><br>
                    <?= __('install.continue_restrict') ?>
                </div>
            <?php endif; ?>

            <a style="font-size: 18px" href="?act=status&amp;lang=<?= $lang ?>"><?= __('install.check_status') ?></a>
            (<?= __('install.performed') ?> <?= config('APP_NEW') ? __('install.install') : __('install.update') ?>)
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fa fa-times-circle"></i> <?= __('install.requirements_failed') ?><br>
                <?= __('install.resolve_errors') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (config('APP_NEW')): ?>
        <?php if ($request->input('act') === 'status'): ?>
            <h1><?= __('install.step2_install') ?></h1>

            <?= nl2br($wrap->getStatus()) ?>

            <p><a style="font-size: 18px" href="?act=migrate&amp;lang=<?= $lang ?>"><?= __('install.migrations') ?></a></p>

        <?php elseif ($request->input('act') === 'migrate'): ?>
            <h1><?= __('install.step3_install') ?></h1>

            <?= nl2br($wrap->getMigrate()) ?>

            <p><a style="font-size: 18px" href="?act=seed&amp;lang=<?= $lang ?>"><?= __('install.seeds') ?></a></p>

        <?php elseif ($request->input('act') === 'seed'): ?>

            <h1><?= __('install.step4_install') ?>)</h1>

            <?= nl2br($wrap->getSeed()) ?>

            <p><a style="font-size: 18px" href="?act=account&amp;lang=<?= $lang ?>"><?= __('install.create_admin') ?></a></p>
        <?php elseif ($request->input('act') === 'account'): ?>

            <h1><?= __('install.step5_install') ?></h1>

            <?= __('install.create_admin_info') ?><br>
            <?= __('install.create_admin_errors') ?><br>
            <?= __('install.delete_install') ?><br><br>

            <?php
                $login     = check($request->input('login'));
                $password  = check($request->input('password'));
                $password2 = check($request->input('password2'));
                $email     = strtolower(check($request->input('email')));
            ?>

            <?php if ($request->isMethod('post')): ?>

                <?php
                $length = strlen($login);
                if ($length <= 20 && $length >= 3) {
                if (preg_match('|^[a-z0-9\-]+$|i', $login)) {
                if ($password === $password2) {
                if (preg_match('#^([a-z0-9_\-\.])+\@([a-z0-9_\-\.])+(\.([a-z0-9])+)+$#', $email)) {

                // Проверка логина на существование
                $checkLogin = User::query()->where('login', $login)->count();
                if (! $checkLogin) {

                // Проверка email на существование
                $checkMail = User::query()->where('email', $email)->count();
                if (! $checkMail) {

                    $user = User::query()->create([
                        'login'      => $login,
                        'password'   => password_hash($password, PASSWORD_BCRYPT),
                        'email'      => $email,
                        'level'      => 'boss',
                        'gender'     => 'male',
                        'themes'     => 0,
                        'point'      => 500,
                        'money'      => 100000,
                        'status'     => 'Boss',
                        'created_at' => SITETIME,
                    ]);

                    // -------------- Приват ---------------//
                    $text = __('install.text_message', ['login' => $login]);
                    $user->sendMessage(null, $text);

                    // -------------- Новость ---------------//
                    $textnews = __('install.text_news');

                    $news = News::query()->create([
                        'title'      => __('install.welcome'),
                        'text'       => $textnews,
                        'user_id'    => $user->id,
                        'created_at' => SITETIME,
                    ]);

                    redirect('?act=finish&lang=' . $lang);

                } else {echo '<div class="alert alert-danger">' . __('users.email_already_exists') . '</div>';}
                } else {echo '<div class="alert alert-danger">' . __('users.login_already_exists') . '</div>';}
                } else {echo '<div class="alert alert-danger">' . __('validator.email') . '</div>';}
                } else {echo '<div class="alert alert-danger">' . __('users.passwords_different') . '</div>';}
                } else {echo '<div class="alert alert-danger">' . __('validator.login') . '</div>';}
                } else {echo '<div class="alert alert-danger">' . __('users.login_requirements') . '</div>';}
                ?>
            <?php endif; ?>

            <div class="form">
               <form method="post" action="?act=account&amp;lang=<?= $lang ?>">
                    <?= __('users.login') ?> (max20):<br>
                    <input class="form-control" name="login" maxlength="20" value="<?= $login ?>"><br>
                    <?= __('users.password') ?> (max20):<br>
                    <input class="form-control" name="password" type="password" maxlength="50"><br>
                    <?= __('users.confirm_password') ?>:<br>
                    <input class="form-control" name="password2" type="password" maxlength="50"><br>
                    <?= __('users.email') ?>:<br>
                    <input class="form-control" name="email" maxlength="100" value="<?= $email ?>"><br>
                   <button class="btn btn-primary"><?= __('main.create') ?></button>
                </form>
            </div><br>
            <?= __('users.login_requirements') ?><br><br>

        <?php elseif ($request->input('act') === 'finish'): ?>

            <h1><?= __('install.install_completed') ?></h1>
            <p>
                <?= __('install.install') ?><br><br>
                <a href="/"><?= __('install.main_page') ?></a><br>
            </p>

            <?php
            runCommand(new AppState());
            runCommand(new KeyGenerate());
            runCommand(new CacheClear());
            runCommand(new RouteClear());
            runCommand(new ConfigClear());
            ?>
        <?php endif; ?>

    <?php else: ?>

        <?php if ($request->input('act') === 'status'): ?>
            <h1><?= __('install.step2_update') ?></h1>
            <?= nl2br($wrap->getStatus()) ?>
            <a style="font-size: 18px" href="?act=migrate&amp;lang=<?= $lang ?>"><?= __('install.migrations') ?></a>

        <?php elseif ($request->input('act') === 'rollback'): ?>
            <?= nl2br($wrap->getRollback()) ?>

        <?php elseif ($request->input('act') === 'migrate'): ?>

            <h1><?= __('install.update_completed') ?></h1>
            <?= nl2br($wrap->getMigrate()) ?>

            <p>
                <?= __('install.success_update') ?><br><br>
                <a href="/"><?= __('install.main_page') ?></a><br>
            </p>
            <?php
            runCommand(new CacheClear());
            runCommand(new RouteClear());
            runCommand(new ConfigClear());
            ?>
        <?php endif; ?>
    <?php endif; ?>
 </div>
</body>
</html>
<?php

/**
 * Parse PHP modules
 *
 * @return array
 */
function parsePHPModules() {
    ob_start();
    phpinfo(INFO_MODULES);
    $s = ob_get_clean();
    $s = strip_tags($s, '<h2><th><td>');
    $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $s);
    $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $s);
    $vTmp = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
    $vModules = [];
    $iMax = count($vTmp);

    for ($i = 1; $i < $iMax; $i++) {
        if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n", $vTmp[$i + 1]);
            foreach ($vTmp2 AS $vOne) {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";
                if (preg_match($vPat3, $vOne, $vMat)) {
                    $vModules[$vName][trim($vMat[1])] = [trim($vMat[2]), trim($vMat[3])];
                } elseif (preg_match($vPat2, $vOne, $vMat)) {
                    $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
                }
            }
        }
    }

    return $vModules;
}

/**
 * Get PHP module setting
 *
 * @param string $pModuleName
 * @param array  $pSettings
 *
 * @return string
 */
function getModuleSetting(string $pModuleName, array $pSettings)
{
    $vModules = parsePHPModules();

    foreach ($pSettings as $pSetting) {
        if (isset($vModules[$pModuleName][$pSetting])) {
            return $vModules[$pModuleName][$pSetting];
        }
    }

    return __('main.undefined');
}
