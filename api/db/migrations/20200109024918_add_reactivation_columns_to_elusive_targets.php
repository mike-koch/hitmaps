<?php


use Phinx\Migration\AbstractMigration;

class AddReactivationColumnsToElusiveTargets extends AbstractMigration {
    public function change() {
        $this->table('elusive_targets')
            ->addColumn('reactivated', 'boolean')
            ->update();
    }
}
