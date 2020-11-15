<?php

use Phinx\Migration\AbstractMigration;

class CreateBanhistTable extends AbstractMigration
{
    /**
     * Migrate Change.
     */
    public function change()
    {
        if (! $this->hasTable('banhist')) {
            $table = $this->table('banhist', ['engine' => config('DB_ENGINE'), 'collation' => config('DB_COLLATION')]);
            $table
                ->addColumn('user_id', 'integer')
                ->addColumn('send_user_id', 'integer')
                //->addColumn('type', 'enum', ['values' => ['ban','unban','change']])
                ->addColumn('type', 'string', ['limit' => 10])
                ->addColumn('reason', 'text', ['null' => true])
                ->addColumn('term', 'integer', ['default' => 0])
                ->addColumn('created_at', 'integer')
                ->addColumn('explain', 'boolean', ['default' => 0])
                ->addIndex('user_id')
                ->addIndex('created_at')
                ->create();
        }
    }
}
