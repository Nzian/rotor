<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Classes\Validator;
use App\Models\Sticker;
use App\Models\StickersCategory;
use App\Models\User;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Http\Request;

class StickerController extends AdminController
{
    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();

        if (! isAdmin(User::ADMIN)) {
            abort(403, __('errors.forbidden'));
        }
    }

    /**
     * Главная страница
     *
     * @return string
     */
    public function index(): string
    {
        $categories = StickersCategory::query()
            ->selectRaw('sc.id, sc.name, count(s.id) cnt')
            ->from('stickers_categories as sc')
            ->leftJoin('stickers as s', 's.category_id', 'sc.id')
            ->groupBy('sc.id')
            ->orderBy('sc.id')
            ->get();

        return view('admin/stickers/index', compact('categories'));
    }

    /**
     * Просмотр стикеров по категориям
     *
     * @param int $id
     *
     * @return string
     */
    public function category(int $id): string
    {
        $category = StickersCategory::query()->where('id', $id)->first();

        if (! $category) {
            abort(404, __('stickers.category_not_exist'));
        }

        $stickers = Sticker::query()
            ->where('category_id', $id)
            ->orderBy(DB::connection()->raw('CHAR_LENGTH(code)'))
            ->orderBy('name')
            ->with('category')
            ->paginate(setting('stickerlist'));

        return view('admin/stickers/category', compact('category', 'stickers'));
    }

    /**
     * Создание категории
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     */
    public function create(Request $request, Validator $validator): void
    {

        $name = $request->input('name');

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
            ->length($name, 3, 50, ['name' => __('validator.text')]);

        if ($validator->isValid()) {
            /** @var StickersCategory $category */
            $category = StickersCategory::query()->create([
                'name'       => $name,
                'created_at' => SITETIME,
            ]);

            setFlash('success', __('stickers.category_success_created'));
            redirect('/admin/stickers/' . $category->id);
        } else {
            setInput($request->all());
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/stickers');
    }

    /**
     * Редактирование категории
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function edit(int $id, Request $request, Validator $validator): string
    {
        $category = StickersCategory::query()->find($id);

        if (! $category) {
            abort(404, __('stickers.category_not_exist'));
        }

        if ($request->isMethod('post')) {
            $name = $request->input('name');

            $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($name, 3, 50, ['name' => __('validator.text')]);

            if ($validator->isValid()) {
                $category->update([
                    'name'       => $name,
                    'updated_at' => SITETIME,
                ]);

                setFlash('success', __('stickers.category_success_changed'));
                redirect('/admin/stickers');
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/stickers/edit_category', compact('category'));
    }

    /**
     * Удаление категории
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @throws Exception
     */
    public function delete(int $id, Request $request, Validator $validator): void
    {
        /** @var StickersCategory $category */
        $category = StickersCategory::query()->find($id);

        if (! $category) {
            abort(404, __('stickers.category_not_exist'));
        }

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'));

        $sticker = Sticker::query()->where('category_id', $category->id)->first();
        if ($sticker) {
            $validator->addError(__('stickers.category_has_stickers'));
        }

        if ($validator->isValid()) {
            $category->delete();

            setFlash('success', __('stickers.category_success_deleted'));
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/stickers');
    }

    /**
     * Добавление стикера
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function createSticker(Request $request, Validator $validator): string
    {
        $cid = int($request->input('cid'));

        $categories = StickersCategory::query()->get();

        if ($categories->isEmpty()) {
            abort('default', __('stickers.empty_categories'));
        }

        if (! is_writable(UPLOADS . '/stickers')) {
            abort('default', __('main.directory_not_writable'));
        }

        if ($request->isMethod('post')) {
            $code    = utfLower($request->input('code'));
            $sticker = $request->file('sticker');

            $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($code, 2, 20, ['code' => __('stickers.sticker_length')])
                ->regex($code, '|^:+[a-яa-z0-9_\-/\(\)]+$|i', ['code' => __('stickers.sticker_requirements')]);

            $category = StickersCategory::query()->where('id', $cid)->first();
            $validator->notEmpty($category, ['category' => __('stickers.category_not_exist')]);

            $duplicate = Sticker::query()->where('code', $code)->first();
            $validator->empty($duplicate, ['code' => __('stickers.sticker_exists')]);

            $rules = [
                'maxsize'   => setting('stickermaxsize'),
                'maxweight' => setting('stickermaxweight'),
                'minweight' => setting('stickerminweight'),
            ];

            $validator->file($sticker, $rules, ['sticker' => __('validator.image_upload_failed')]);

            if ($validator->isValid()) {
                $newName = uniqueName($sticker->getClientOriginalExtension());
                $path    = (new Sticker())->uploadPath . '/' . $newName;
                $sticker->move((new Sticker())->uploadPath, $newName);

                Sticker::query()->create([
                    'category_id' => $cid,
                    'name'        => str_replace(HOME, '', $path),
                    'code'        => $code,
                ]);

                clearCache('stickers');
                setFlash('success', __('stickers.sticker_success_created'));
                redirect('/admin/stickers/' . $cid);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/stickers/create_sticker', compact('categories', 'cid'));
    }

    /**
     * Редактирование стикера
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function editSticker(int $id, Request $request, Validator $validator): string
    {
        /** @var Sticker $sticker */
        $sticker = Sticker::query()->find($id);
        $page    = int($request->input('page', 1));

        if (! $sticker) {
            abort(404, __('stickers.sticker_not_exist'));
        }

        if ($request->isMethod('post')) {
            $code  = utfLower($request->input('code'));
            $cid   = int($request->input('cid'));

            $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($code, 2, 20, ['code' => __('stickers.sticker_length')])
                ->regex($code, '|^:+[a-яa-z0-9_\-/\(\)]+$|i', ['code' => __('stickers.sticker_requirements')]);

            $duplicate = Sticker::query()->where('code', $code)->where('id', '<>', $sticker->id)->first();
            $validator->empty($duplicate, ['code' => __('stickers.sticker_exists')]);

            $category = StickersCategory::query()->where('id', $cid)->first();
            $validator->notEmpty($category, ['category' => __('stickers.category_not_exist')]);

            if ($validator->isValid()) {
                $sticker->update([
                    'code'        => $code,
                    'category_id' => $cid,
                ]);

                clearCache('stickers');
                setFlash('success', __('stickers.sticker_success_changed'));
                redirect('/admin/stickers/' . $cid . '?page=' . $page);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $categories = StickersCategory::query()->get();

        return view('admin/stickers/edit_sticker', compact('sticker', 'categories', 'page'));
    }

    /**
     * Удаление стикера
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     * @throws Exception
     */
    public function deleteSticker(int $id, Request $request, Validator $validator): void
    {
        if (! is_writable(UPLOADS . '/stickers')) {
            abort('default', __('main.directory_not_writable'));
        }

        $sticker = Sticker::query()->where('id', $id)->first();

        if (! $sticker) {
            abort(404, __('stickers.sticker_not_exist'));
        }

        $page     = int($request->input('page', 1));
        $category = $sticker->category->id;

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'));

        if ($validator->isValid()) {
            deleteFile(HOME . $sticker->name);
            $sticker->delete();

            clearCache('stickers');
            setFlash('success', __('stickers.sticker_success_deleted'));
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/stickers/' . $category . '?page=' . $page);
    }
}
