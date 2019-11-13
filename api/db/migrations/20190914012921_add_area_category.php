<?php


use Phinx\Migration\AbstractMigration;

class AddAreaCategory extends AbstractMigration {
    public function up() {
        $this->table('node_categories')
            ->insert([
                [
                    'type' => 'Navigation',
                    'group' => 'Area',
                    'subgroup' => 'area',
                    'require_name' => 1,
                    'require_action' => 0,
                    'require_target' => 0,
                    'note' => 'Used to mark discovery areas shown on the in-game map',
                    'require_pickup' => 0,
                    'require_direction' => 0,
                    'order' => 33,
                    'searchable' => 0,
                    'collapsible' => 0,
                    'for_sniper_assassin' => 0,
                    'for_mission' => 1,
                    'icon' => 'area'
                ]
            ])->save();
    }

    public function down() {
        $this->execute("DELETE FROM `node_categories` WHERE `subgroup` = 'area'");
    }
}
