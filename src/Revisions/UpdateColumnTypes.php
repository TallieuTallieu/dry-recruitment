<?php

namespace Tnt\Recruitment\Revisions;

use dry\db\Connection;
use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\QueryBuilder;
use Tnt\Dbi\TableBuilder;

class UpdateColumnTypes implements RevisionInterface
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
        $this->queryBuilder->table('recruitment_vacancy')->alter(function(TableBuilder $table) {

            $table->changeColumn('profile_nl')->type('text');
            $table->changeColumn('profile_fr')->type('text');
            $table->changeColumn('profile_en')->type('text');
            $table->changeColumn('challenge_nl')->type('text');
            $table->changeColumn('challenge_fr')->type('text');
            $table->changeColumn('challenge_en')->type('text');
            $table->changeColumn('contact_nl')->type('text');
            $table->changeColumn('contact_fr')->type('text');
            $table->changeColumn('contact_en')->type('text');
            $table->changeColumn('offer_nl')->type('text');
            $table->changeColumn('offer_fr')->type('text');
            $table->changeColumn('offer_en')->type('text');

        });

        $this->queryBuilder->build();

        Connection::get()->query($this->queryBuilder->getQuery());
    }

    /**
     *
     */
    public function down()
    {
        $this->queryBuilder->table('recruitment_vacancy')->alter(function(TableBuilder $table) {

            $table->changeColumn('profile_nl')->type('varchar')->length(255);
            $table->changeColumn('profile_fr')->type('varchar')->length(255);
            $table->changeColumn('profile_en')->type('varchar')->length(255);
            $table->changeColumn('challenge_nl')->type('varchar')->length(255);
            $table->changeColumn('challenge_fr')->type('varchar')->length(255);
            $table->changeColumn('challenge_en')->type('varchar')->length(255);
            $table->changeColumn('contact_nl')->type('varchar')->length(255);
            $table->changeColumn('contact_fr')->type('varchar')->length(255);
            $table->changeColumn('contact_en')->type('varchar')->length(255);
            $table->changeColumn('offer_nl')->type('varchar')->length(255);
            $table->changeColumn('offer_fr')->type('varchar')->length(255);
            $table->changeColumn('offer_en')->type('varchar')->length(255);

        });

        $this->queryBuilder->build();

        Connection::get()->query($this->queryBuilder->getQuery());
    }

    /**
     * @return string
     */
    public function describeUp(): string
    {
        return 'Update column types recruitment_vacancy table';
    }

    /**
     * @return string
     */
    public function describeDown(): string
    {
        return 'Downdate column types recruitment_vacancy table';
    }
}