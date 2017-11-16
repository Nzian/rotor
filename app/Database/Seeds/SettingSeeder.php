-<?php

use Phinx\Seed\AbstractSeed;

class SettingSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $data = [
            ['name'  => 'addbansend', 'value' => 1],
            ['name'  => 'addofferspoint', 'value' => 50],
            ['name'  => 'advertpoint', 'value' => 2000],
            ['name'  => 'allowextload', 'value' => 'zip,rar,txt,jpg,jpeg,gif,png,mp3,mp4,3gp,wav,mmf,mid,midi,sis,jar,jad'],
            ['name'  => 'allvotes', 'value' => 10],
            ['name'  => 'avtorlist', 'value' => 10],
            ['name'  => 'banlist', 'value' => 10],
            ['name'  => 'blacklist', 'value' => 10],
            ['name'  => 'blogcomm', 'value' => 10],
            ['name'  => 'blogexprated', 'value' => 72],
            ['name'  => 'blogexpread', 'value' => 72],
            ['name'  => 'blogpost', 'value' => 10],
            ['name'  => 'blogvotepoint', 'value' => 50],
            ['name'  => 'bonusmoney', 'value' => 500],
            ['name'  => 'bookadds', 'value' => 1],
            ['name'  => 'bookpost', 'value' => 10],
            ['name'  => 'bookscores', 'value' => 0],
            ['name'  => 'buildversion', 'value' => 0],
            ['name'  => 'captcha_angle', 'value' => 20],
            ['name'  => 'captcha_distortion', 'value' => 1],
            ['name'  => 'captcha_interpolation', 'value' => 1],
            ['name'  => 'captcha_maxlength', 'value' => 5],
            ['name'  => 'captcha_offset', 'value' => 5],
            ['name'  => 'captcha_spaces', 'value' => 0],
            ['name'  => 'captcha_symbols', 'value' => '0123456789'],
            ['name'  => 'chatpost', 'value' => 10],
            ['name'  => 'closedsite', 'value' => 0],
            ['name'  => 'contactlist', 'value' => 10],
            ['name'  => 'copy', 'value' => '© RotorCMS'],
            ['name'  => 'copyfoto', 'value' => 1],
            ['name'  => 'description', 'value' => 'Краткое описание вашего сайта'],
            ['name'  => 'doslimit', 'value' => 0],
            ['name'  => 'downcomm', 'value' => 10],
            ['name'  => 'downlist', 'value' => 10],
            ['name'  => 'downupload', 'value' => 1],
            ['name'  => 'editforumpoint', 'value' => 500],
            ['name'  => 'editratingpoint', 'value' => 150],
            ['name'  => 'editstatusmoney', 'value' => 3000],
            ['name'  => 'editstatuspoint', 'value' => 1000],
            ['name'  => 'errorlog', 'value' => 1],
            ['name'  => 'filesize', 'value' => 5242880],
            ['name'  => 'fileupfoto', 'value' => 3000],
            ['name'  => 'fileupload', 'value' => 33554432],
            ['name'  => 'floodstime', 'value' => 30],
            ['name'  => 'forumextload', 'value' => 'zip,rar,txt,jpg,jpeg,gif,png,mp3,mp4,3gp,wav,pdf'],
            ['name'  => 'forumloadpoints', 'value' => 150],
            ['name'  => 'forumloadsize', 'value' => 1048576],
            ['name'  => 'forumpost', 'value' => 10],
            ['name'  => 'forumtem', 'value' => 10],
            ['name'  => 'forumtextlength', 'value' => 3000],
            ['name'  => 'fotolist', 'value' => 5],
            ['name'  => 'guestsuser', 'value' => 'Гость'],
            ['name'  => 'guesttextlength', 'value' => 1000],
            ['name'  => 'ignorlist', 'value' => 10],
            ['name'  => 'incount', 'value' => 5],
            ['name'  => 'invite', 'value' => 0],
            ['name'  => 'ipbanlist', 'value' => 10],
            ['name'  => 'keywords', 'value' => 'Ключевые слова вашего сайта'],
            ['name'  => 'lastnews', 'value' => 5],
            ['name'  => 'lastusers', 'value' => 100],
            ['name'  => 'lifelist', 'value' => 10],
            ['name'  => 'limitcontact', 'value' => 50],
            ['name'  => 'limitignore', 'value' => 50],
            ['name'  => 'limitmail', 'value' => 300],
            ['name'  => 'limitoutmail', 'value' => 100],
            ['name'  => 'listbanhist', 'value' => 10],
            ['name'  => 'listinvite', 'value' => 20],
            ['name'  => 'listtransfers', 'value' => 10],
            ['name'  => 'loginauthlist', 'value' => 10],
            ['name'  => 'loglist', 'value' => 10],
            ['name'  => 'logos', 'value' => 'Сайт на движке RotorCMS'],
            ['name'  => 'logotip', 'value' => '/assets/img/images/logo.png'],
            ['name'  => 'maxbantime', 'value' => 43200],
            ['name'  => 'maxblogpost', 'value' => 50000],
            ['name'  => 'moneyname', 'value' => 'рубль,рубля,рублей'],
            ['name'  => 'nocheck', 'value' => 'txt,dat,gif,jpg,jpeg,png,zip'],
            ['name'  => 'onlinelist', 'value' => 10],
            ['name'  => 'onlines', 'value' => 1],
            ['name'  => 'openreg', 'value' => 1],
            ['name'  => 'performance', 'value' => 1],
            ['name'  => 'photoexprated', 'value' => 72],
            ['name'  => 'photogroup', 'value' => 10],
            ['name'  => 'postcommoffers', 'value' => 10],
            ['name'  => 'postgallery', 'value' => 10],
            ['name'  => 'postnews', 'value' => 10],
            ['name'  => 'postoffers', 'value' => 10],
            ['name'  => 'previewsize', 'value' => 150],
            ['name'  => 'privatpost', 'value' => 10],
            ['name'  => 'privatprotect', 'value' => 50],
            ['name'  => 'proxy', 'value' => ''],
            ['name'  => 'ratinglist', 'value' => 20],
            ['name'  => 'registermoney', 'value' => 1000],
            ['name'  => 'regkeys', 'value' => 1],
            ['name'  => 'reglist', 'value' => 10],
            ['name'  => 'rekuseroptprice', 'value' => 100],
            ['name'  => 'rekuserpost', 'value' => 10],
            ['name'  => 'rekuserprice', 'value' => 1000],
            ['name'  => 'rekusershow', 'value' => 1],
            ['name'  => 'rekusertime', 'value' => 12],
            ['name'  => 'rekusertotal', 'value' => 10],
            ['name'  => 'scorename', 'value' => 'балл,балла,баллов'],
            ['name'  => 'screensize', 'value' => 500],
            ['name'  => 'screenupload', 'value' => 2097152],
            ['name'  => 'screenupsize', 'value' => 5000],
            ['name'  => 'sendmailpacket', 'value' => 3],
            ['name'  => 'sendmoneypoint', 'value' => 150],
            ['name'  => 'sendprivatmailday', 'value' => 3],
            ['name'  => 'showuser', 'value' => 10],
            ['name'  => 'smilelist', 'value' => 10],
            ['name'  => 'smilemaxsize', 'value' => 10240],
            ['name'  => 'smilemaxweight', 'value' => 100],
            ['name'  => 'smileminweight', 'value' => 16],
            ['name'  => 'spamlist', 'value' => 10],
            ['name'  => 'statusdef', 'value' => 'Дух'],
            ['name'  => 'statusname', 'value' => 'Владелец,Админ,Модератор,Менеджер,Редактор,Пользователь,Ожидающий,Забаненный'],
            ['name'  => 'themes', 'value' => 'default'],
            ['name'  => 'timeonline', 'value' => 600],
            ['name'  => 'timezone', 'value' => 'Europe/Moscow'],
            ['name'  => 'title', 'value' => 'RotorCMS'],
            ['name'  => 'touchthemes', 'value' => 0],
            ['name'  => 'userlist', 'value' => 10],
            ['name'  => 'usersearch', 'value' => 30],
            ['name'  => 'wallmaxpost', 'value' => 100],
            ['name'  => 'wallpost', 'value' => 10],
            ['name'  => 'webthemes', 'value' => 'motor'],
            ['name'  => 'ziplist', 'value' => 20],
        ];

        $this->execute('TRUNCATE setting');

        $table = $this->table('setting');
        $table->insert($data)->save();
    }
}
