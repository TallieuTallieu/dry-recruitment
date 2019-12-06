<?php

namespace Tnt\Recruitment;

use Tnt\Dbi\BaseRepository;
use Tnt\Dbi\Criteria\GreaterThan;
use Tnt\Dbi\Criteria\IsTrue;
use Tnt\Dbi\Criteria\LessThan;
use Tnt\Dbi\Criteria\NotEquals;
use Tnt\Dbi\Criteria\OrderBy;
use Tnt\Recruitment\Contracts\VacancyRepositoryInterface;
use Tnt\Recruitment\Model\Vacancy;

class VacancyRepository extends BaseRepository implements VacancyRepositoryInterface
{
    /**
     * @var string Vacancy
     */
    protected $model = Vacancy::class;

    /**
     * init
     */
    protected function init()
    {
        $this->addCriteria(new OrderBy('sort_index'));

        parent::init();
    }

    /**
     * @return VacancyRepositoryInterface
     */
    public function visible(): VacancyRepositoryInterface
    {
        $this->addCriteria(new IsTrue('is_visible'));

        return $this;
    }

    /**
     * @return VacancyRepositoryInterface
     */
    public function featured(): VacancyRepositoryInterface
    {
        $this->addCriteria(new IsTrue('is_featured'));

        return $this;
    }

    /**
     * @param Vacancy $vacancy
     * @return VacancyRepositoryInterface
     */
    public function prev(Vacancy $vacancy): VacancyRepositoryInterface
    {
        $this->addCriteria(new LessThan('sort_index', $vacancy->sort_index));
        $this->addCriteria(new OrderBy('sort_index', 'DESC'));

        return $this;
    }

    /**
     * @param Vacancy $vacancy
     * @return VacancyRepositoryInterface
     */
    public function next(Vacancy $vacancy): VacancyRepositoryInterface
    {
        $this->addCriteria(new NotEquals('id', $vacancy->id));
        $this->addCriteria(new GreaterThan('sort_index', $vacancy->sort_index));

        return $this;
    }
}