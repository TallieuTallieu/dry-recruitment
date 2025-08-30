<?php

namespace Tnt\Recruitment\Model;

use dry\media\File;
use dry\orm\Model;
use dry\orm\special\Boolean;

class Vacancy extends Model
{
	const TABLE = 'recruitment_vacancy';

    const VIDEO_TYPE_FILE = 'file';
    const VIDEO_TYPE_VIMEO = 'vimeo';
    const VIDEO_TYPE_YOUTUBE = 'youtube';

	public static $special_fields = [
		'photo' => File::class,
		'video' => File::class,
		'video_thumb' => File::class,
		'is_visible' => Boolean::class,
		'is_featured' => Boolean::class,
	];

    public function getVideoUrl(): ?string
    {
        if ($this->video_type === self::VIDEO_TYPE_YOUTUBE) {
            return 'https://www.youtube.com/embed/' . $this->video_id . '?rel=0&autoplay=0';
        }

        if ($this->video_type === self::VIDEO_TYPE_VIMEO) {
            return 'https://player.vimeo.com/video/' . $this->video_id;
        }

        if ($this->video_type === self::VIDEO_TYPE_FILE) {
            return \app\MEDIA_ROOT . $this->video;
        }

        return null;
    }
}