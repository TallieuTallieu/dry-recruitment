<?php

namespace Tnt\Recruitment;

use Oak\Contracts\Config\RepositoryInterface;
use Oak\Contracts\Container\ContainerInterface;
use Oak\Migration\MigrationManager;
use Oak\Migration\Migrator;
use Oak\ServiceProvider;
use Tnt\Recruitment\Admin\VacancyManager;
use Tnt\Recruitment\Contracts\VacancyManagerInterface;
use Tnt\Recruitment\Contracts\VacancyRepositoryInterface;
use Tnt\Recruitment\Revisions\CreateVacancyTable;
use Tnt\Recruitment\Revisions\UpdateAddVideo;
use Tnt\Recruitment\Revisions\UpdateColumnTypes;

class RecruitmentServiceProvider extends ServiceProvider
{
    public function boot(ContainerInterface $app)
    {
        if ($app->isRunningInConsole()) {

            $migrator = $app->getWith(Migrator::class, [
                'name' => 'vacancy',
            ]);

            $migrator->setRevisions([
                CreateVacancyTable::class,
                UpdateColumnTypes::class,
                UpdateAddVideo::class,
            ]);

            $app->get(MigrationManager::class)
                ->addMigrator($migrator);
        }
    }

    public function provides(): array
    {
        return [VacancyManagerInterface::class];
    }

    public function register(ContainerInterface $app)
    {
        $app->set(VacancyRepositoryInterface::class, VacancyRepository::class);

        $app->set(VacancyManagerInterface::class, function() use ($app) {
            return $this->registerManager($app);
        });
    }

    private function registerManager(ContainerInterface $app)
    {
        $languages = $app->get(RepositoryInterface::class)->get('recruitment.languages', [
            'nl',
            'en',
            'fr'
        ]);

        $requiredLanguages = $app->get(RepositoryInterface::class)->get('recruitment.required_languages', $languages);

        return new VacancyManager([
            'languages' => $languages,
            'requiredLanguages' => $requiredLanguages
        ]);
    }
}