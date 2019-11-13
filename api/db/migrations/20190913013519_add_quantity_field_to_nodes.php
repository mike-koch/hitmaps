<?php


use Phinx\Migration\AbstractMigration;

class AddQuantityFieldToNodes extends AbstractMigration {
    public function change() {
        $this->table('nodes')
            ->addColumn('quantity', 'integer', ['default' => 1])
            ->update();
    }
}
