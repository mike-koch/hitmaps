<?php


use Phinx\Migration\AbstractMigration;

class AddAreaIcon extends AbstractMigration {
    public function up() {
        $this->table('icons')
            ->insert([
                [
                    'icon' => 'area',
                    'alt_text' => 'Area',
                    'group' => 'Navigation',
                    'order' => 3
                ]
            ])->save();
    }

    public function down() {
        $this->execute("DELETE FROM `icons` WHERE `icon` = 'area'");
    }
}
