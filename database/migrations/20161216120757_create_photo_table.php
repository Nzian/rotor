<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePhotoTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        if (! $this->hasTable('photo')) {
            $table = $this->table('photo', ['collation' => env('DB_COLLATION')]);
            $table
                ->addColumn('user_id', 'integer')
                ->addColumn('title', 'string', ['limit' => 50])
                ->addColumn('text', 'text', ['null' => true])
                ->addColumn('link', 'string', ['limit' => 30])
                ->addColumn('created_at', 'integer')
                ->addColumn('rating', 'integer', ['default' => 0])
                ->addColumn('closed', 'boolean', ['default' => 0])
                ->addColumn('count_comments', 'integer', ['default' => 0])
                ->addIndex('created_at')
                ->addIndex('user_id')
                ->create();
        }
    }
}
