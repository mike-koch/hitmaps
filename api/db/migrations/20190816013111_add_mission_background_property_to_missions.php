<?php


use Phinx\Migration\AbstractMigration;

class AddMissionBackgroundPropertyToMissions extends AbstractMigration {
    public function up() {
        $this->table('missions')
            ->addColumn('background', 'string')
            ->update();
        $this->execute("UPDATE `missions` SET `background` = `slug`");
    }

    public function down() {
        $this->table('missions')
            ->removeColumn('background')
            ->update();
    }
}
