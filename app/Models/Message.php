<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Inbox
 *
 * @property int id
 * @property int user_id
 * @property int author_id
 * @property string text
 * @property string type
 * @property int reading
 * @property int created_at
 */
class Message extends BaseModel
{
    use UploadTrait;

    public const IN   = 'in';   // Принятые
    public const OUT  = 'out';  // Отправленные

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Morph name
     *
     * @var string
     */
    public static $morphName = 'messages';

    /**
     * Директория загрузки файлов
     *
     * @var string
     */
    public $uploadPath = UPLOADS . '/messages';

    /**
     * Возвращает связь пользователей
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id')->withDefault();
    }

    /**
     * Возвращает загруженные файлы
     *
     * @return MorphMany
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'relate');
    }

}
