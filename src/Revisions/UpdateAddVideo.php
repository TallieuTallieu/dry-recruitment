<?php

namespace Tnt\Recruitment\Revisions;

use dry\db\Connection;
use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\QueryBuilder;
use Tnt\Dbi\TableBuilder;

class UpdateAddVideo implements RevisionInterface
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * CreateBlogPostBlockTable constructor.
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
        $this->queryBuilder->table('recruitment_vacancy')->alter(function (TableBuilder $table) {
            $table->addColumn('media_credit_nl', 'varchar')->length(255);
            $table->addColumn('media_credit_fr', 'varchar')->length(255);
            $table->addColumn('media_credit_en', 'varchar')->length(255);

            $table->addColumn('video_type', 'varchar')->length(255);
            $table->addColumn('video_id', 'varchar')->length(255);

            $table->addColumn('video', 'int')->length(11)->null();
            $table->addForeignKey('video', 'dry_media_file');

            $table->addColumn('video_thumb', 'int')->length(11)->null();
            $table->addForeignKey('video_thumb', 'dry_media_file');
        });

        $this->queryBuilder->build();

        Connection::get()->query($this->queryBuilder->getQuery());
    }

    /**
     *
     */
    public function down()
    {
        $this->queryBuilder->table('recruitment_vacancy')->alter(function (TableBuilder $table) {

            $table->dropColumn('media_credit_nl');
            $table->dropColumn('media_credit_fr');
            $table->dropColumn('media_credit_en');

            $table->dropColumn('video_type');
            $table->dropColumn('video_id');

            $table->dropColumn('video');
            $table->dropForeignKey('video', 'dry_media_file');

        });

        $this->queryBuilder->build();

        Connection::get()->query($this->queryBuilder->getQuery());
    }

    /**
     * @return string
     */
    public function describeUp(): string
    {
        return 'Update recruitment_vacancy table add video';
    }

    /**
     * @return string
     */
    public function describeDown(): string
    {
        return 'Update recruitment_vacancy table drop video';
    }
}
