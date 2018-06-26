<?php

namespace App\Models;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\UploadedFile;

class Down extends BaseModel
{
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
     * Директория загрузки файлов
     *
     * @var string
     */
    public $uploadPath       = UPLOADS . '/files';
    public $uploadScreenPath = UPLOADS . '/screens';

    /**
     * Список расширений доступных для просмотра в архиве
     *
     * @var array
     */
    public static $viewExt = ['xml', 'wml', 'asp', 'aspx', 'shtml', 'htm', 'phtml', 'html', 'php', 'htt', 'dat', 'tpl', 'htaccess', 'pl', 'js', 'jsp', 'css', 'txt', 'sql', 'gif', 'png', 'bmp', 'wbmp', 'jpg', 'jpeg', 'env', 'gitignore', 'json', 'yml', 'md'];

    /**
     * Возвращает категорию загрузок
     */
    public function category()
    {
        return $this->belongsTo(Load::class, 'category_id')->withDefault();
    }

    /**
     * Возвращает комментарии
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'relate');
    }

    /**
     * Возвращает последнии комментарии к файлу
     *
     * @param int $limit
     * @return mixed
     */
    public function lastComments($limit = 15)
    {
        return $this->hasMany(Comment::class, 'relate_id')
            ->where('relate_type', self::class)
            ->limit($limit);
    }

    /**
     * Возвращает загруженные файлы
     */
    public function files()
    {
        return $this->morphMany(File::class, 'relate');
    }

    /**
     * Возвращает файлы
     */
    public function getFiles()
    {
        return $this->files->filter(function ($value, $key) {
            return ! $value->isImage();
        });
    }

    /**
     * Возвращает картинки
     */
    public function getImages()
    {
        return $this->files->filter(function ($value, $key) {
            return $value->isImage();
        });
    }

    /**
     * Обрезает текст
     *
     * @param int $limit
     * @return string
     */
    public function cutText($limit = 200): string
    {
        if (strlen($this->text) > $limit) {
            $this->text = strip_tags(bbCode($this->text), '<br>');
            $this->text = mb_substr($this->text, 0, mb_strrpos(mb_substr($this->text, 0, $limit), ' ')) . '...';
        }

        return $this->text;
    }

    /**
     * Возвращает массив доступных расширений для просмотра в архиве
     *
     * @return array
     */
    public static function getViewExt(): array
    {
        return self::$viewExt;
    }

    /**
     * Загружает файл
     *
     * @param  UploadedFile $file
     * @param  string       $uploadPath
     * @return string
     */
    public function uploadFile(UploadedFile $file, $uploadPath = null): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path      = in_array($extension, ['jpg', 'jpeg', 'gif', 'png']) ? $this->uploadScreenPath : $this->uploadPath;

        $upload = parent::uploadFile($file, $path);
        $this->convertVideo($file, $upload);

        return $upload;
    }

    /**
     * @param UploadedFile $file
     */
    public function convertVideo(UploadedFile  $file, $fileName)
    {
        $isVideo = strpos($file->getClientMimeType(), 'video/') !== false;

        // Обработка видео
        if ($isVideo && env('FFMPEG_ENABLED')) {

            $ffconfig = [
                'ffmpeg.binaries'  => env('FFMPEG_PATH'),
                'ffprobe.binaries' => env('FFPROBE_PATH'),
                'timeout'          => env('FFMPEG_TIMEOUT'),
                'ffmpeg.threads'   => env('FFMPEG_THREADS'),
            ];

            $ffmpeg = FFMpeg::create($ffconfig);

            $video = $ffmpeg->open($this->uploadPath . '/' . $fileName);

            // Сохраняем скрин с 5 секунды
            $frame = $video->frame(TimeCode::fromSeconds(5));
            $frame->save($this->uploadScreenPath . '/' . $fileName . '.jpg');

            File::query()->create([
                'relate_id'   => $this->id,
                'relate_type' => self::class,
                'hash'        => $fileName . '.jpg',
                'name'        => 'screenshot.jpg',
                'size'        => filesize($this->uploadScreenPath . '/' . $fileName . '.jpg'),
                'user_id'     => getUser('id'),
                'created_at'  => SITETIME,
            ]);

            // Перекодируем видео в h264
            $ffprobe = FFProbe::create($ffconfig);
            $codec = $ffprobe
                ->streams($this->uploadPath . '/' . $fileName)
                ->videos()
                ->first()
                ->get('codec_name');

            if ($file->getClientOriginalExtension() === 'mp4' && $codec !== 'h264') {
                $format = new X264('libmp3lame', 'libx264');
                $video->save($format, $this->uploadPath . '/convert-' . $fileName);

                rename(
                    $this->uploadPath . '/convert-' . $fileName,
                    $this->uploadPath . '/' . $fileName
                );
            }
        }
    }

    /**
     * Удаление загрузки и загруженных файлов
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->files->each(function($file) {

            if ($file->isImage()) {
                deleteFile($this->uploadScreenPath . '/' . $file->hash);
            } else {
                deleteFile($this->uploadPath . '/' . $file->hash);
            }

            $file->delete();
        });

        return parent::delete();
    }
}
