<?php

namespace Tnt\Recruitment\Revisions;

use dry\db\Connection;
use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\QueryBuilder;
use Tnt\Dbi\TableBuilder;

class CreateVacancyTable implements RevisionInterface
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * CreateBlogPostTable constructor.
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     *
     */
    public function up()
    {
        $this->queryBuilder->table('recruitment_vacancy')->create(function(TableBuilder $table) {

            $table->addColumn('id', 'int')->length(11)->primaryKey();
            $table->addColumn('created', 'int')->length(11);
            $table->addColumn('updated', 'int')->length(11);
            $table->addColumn('title_nl', 'varchar')->length(255);
            $table->addColumn('title_fr', 'varchar')->length(255);
            $table->addColumn('title_en', 'varchar')->length(255);
            $table->addColumn('slug_nl', 'varchar')->length(255);
            $table->addColumn('slug_fr', 'varchar')->length(255);
            $table->addColumn('slug_en', 'varchar')->length(255);
            $table->addColumn('introduction_nl', 'varchar')->length(255);
            $table->addColumn('introduction_fr', 'varchar')->length(255);
            $table->addColumn('introduction_en', 'varchar')->length(255);
            $table->addColumn('short_description_nl', 'varchar')->length(255);
            $table->addColumn('short_description_fr', 'varchar')->length(255);
            $table->addColumn('short_description_en', 'varchar')->length(255);
            $table->addColumn('profile_nl', 'varchar')->length(255);
            $table->addColumn('profile_fr', 'varchar')->length(255);
            $table->addColumn('profile_en', 'varchar')->length(255);
            $table->addColumn('challenge_nl', 'varchar')->length(255);
            $table->addColumn('challenge_fr', 'varchar')->length(255);
            $table->addColumn('challenge_en', 'varchar')->length(255);
            $table->addColumn('contact_nl', 'varchar')->length(255);
            $table->addColumn('contact_fr', 'varchar')->length(255);
            $table->addColumn('contact_en', 'varchar')->length(255);
            $table->addColumn('offer_nl', 'varchar')->length(255);
            $table->addColumn('offer_fr', 'varchar')->length(255);
            $table->addColumn('offer_en', 'varchar')->length(255);
            $table->addColumn('sort_index', 'int')->length(11);
            $table->addColumn('is_visible', 'tinyint')->length(1);
            $table->addColumn('is_featured', 'tinyint')->length(1);
            $table->addColumn('photo', 'int')->length(11);

            $table->addForeignKey('photo', 'dry_media_file');

        });

        $this->queryBuilder->build();

        Connection::get()->query($this->queryBuilder->getQuery());
    }

    /**
     *
     */
    public function down()
    {
        $this->queryBuilder->table('recruitment_vacancy')->drop();

        $this->queryBuilder->build();

        Connection::get()->query($this->queryBuilder->getQuery());
    }

    /**
     * @return string
     */
    public function describeUp(): string
    {
        return 'Create recruitment_vacancy table';
    }

    /**
     * @return string
     */
    public function describeDown(): string
    {
        return 'Drop recruitment_vacancy table';
    }
}