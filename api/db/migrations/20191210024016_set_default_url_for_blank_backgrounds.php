<?php


use Phinx\Migration\AbstractMigration;

class SetDefaultUrlForBlankBackgrounds extends AbstractMigration {
    public function up() {
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/backgrounds/menu_bg.jpg' WHERE `background_url` = ''");
    }

    public function down() {
        //-- None
    }
}
