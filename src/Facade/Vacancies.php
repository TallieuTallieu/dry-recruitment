<?php

namespace Tnt\Recruitment\Facade;

use Oak\Facade;
use Tnt\Recruitment\Contracts\VacancyRepositoryInterface;

class Vacancies extends Facade
{
    protected static function getContract(): string
    {
        return VacancyRepositoryInterface::class;
    }
}