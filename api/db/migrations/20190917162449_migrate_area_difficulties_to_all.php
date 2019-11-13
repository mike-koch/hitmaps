<?php


use Phinx\Migration\AbstractMigration;

class MigrateAreaDifficultiesToAll extends AbstractMigration {
    public function up() {
        $this->execute("UPDATE `nodes` SET `difficulty` = 'ALL' WHERE `subgroup` = 'area'");
    }

    public function down() {
        $this->execute("UPDATE `nodes` SET `difficulty` = 'professional' WHERE `subgroup` = 'area'");
    }
}
