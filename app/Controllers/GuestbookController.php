<?php

declare(strict_types=1);

namespace App\Controllers;

//use App\Classes\Validator;
use App\Models\Flood;
use App\Models\Guestbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestbookController extends BaseController
{
    /**
     * Главная страница
     *
     * @return string
     */
    public function index(): string
    {
        $posts = Guestbook::query()
            ->orderByDesc('created_at')
            ->with('user', 'editUser')
            ->paginate(setting('bookpost'));

        return view('guestbook/index', compact('posts'));
    }

    /**
     * Добавление сообщения
     *
     * @param Request $request
     * @param Flood   $flood
     *
     * @return void
     */
    public function add(Request $request, Flood $flood): void
    {
        $user = getUser();
        $request = array_map('trim', $request->all());

        $validator = Validator::make($request, [
            'token' => 'required|in:' . $_SESSION['token'],
            'msg'   => 'required|string|between:5,' . setting('guesttextlength'),
        ], [
            'token.*'     => __('validator.token'),
            'msg.between' => __('validator.text'),
        ]);

        $validator->after(function ($validator) use ($user, $flood) {
            if ($flood->isFlood()) {
                $validator->errors()->add('msg', 'Is flood');
            }

            if (! $user && setting('bookadds')) {

            }
        });



        //dd($validation->errors(), $request);

      //  $validator = $validation->make($data, $rules);


        if ($validator->passes()) {




/*        $validator->equal($request->input('token'), $_SESSION['token'], ['msg' => __('validator.token')])
            ->length($msg, 5, setting('guesttextlength'), ['msg' => __('validator.text')])
            ->false($flood->isFlood(), ['msg' => __('validator.flood', ['sec' => $flood->getPeriod()])]);*/

        /* Проверка для гостей */
/*        if (! $user && setting('bookadds')) {
            $validator->true(captchaVerify(), ['protect' => __('validator.captcha')]);
            $validator->false(strpos($msg, '//'), ['msg' => __('guestbook.without_links')]);
            $validator->length($request->input('guest_name'), 3, 20, ['guest_name' => __('users.name_short_or_long')], false);
        } else {
            $validator->true($user, ['msg' => __('main.not_authorized')]);
        }

        if ($validator->isValid()) {*/
            $msg       = antimat($request['msg']);
            //$guestName = $request->input('guest_name');

            if ($user) {
                $guestName  = null;
                $bookscores = setting('bookscores') ? 1 : 0;

                $user->increment('allguest');
                $user->increment('point', $bookscores);
                $user->increment('money', 5);
            }

            Guestbook::query()->create([
                'user_id'    => $user->id ?? null,
                'text'       => $msg,
                'ip'         => getIp(),
                'brow'       => getBrowser(),
                'guest_name' => $guestName,
                'created_at' => SITETIME,
            ]);

            clearCache('statGuestbook');
            $flood->saveState();

            sendNotify($msg, '/guestbook', __('index.guestbook'));
            setFlash('success', __('main.message_added_success'));
        } else {
            setInput($request);
            setFlash('danger', $validator->errors());

/*            setFlash('danger', $validator->getErrors());*/
        }

        redirect('/guestbook');
    }

    /**
     * Редактирование сообщения
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function edit(int $id, Request $request, Validator $validator): string
    {
        if (! $user = getUser()) {
            abort(403);
        }

        $msg = $request->input('msg');

        /** @var Guestbook $post */
        $post = Guestbook::query()->where('user_id', $user->id)->find($id);

        if (! $post) {
            abort('default', __('main.message_not_found'));
        }

        if ($post->created_at + 600 < SITETIME) {
            abort('default', __('main.editing_impossible'));
        }

        if ($request->isMethod('post')) {
            $validator->equal($request->input('token'), $_SESSION['token'], ['msg' => __('validator.token')])
                ->length($msg, 5, setting('guesttextlength'), ['msg' => __('validator.text')]);

            if ($validator->isValid()) {
                $post->update([
                    'text'         => antimat($msg),
                    'edit_user_id' => $user->id,
                    'updated_at'   => SITETIME,
                ]);

                setFlash('success', __('main.message_edited_success'));
                redirect('/guestbook');
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('guestbook/edit', compact('post'));
    }
}
