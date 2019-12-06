<?php

namespace Tnt\Recruitment\Model;

use dry\media\File;
use dry\orm\Model;
use dry\orm\special\Boolean;

class Vacancy extends Model
{
	const TABLE = 'recruitment_vacancy';

	public static $special_fields = [
		'photo' => File::class,
		'is_visible' => Boolean::class,
		'is_featured' => Boolean::class,
	];
}