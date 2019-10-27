<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Classes\Validator;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\File;
use App\Models\Flood;
use App\Models\Reader;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    /**
     * Главная страница
     *
     * @return string
     */
    public function index(): string
    {
        $categories = Category::query()
            ->where('parent_id', 0)
            ->orderBy('sort')
            ->with('children', 'new', 'children.new')
            ->get();

        if ($categories->isEmpty()) {
            abort('default', __('blogs.categories_not_created'));
        }

        return view('blogs/index', compact('categories'));
    }

    /**
     * Список блогов
     *
     * @param int $id
     * @return string
     */
    public function blog(int $id): string
    {
        $category = Category::query()->with('parent')->find($id);

        if (! $category) {
            abort(404, __('blogs.category_not_exist'));
        }

        $total = Blog::query()->where('category_id', $id)->count();

        $page = paginate(setting('blogpost'), $total);

        $blogs = Blog::query()
            ->where('category_id', $id)
            ->orderBy('created_at', 'desc')
            ->offset($page->offset)
            ->limit($page->limit)
            ->with('user')
            ->get();

        return view('blogs/blog', compact('blogs', 'category', 'page'));
    }

    /**
     * Просмотр статьи
     *
     * @param int $id
     * @return string
     */
    public function view(int $id): string
    {
        $blog = Blog::query()
            ->select('blogs.*', 'pollings.vote')
            ->where('blogs.id', $id)
            ->leftJoin('pollings', static function (JoinClause $join) {
                $join->on('blogs.id', 'pollings.relate_id')
                    ->where('pollings.relate_type', Blog::class)
                    ->where('pollings.user_id', getUser('id'));
            })
            ->with('category.parent')
            ->first();

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        $reader = Reader::query()
            ->where('relate_type', Blog::class)
            ->where('relate_id', $blog->id)
            ->where('ip', getIp())
            ->first();

        if (! $reader) {
            Reader::query()->create([
                'relate_type' => Blog::class,
                'relate_id'   => $blog->id,
                'ip'          => getIp(),
                'created_at'  => SITETIME,
            ]);

            $blog->increment('visits');
        }

        $blog->text = bbCode($blog->text) . '<br>';
        $tagsList = preg_split('/[\s]*[,][\s]*/', $blog->tags);

        $tags = '';
        foreach ($tagsList as $key => $value) {
            $comma = empty($key) ? '' : ', ';
            $tags .= $comma . '<a href="/blogs/tags/' . urlencode($value) . '">' . $value . '</a>';
        }

        return view('blogs/view', compact('blog', 'tags'));
    }

    /**
     * Редактирование статьи
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     * @return string
     */
    public function edit(int $id, Request $request, Validator $validator): string
    {
        if (! getUser()) {
            abort(403, __('main.not_authorized'));
        }

        /** @var Blog $blog */
        $blog = Blog::query()->find($id);

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        if ($blog->user_id !== getUser('id')) {
            abort('default', __('main.article_not_author'));
        }

        if ($request->isMethod('post')) {

            $token = check($request->input('token'));
            $cid   = int($request->input('cid'));
            $title = check($request->input('title'));
            $text  = check($request->input('text'));
            $tags  = check($request->input('tags'));

            /** @var Category $category */
            $category = Category::query()->find($cid);

            $validator
                ->equal($token, $_SESSION['token'], __('validator.token'))
                ->length($title, 5, 50, ['title' => __('validator.text')])
                ->length($text, 100, setting('maxblogpost'), ['text' => __('validator.text')])
                ->length($tags, 2, 50, ['tags' => __('blogs.article_error_tags')])
                ->notEmpty($category, ['cid' => __('blogs.category_not_exist')]);

            if ($category) {
                $validator->empty($category->closed, ['cid' => __('blogs.category_closed')]);
            }

            if ($validator->isValid()) {
                // Обновление счетчиков
                if ($blog->category_id !== $category->id) {
                    $category->increment('count_blogs');
                    Category::query()->where('id', $blog->category_id)->decrement('count_blogs');
                }

                $blog->update([
                    'category_id' => $category->id,
                    'title'       => $title,
                    'text'        => $text,
                    'tags'        => $tags,
                ]);

                clearCache(['statblog', 'recentblog']);
                setFlash('success', __('blogs.article_success_edited'));
                redirect('/articles/' . $blog->id);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $categories = Category::query()
            ->where('parent_id', 0)
            ->with('children')
            ->orderBy('sort')
            ->get();

        return view('blogs/edit', compact('blog', 'categories'));
    }

    /**
     * Просмотр по категориям
     *
     * @return string
     */
    public function authors(): string
    {
        $total = Blog::query()
            ->distinct()
            ->join('users', 'blogs.user_id', 'users.id')
            ->count('user_id');

        $page = paginate(setting('bloggroup'), $total);

        $blogs = Blog::query()
            ->select('user_id', 'login')
            ->selectRaw('count(*) as cnt, sum(count_comments) as count_comments')
            ->join('users', 'blogs.user_id', 'users.id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->groupBy('user_id')
            ->orderBy('cnt', 'desc')
            ->get();

        return view('blogs/authors', compact('blogs', 'page'));
    }

    /**
     * Создание статьи
     *
     * @param Request   $request
     * @param Validator $validator
     * @param Flood     $flood
     * @return string
     */
    public function create(Request $request, Validator $validator, Flood $flood): string
    {
        $cid = int($request->input('cid'));

        if (! isAdmin() && ! setting('blog_create')) {
            abort('default', __('main.articles_closed'));
        }

        if (! getUser()) {
            abort(403, __('main.not_authorized'));
        }

        $cats = Category::query()
            ->where('parent_id', 0)
            ->with('children')
            ->orderBy('sort')
            ->get();

        if (! $cats) {
            abort(404, __('blogs.categories_not_created'));
        }

        if ($request->isMethod('post')) {

            $token = check($request->input('token'));
            $title = check($request->input('title'));
            $text  = check($request->input('text'));
            $tags  = check($request->input('tags'));

            /** @var Category $category */
            $category = Category::query()->find($cid);

            $validator
                ->equal($token, $_SESSION['token'], __('validator.token'))
                ->length($title, 5, 50, ['title' => __('validator.text')])
                ->length($text, 100, setting('maxblogpost'), ['text' => __('validator.text')])
                ->length($tags, 2, 50, ['tags' => 'Слишком длинные или короткие метки статьи!'])
                ->false($flood->isFlood(), ['msg' => __('validator.flood', ['sec' => $flood->getPeriod()])])
                ->notEmpty($category, ['cid' => __('blogs.category_not_exist')]);

            if ($category) {
                $validator->empty($category->closed, ['cid' => __('blogs.category_closed')]);
            }

            if ($validator->isValid()) {
                $text = antimat($text);

                /** @var Blog $blog */
                $blog = Blog::query()->create([
                    'category_id' => $cid,
                    'user_id'     => getUser('id'),
                    'title'       => $title,
                    'text'        => $text,
                    'tags'        => $tags,
                    'created_at'  => SITETIME,
                ]);

                $category->increment('count_blogs');

                getUser()->increment('point', 5);
                getUser()->increment('money', 100);

                File::query()
                    ->where('relate_type', Blog::class)
                    ->where('relate_id', 0)
                    ->where('user_id', getUser('id'))
                    ->update(['relate_id' => $blog->id]);

                clearCache(['statblog', 'recentblog']);
                $flood->saveState();

                setFlash('success', __('blogs.article_success_created'));
                redirect('/articles/' . $blog->id);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $files = File::query()
            ->where('relate_type', Blog::class)
            ->where('relate_id', 0)
            ->where('user_id', getUser('id'))
            ->get();

        return view('blogs/create', compact('cats', 'cid', 'files'));
    }

    /**
     * Комментарии
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     * @param Flood     $flood
     * @return string
     */
    public function comments(int $id, Request $request, Validator $validator, Flood $flood): string
    {
        /** @var Blog $blog */
        $blog = Blog::query()->find($id);

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        if ($request->isMethod('post')) {

            $token = check($request->input('token'));
            $msg   = check($request->input('msg'));

            $validator
                ->true(getUser(), __('main.not_authorized'))
                ->equal($token, $_SESSION['token'], __('validator.token'))
                ->length($msg, 5, setting('comment_length'), ['msg' => __('validator.text')])
                ->false($flood->isFlood(), ['msg' => __('validator.flood', ['sec' => $flood->getPeriod()])]);

            if ($validator->isValid()) {
                $msg = antimat($msg);

                /** @var Comment $comment */
                $comment = Comment::query()->create([
                    'relate_type' => Blog::class,
                    'relate_id'   => $blog->id,
                    'text'        => $msg,
                    'user_id'     => getUser('id'),
                    'created_at'  => SITETIME,
                    'ip'          => getIp(),
                    'brow'        => getBrowser(),
                ]);

                $user = getUser();
                $user->increment('allcomments');
                $user->increment('point');
                $user->increment('money', 5);

                $blog->increment('count_comments');

                $flood->saveState();
                sendNotify($msg, '/articles/comment/' . $blog->id . '/' . $comment->id, $blog->title);

                setFlash('success', __('main.comment_added_success'));
                redirect('/articles/end/' . $blog->id);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $total = Comment::query()
            ->where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->count();

        $page = paginate(setting('blogcomm'), $total);

        $comments = Comment::query()
            ->where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->orderBy('created_at')
            ->offset($page->offset)
            ->limit($page->limit)
            ->get();

        return view('blogs/comments', compact('blog', 'comments', 'page'));
    }

    /**
     * Подготовка к редактированию комментария
     *
     * @param int       $id
     * @param int       $cid
     * @param Request   $request
     * @param Validator $validator
     * @return string
     */
    public function editComment(int $id, int $cid, Request $request, Validator $validator): string
    {
        $page = int($request->input('page', 1));

        /** @var Blog $blog */
        $blog = Blog::query()->find($id);

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        if (! getUser()) {
            abort(403, __('main.not_authorized'));
        }

        $comment = Comment::query()
            ->where('relate_type', Blog::class)
            ->where('id', $cid)
            ->where('user_id', getUser('id'))
            ->first();

        if (! $comment) {
            abort('default', __('main.comment_deleted'));
        }

        if ($comment->created_at + 600 < SITETIME) {
            abort('default', __('main.editing_impossible'));
        }

        if ($request->isMethod('post')) {
            $token = check($request->input('token'));
            $msg   = check($request->input('msg'));
            $page  = int($request->input('page', 1));

            $validator
                ->equal($token, $_SESSION['token'], __('validator.token'))
                ->length($msg, 5, setting('comment_length'), ['msg' => __('validator.text')]);

            if ($validator->isValid()) {
                $msg = antimat($msg);

                $comment->update([
                    'text' => $msg,
                ]);

                setFlash('success', __('main.comment_edited_success'));
                redirect('/articles/comments/' . $blog->id . '?page=' . $page);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('blogs/editcomment', compact('blog', 'comment', 'page'));
    }

    /**
     * Переадресация на последнюю страницу
     *
     * @param int $id
     * @return void
     */
    public function end(int $id): void
    {
        $blog = Blog::query()->find($id);

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        $total = Comment::query()
            ->where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->count();

        $end = ceil($total / setting('blogpost'));
        redirect('/articles/comments/' . $id . '?page=' . $end);
    }

    /**
     * Печать
     *
     * @param int $id
     * @return string
     */
    public function print(int $id): string
    {
        /** @var Blog $blog */
        $blog = Blog::query()->find($id);

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        return view('blogs/print', compact('blog'));
    }

    /**
     * RSS всех блогов
     *
     * @return string
     */
    public function rss(): string
    {
        $blogs = Blog::query()
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        if ($blogs->isEmpty()) {
            abort('default', 'Блоги не найдены!');
        }

        return view('blogs/rss', compact('blogs'));
    }

    /**
     * RSS комментариев к блогу
     *
     * @param int $id
     * @return string
     */
    public function rssComments(int $id): string
    {
        $blog = Blog::query()->where('id', $id)->with('lastComments')->first();

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        return view('blogs/rss_comments', compact('blog'));
    }

    /**
     * Вывод всех тегов
     *
     * @return string
     */
    public function tags(): string
    {
        if (@filemtime(STORAGE . '/temp/tagcloud.dat') < time() - 3600) {

            $allTags = Blog::query()
                ->select('tags')
                ->pluck('tags')
                ->all();

            $stingTag = implode(',', $allTags);
            $dumptags = preg_split('/[\s]*[,][\s]*/', $stingTag, -1, PREG_SPLIT_NO_EMPTY);
            $allTags  = array_count_values(array_map('utfLower', $dumptags));

            arsort($allTags);
            array_splice($allTags, 100);
            shuffleAssoc($allTags);

            file_put_contents(STORAGE . '/temp/tagcloud.dat', json_encode($allTags, JSON_UNESCAPED_UNICODE), LOCK_EX);
        }

        $tags = json_decode(file_get_contents(STORAGE . '/temp/tagcloud.dat'), true);
        $max = max($tags);
        $min = min($tags);

        return view('blogs/tags', compact('tags', 'max', 'min'));
    }

    /**
     * Поиск по тегам
     *
     * @param string $tag
     * @return string
     */
    public function searchTag(string $tag): string
    {
        $tag = urldecode($tag);

        if (! isUtf($tag)) {
            $tag = winToUtf($tag);
        }

        if (utfStrlen($tag) < 2) {
            setFlash('danger', 'Необходимо не менее 2-х символов в запросе!');
            redirect('/blogs/tags');
        }

        if (
            empty($_SESSION['findresult']) ||
            empty($_SESSION['blogfind'])   ||
            $tag !== $_SESSION['blogfind']
        ) {
            $result = Blog::query()
                ->select('id')
                ->where('tags', 'like', '%'.$tag.'%')
                ->limit(500)
                ->pluck('id')
                ->all();

            $_SESSION['blogfind'] = $tag;
            $_SESSION['findresult'] = $result;
        }

        $total = count($_SESSION['findresult']);
        $page  = paginate(setting('blogpost'), $total);

        $blogs = Blog::query()
            ->select('blogs.*', 'categories.name')
            ->whereIn('blogs.id', $_SESSION['findresult'])
            ->join('categories', 'blogs.category_id', 'categories.id')
            ->orderBy('created_at', 'desc')
            ->offset($page->offset)
            ->limit($page->limit)
            ->with('user')
            ->get();

        return view('blogs/tags_search', compact('blogs', 'tag', 'page'));
    }

    /**
     * Новые статьи
     *
     * @return string
     */
    public function newArticles(): string
    {
        $total = Blog::query()->count();

        if ($total > 500) {
            $total = 500;
        }
        $page = paginate(setting('blogpost'), $total);

        $blogs = Blog::query()
            ->orderBy('created_at', 'desc')
            ->offset($page->offset)
            ->limit($page->limit)
            ->with('user')
            ->get();

        return view('blogs/new_articles', compact('blogs', 'page'));
    }

    /**
     * Новые комментарии
     *
     * @return string
     */
    public function newComments(): string
    {
        $total = Comment::query()->where('relate_type', Blog::class)->count();

        if ($total > 500) {
            $total = 500;
        }
        $page = paginate(setting('blogpost'), $total);

        $comments = Comment::query()
            ->select('comments.*', 'title', 'count_comments')
            ->where('relate_type', Blog::class)
            ->leftJoin('blogs', 'comments.relate_id', 'blogs.id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy('comments.created_at', 'desc')
            ->with('user')
            ->get();

        return view('blogs/new_comments', compact('comments', 'page'));
    }

    /**
     * Статьи пользователя
     *
     * @param Request $request
     * @return string
     */
    public function userArticles(Request $request): string
    {
        $login = check($request->input('user', getUser('login')));
        $user  = getUserByLogin($login);

        if (! $user) {
            abort(404, __('validator.user'));
        }

        $total = Blog::query()->where('user_id', $user->id)->count();
        $page  = paginate(setting('blogpost'), $total);

        $blogs = Blog::query()->where('user_id', $user->id)
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blogs/active_articles', compact('blogs', 'user', 'page'));
    }

    /**
     * Комментарии пользователя
     *
     * @param Request $request
     * @return string
     */
    public function userComments(Request $request): string
    {
        $login = check($request->input('user', getUser('login')));
        $user  = getUserByLogin($login);

        if (! $user) {
            abort(404, __('validator.user'));
        }

        $total = Comment::query()
            ->where('relate_type', Blog::class)
            ->where('user_id', $user->id)
            ->count();
        $page = paginate(setting('blogpost'), $total);

        $comments = Comment::query()
            ->select('comments.*', 'title', 'count_comments')
            ->where('relate_type', Blog::class)
            ->where('comments.user_id', $user->id)
            ->leftJoin('blogs', 'comments.relate_id', 'blogs.id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy('comments.created_at', 'desc')
            ->with('user')
            ->get();

            return view('blogs/active_comments', compact('comments', 'user', 'page'));
    }

    /**
     * Переход к сообщению
     *
     * @param $id
     * @param $cid
     * @return void
     */
    public function viewComment(int $id, int $cid): void
    {
        /** @var Blog $blog */
        $blog = Blog::query()->find($id);

        if (! $blog) {
            abort(404, __('blogs.article_not_exist'));
        }

        $total = Comment::query()
            ->where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->where('id', '<=', $cid)
            ->orderBy('created_at')
            ->count();

        $end = ceil($total / setting('blogpost'));
        redirect('/articles/comments/' . $blog->id . '?page=' . $end . '#comment_' . $cid);
    }

    /**
     * Топ статей
     *
     * @param Request $request
     * @return string
     */
    public function top(Request $request): string
    {
        $sort = check($request->input('sort', 'visits'));

        switch ($sort) {
            case 'rated': $order = 'rating';
                break;
            case 'comments': $order = 'count_comments';
                break;
            default: $order = 'visits';
        }

        $total = Blog::query()->count();
        $page = paginate(setting('blogpost'), $total);

        $blogs = Blog::query()
            ->select('blogs.*', 'categories.name')
            ->leftJoin('categories', 'blogs.category_id', 'categories.id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy($order, 'desc')
            ->with('user')
            ->get();

        return view('blogs/top', compact('blogs', 'order', 'page'));
    }

    /**
     * Поиск
     *
     * @param Request $request
     * @return string
     */
    public function search(Request $request): ?string
    {
        $find  = check($request->input('find'));
        $type  = int($request->input('type'));
        $where = int($request->input('where'));

        if (! getUser()) {
            abort(403, __('main.not_authorized'));
        }

        if (empty($find)) {
            return view('blogs/search');
        }

        if (! isUtf($find)) {
            $find = winToUtf($find);
        }

        $strlen = utfStrlen($find);

        if ($strlen >= 3 && $strlen <= 50) {
            $findme = utfLower($find);
            $findmewords = explode(' ', $findme);

            $arrfind = [];
            foreach ($findmewords as $valfind) {
                if (utfStrlen($valfind) >= 3) {
                    $arrfind[] = $valfind;
                }
            }
            array_splice($arrfind, 3);

                $types = empty($type) ? 'AND' : 'OR';
                $wheres = empty($where) ? 'title' : 'text';

                $blogfind = ($types . $wheres . $find);

                // ----------------------------- Поиск в названии -------------------------------//
                if ($wheres === 'title') {

                    if ($type === 2) {
                        $arrfind[0] = $findme;
                    }
                    $search1 = isset($arrfind[1]) && $type !== 2 ? $types . " `title` LIKE '%" . $arrfind[1] . "%'" : '';
                    $search2 = isset($arrfind[2]) && $type !== 2 ? $types . " `title` LIKE '%" . $arrfind[2] . "%'" : '';

                    if (empty($_SESSION['blogfindres']) || $blogfind !== $_SESSION['blogfind']) {

                        $result = Blog::query()
                            ->select('id')
                            ->whereRaw("title like '%".$arrfind[0]."%'".$search1.$search2)
                            ->limit(500)
                            ->pluck('id')
                            ->all();

                        $_SESSION['blogfind'] = $blogfind;
                        $_SESSION['blogfindres'] = $result;
                    }

                    $total = count($_SESSION['blogfindres']);
                    $page  = paginate(setting('blogpost'), $total);

                    if ($total > 0) {
                        $blogs = Blog::query()
                            ->select('blogs.*', 'categories.name')
                            ->whereIn('blogs.id', $_SESSION['blogfindres'])
                            ->join('categories', 'blogs.category_id', 'categories.id')
                            ->orderBy('created_at', 'desc')
                            ->offset($page->offset)
                            ->limit($page->limit)
                            ->with('user')
                            ->get();

                        return view('blogs/search_title', compact('blogs', 'find', 'page'));
                    }

                    setInput($request->all());
                    setFlash('danger', __('main.empty_found'));
                    redirect('/blogs/search');
                }
                // --------------------------- Поиск в текте -------------------------------//
                if ($wheres === 'text') {

                    if ($type === 2) {
                        $arrfind[0] = $findme;
                    }
                    $search1 = isset($arrfind[1]) && $type !== 2 ? $types . " `text` LIKE '%" . $arrfind[1] . "%'" : '';
                    $search2 = isset($arrfind[2]) && $type !== 2 ? $types . " `text` LIKE '%" . $arrfind[2] . "%'" : '';

                    if (empty($_SESSION['blogfindres']) || $blogfind !== $_SESSION['blogfind']) {

                        $result = Blog::query()
                            ->select('id')
                            ->whereRaw("text like '%".$arrfind[0]."%'".$search1.$search2)
                            ->limit(500)
                            ->pluck('id')
                            ->all();

                        $_SESSION['blogfind'] = $blogfind;
                        $_SESSION['blogfindres'] = $result;
                    }

                    $total = count($_SESSION['blogfindres']);
                    $page  = paginate(setting('blogpost'), $total);

                    if ($total > 0) {
                        $blogs = Blog::query()
                            ->select('blogs.*', 'categories.name')
                            ->whereIn('blogs.id', $_SESSION['blogfindres'])
                            ->join('categories', 'blogs.category_id', 'categories.id')
                            ->orderBy('created_at', 'desc')
                            ->offset($page->offset)
                            ->limit($page->limit)
                            ->with('user')
                            ->get();

                        return view('blogs/search_text', compact('blogs', 'find', 'page'));
                    }

                    setInput($request->all());
                    setFlash('danger', __('main.empty_found'));
                    redirect('/blogs/search');
                }
        } else {
            setInput($request->all());
            setFlash('danger', ['find' => __('main.request_requirements')]);
            redirect('/blogs/search');
        }
    }

    /**
     * Список всех блогов (Для вывода на главную страницу)
     *
     * @return string
     */
    public function main(): string
    {
        $total = Blog::query()->count();

        $page = paginate(setting('blogpost'), $total);

        $blogs = Blog::query()
            ->orderBy('created_at', 'desc')
            ->offset($page->offset)
            ->limit($page->limit)
            ->with('user')
            ->get();

        return view('blogs/main', compact('blogs', 'page'));
    }
}
