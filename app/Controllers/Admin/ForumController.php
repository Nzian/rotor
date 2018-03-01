<?php

namespace App\Controllers\Admin;

use App\Classes\Request;
use App\Classes\Validator;
use App\Models\Forum;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;

class ForumController extends AdminController
{
    /**
     * Главная страница
     */
    public function index()
    {
        $forums = Forum::query()
            ->where('parent_id', 0)
            ->with('lastTopic.lastPost.user')
            ->with('children')
            ->orderBy('sort')
            ->get();

        if ($forums->isEmpty()) {
            abort('default', 'Разделы форума еще не созданы!');
        }

        return view('admin/forum/index', compact('forums'));
    }

    /**
     * Создание раздела
     */
    public function create()
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, 'Доступ запрещен!');
        }

        $token = check(Request::input('token'));
        $title = check(Request::input('title'));

        $validator = new Validator();
        $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!')
            ->length($title, 5, 50, ['title' => 'Слишком длинное или короткое название раздела!']);

        if ($validator->isValid()) {

            $max = Forum::query()->max('sort') + 1;

            $forum = Forum::query()->create([
                'title' => $title,
                'sort'  => $max,
            ]);

            setFlash('success', 'Новый раздел успешно добавлен!');
            redirect('/admin/forum/edit/' . $forum->id);
        } else {
            setInput(Request::all());
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/forum');
    }

    /**
     * Редактирование форума
     */
    public function edit($id)
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, 'Доступ запрещен!');
        }

        $forum = Forum::query()->with('children')->find($id);

        if (! $forum) {
            abort(404, 'Данного раздела не существует!');
        }

        $forums = Forum::query()
            ->where('parent_id', 0)
            ->orderBy('sort')
            ->get();

        if (Request::isMethod('post')) {
            $token       = check(Request::input('token'));
            $parent      = int(Request::input('parent'));
            $title       = check(Request::input('title'));
            $description = check(Request::input('description'));
            $sort        = check(Request::input('sort'));
            $closed      = empty(Request::input('closed')) ? 0 : 1;

            $validator = new Validator();
            $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!')
                ->length($title, 5, 50, ['title' => 'Слишком длинное или короткое название раздела!'])
                ->length($description, 0, 100, ['description' => 'Слишком длинное описания раздела!'])
                ->notEqual($parent, $forum->id, ['parent' => 'Недопустимый выбор родительского раздела!']);


            if (! empty($parent) && $forum->children->isNotEmpty()) {
                $validator->addError(['parent' => 'Текущий раздел имеет подфорумы!']);
            }

            if ($validator->isValid()) {

                $forum->update([
                    'parent_id'   => $parent,
                    'title'       => $title,
                    'description' => $description,
                    'sort'        => $sort,
                    'closed'      => $closed,
                ]);

                setFlash('success', 'Раздел успешно отредактирован!');
                redirect('/admin/forum');
            } else {
                setInput(Request::all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/forum/edit', compact('forums', 'forum'));
    }

    /**
     * Удаление раздела
     */
    public function delete($id)
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, 'Доступ запрещен!');
        }

        $forum = Forum::query()->with('children')->find($id);

        if (! $forum) {
            abort(404, 'Данного раздела не существует!');
        }

        $token = check(Request::input('token'));

        $validator = new Validator();
        $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!')
            ->true($forum->children->isEmpty(), 'Удаление невозможно! Данный раздел имеет подфорумы!');

        $topic = Topic::query()->where('forum_id', $forum->id)->first();
        if ($topic) {
            $validator->addError('Удаление невозможно! В данном разделе имеются темы!');
        }

        if ($validator->isValid()) {

            $forum->delete();

            setFlash('success', 'Раздел успешно удален!');
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/forum');
    }

    /**
     * Пересчет сообщений
     */
    public function restatement()
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, 'Доступ запрещен!');
        }

        $token = check(Request::input('token'));

        if ($token == $_SESSION['token']) {

            restatement('forum');

            setFlash('success', 'Сообщения успешно пересчитаны!');
        } else {
            setFlash('danger', 'Ошибка! Неверный идентификатор сессии, повторите действие!');
        }

        redirect('/admin/forum');
    }

    /**
     * Просмотр тем раздела
     */
    public function forum($id)
    {
        $forum = Forum::query()->with('parent', 'children.lastTopic.lastPost.user')->find($id);

        if (! $forum) {
            abort(404, 'Данного раздела не существует!');
        }

        $total = Topic::query()->where('forum_id', $forum->id)->count();

        $page = paginate(setting('forumtem'), $total);

        $topics = Topic::query()
            ->where('forum_id', $forum->id)
            ->orderBy('locked', 'desc')
            ->orderBy('updated_at', 'desc')
            ->offset($page['offset'])
            ->limit($page['limit'])
            ->with('lastPost.user')
            ->get();

        return view('admin/forum/forum', compact('forum', 'topics', 'page'));
    }

    /**
     * Редактирование темы
     */
    public function editTopic($id)
    {
        $topic = Topic::query()->find($id);

        if (! $topic) {
            abort(404, 'Данной темы не существует!');
        }

        if (Request::isMethod('post')) {

            $token = check(Request::input('token'));
            $title       = check(Request::input('title'));
            $note       = check(Request::input('note'));
            $moderators = check(Request::input('moderators'));
            $locked     = empty(Request::input('locked')) ? 0 : 1;
            $closed     = empty(Request::input('closed')) ? 0 : 1;

            $validator = new Validator();
            $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!')
                ->length($title, 5, 50, ['title' => 'Слишком длинное или короткое название темы!'])
                ->length($note, 0, 250, ['note' => 'Слишком длинное объявление!']);

            if ($validator->isValid()) {

                $moderators = implode(',', preg_split('/[\s]*[,][\s]*/', $moderators));

                $topic->update([
                    'title'      => $title,
                    'note'       => $note,
                    'moderators' => $moderators,
                    'locked'     => $locked,
                    'closed'     => $closed,
                ]);

                setFlash('success', 'Тема успешно отредактирована!');
                redirect('/admin/forum/' . $topic->forum_id);
            } else {
                setInput(Request::all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/forum/edit_topic', compact('topic'));
    }

    /**
     * Перенос темы
     */
    public function moveTopic($id)
    {
        $topic = Topic::query()->find($id);

        if (! $topic) {
            abort(404, 'Данной темы не существует!');
        }

        if (Request::isMethod('post')) {
            $token = check(Request::input('token'));
            $fid   = int(Request::input('fid'));

            $validator = new Validator();
            $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!');

            $forum = Forum::query()->find($fid);

            if ($forum) {
                if ($forum->closed) {
                    $validator->addError(['forum' => 'В закрытый раздел запрещено перемещать темы!']);
                }

                if ($topic->forum_id == $forum->id) {
                    $validator->addError(['forum' => 'Нельзя переносить тему в этот же раздел!']);
                }
            } else {
                $validator->addError(['forum' => 'Выбранного раздела не существует!']);
            }

            if ($validator->isValid()) {

                $oldForumId = $topic->forum_id;

                $topic->update([
                    'forum_id' => $forum->id,
                ]);

                // Ищем последние темы в форумах для обновления списка последних тем
                $newTopic = Topic::query()->where('forum_id', $forum->id)->orderBy('updated_at', 'desc')->first();
                $topic->forum()->update([
                    'last_topic_id' => $newTopic ? $newTopic->id : 0,
                    'topics'        => DB::raw('topics + 1'),
                    'posts'         => DB::raw('posts + ' . $topic->posts),
                ]);

                $oldTopic = Topic::query()->where('forum_id', $oldForumId)->orderBy('updated_at', 'desc')->first();
                Forum::query()->where('id', $oldForumId)->update([
                    'last_topic_id' => $oldTopic ? $oldTopic->id : 0,
                    'topics'        => $oldTopic ? DB::raw('topics - 1') : 0,
                    'posts'         => $oldTopic ? DB::raw('posts - ' . $oldTopic->posts) : 0,
                ]);

                setFlash('success', 'Тема успешно перенесена!');
                //redirect('/admin/forum/' . $topic->forum_id);
            } else {
                setInput(Request::all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $forums = Forum::query()
            ->where('parent_id', 0)
            ->with('children')
            ->orderBy('sort')
            ->get();

        return view('admin/forum/move_topic', compact('forums', 'topic'));
    }
}
