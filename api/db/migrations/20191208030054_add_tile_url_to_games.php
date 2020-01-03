<?php


use Phinx\Migration\AbstractMigration;

class AddTileUrlToGames extends AbstractMigration {
    public function up() {
        $this->table('games')
            ->addColumn('tile_url', 'string')
            ->update();
        $this->execute("UPDATE `games` SET `tile_url` = 'https://media.hitmaps.com/img/story/background_season1.jpg' WHERE `slug` = 'hitman'");
        $this->execute("UPDATE `games` SET `tile_url` = 'https://media.hitmaps.com/img/story/background_season2.jpg' WHERE `slug` = 'hitman2'");
        $this->execute("UPDATE `games` SET `tile_url` = 'https://media.hitmaps.com/img/livetile/tile_gamemode_sniper.jpg' WHERE `slug` = 'sniper-assassin'");
    }

    public function down() {
        $this->table('games')
            ->removeColumn('tile_url')
            ->update();
    }
}
