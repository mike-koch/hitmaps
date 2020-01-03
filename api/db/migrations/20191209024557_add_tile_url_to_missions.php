<?php


use Phinx\Migration\AbstractMigration;

class AddTileUrlToMissions extends AbstractMigration {
    public function up() {
        $this->table('missions')
            ->addColumn('tile_url', 'string')
            ->update();
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/polarbear/graduation/tile.jpg' WHERE `slug` = 'the-final-test'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/novikov_and_magolis/tile.jpg' WHERE `slug` = 'the-showstopper'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/surveillance_portofino/tile.jpg' WHERE `slug` = 'world-of-tomorrow'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/copperhead/tile.jpg' WHERE `slug` = 'the-icon'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/mamba/tile.jpg' WHERE `slug` = 'landslide'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/whitespider/002_ebola/tile.jpg' WHERE `slug` = 'the-author'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/spider/tile.jpg' WHERE `slug` = 'a-gilded-cage'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/python/tile.jpg' WHERE `slug` = 'a-house-built-on-sand'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/tiger/tile.jpg' WHERE `slug` = 'club-27'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/whitespider/001_zika/tile.jpg' WHERE `slug` = 'the-source'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/bull/tile.jpg' WHERE `slug` = 'freedom-fighters'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/snowcrane/tile.jpg' WHERE `slug` = 'situs-inversus'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/whitespider/004_flu/tile.jpg' WHERE `slug` = 'patient-zero'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/sheep/tile.jpg' WHERE `slug` = 'nightcall'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/flamingo/tile.jpg' WHERE `slug` = 'finish-line'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/hippo/tile.jpg' WHERE `slug` = 'three-headed-serpent'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/mongoose/tile.jpg' WHERE `slug` = 'chasing-a-ghost'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/skunk/tile.jpg' WHERE `slug` = 'another-life'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/magpie/tile.jpg' WHERE `slug` = 'ark-society'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/polarbear/module_002_b/tile.jpg' WHERE `slug` = 'freeform-training'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/parisnoel/tile.jpg' WHERE `slug` = 'holiday-hoarders'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/mamushi/tile.jpg' WHERE `slug` = 'hokkaido-snow-festival'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/locations/location_austria/tile.jpg' WHERE `slug` = 'the-last-yardbird'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/seagull/tile_background.jpg' WHERE `slug` = 'the-pen-and-the-sword'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/raccoon/tile.jpg' WHERE `slug` = 'golden-handshake'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/anaconda/tile.jpg' WHERE `slug` = 'embrace-of-the-serpent'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/kingcobra/tile.jpg' WHERE `slug` = 'illusions-of-grandeur'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/falcon/tile_background.jpg' WHERE `slug` = 'crime-and-punishment'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/stingray/tile.jpg' WHERE `slug` = 'the-last-resort'");
        $this->execute("UPDATE `missions` SET `tile_url` = 'https://media.hitmaps.com/img/contracts/escalation/contractescalation_newzealand_opuntia.jpg' WHERE `slug` = 'the-mills-reverie'");
    }

    public function down() {
        $this->table('missions')
            ->removeColumn('tile_url')
            ->update();
    }
}
