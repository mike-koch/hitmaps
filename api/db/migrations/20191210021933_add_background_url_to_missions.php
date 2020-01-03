<?php


use Phinx\Migration\AbstractMigration;

class AddBackgroundUrlToMissions extends AbstractMigration {
    public function up() {
        $this->table('missions')
            ->addColumn('background_url', 'string')
            ->removeColumn('background')
            ->update();
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_siberia_graduation/background.jpg' WHERE `slug` = 'the-final-test'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_paris/background.jpg' WHERE `slug` = 'the-showstopper'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_coastaltown/background.jpg' WHERE `slug` = 'world-of-tomorrow'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_coastaltown_copperhead/background.jpg' WHERE `slug` = 'the-icon'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_coastaltown_mamba/background.jpg' WHERE `slug` = 'landslide'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_coastaltown_ebola/background.jpg' WHERE `slug` = 'the-author'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_marrakech/background.jpg' WHERE `slug` = 'a-gilded-cage'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_marrakech_python/background.jpg' WHERE `slug` = 'a-house-built-on-sand'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_bangkok/background.jpg' WHERE `slug` = 'club-27'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_bangkok_zika/background.jpg' WHERE `slug` = 'the-source'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_colorado/background.jpg' WHERE `slug` = 'freedom-fighters'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_hokkaido/background.jpg' WHERE `slug` = 'situs-inversus'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_hokkaido_flu/background.jpg' WHERE `slug` = 'patient-zero'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_newzealand/background.jpg' WHERE `slug` = 'nightcall'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_miami/background.jpg' WHERE `slug` = 'finish-line'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_colombia/background.jpg' WHERE `slug` = 'three-headed-serpent'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_mumbai/background.jpg' WHERE `slug` = 'chasing-a-ghost'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_northamerica/background.jpg' WHERE `slug` = 'another-life'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_northsea/background.jpg' WHERE `slug` = 'ark-society'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_siberia_ship/background.jpg' WHERE `slug` = 'freeform-training'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_paris/background.jpg' WHERE `slug` = 'holiday-hoarders'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_hokkaido_mamushi/background.jpg' WHERE `slug` = 'hokkaido-snow-festival'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_austria/background.jpg' WHERE `slug` = 'the-last-yardbird'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_salty/background.jpg' WHERE `slug` = 'the-pen-and-the-sword'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_greedy_raccoon/background.jpg' WHERE `slug` = 'golden-handshake'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_colombia_anaconda/background.jpg' WHERE `slug` = 'embrace-of-the-serpent'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_mumbai_kingcobra/background.jpg' WHERE `slug` = 'illusions-of-grandeur'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_caged_falcon/background.jpg' WHERE `slug` = 'crime-and-punishment'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_opulent_stingray/background.jpg' WHERE `slug` = 'the-last-resort'");
        $this->execute("UPDATE `missions` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_newzealand/background.jpg' WHERE `slug` = 'the-mills-reverie'");
    }

    public function down() {
        $this->table('missions')
            ->addColumn('background', 'string')
            ->removeColumn('background_url')
            ->update();
        $this->execute("UPDATE `missions` SET `background` = 'prologue' WHERE `slug` = 'the-final-test'");
        $this->execute("UPDATE `missions` SET `background` = 'the-showstopper' WHERE `slug` = 'the-showstopper'");
        $this->execute("UPDATE `missions` SET `background` = 'world-of-tomorrow' WHERE `slug` = 'world-of-tomorrow'");
        $this->execute("UPDATE `missions` SET `background` = 'the-icon' WHERE `slug` = 'the-icon'");
        $this->execute("UPDATE `missions` SET `background` = 'landslide' WHERE `slug` = 'landslide'");
        $this->execute("UPDATE `missions` SET `background` = 'the-author' WHERE `slug` = 'the-author'");
        $this->execute("UPDATE `missions` SET `background` = 'a-gilded-cage' WHERE `slug` = 'a-gilded-cage'");
        $this->execute("UPDATE `missions` SET `background` = 'a-house-built-on-sand' WHERE `slug` = 'a-house-built-on-sand'");
        $this->execute("UPDATE `missions` SET `background` = 'club-27' WHERE `slug` = 'club-27'");
        $this->execute("UPDATE `missions` SET `background` = 'the-source' WHERE `slug` = 'the-source'");
        $this->execute("UPDATE `missions` SET `background` = 'freedom-fighters' WHERE `slug` = 'freedom-fighters'");
        $this->execute("UPDATE `missions` SET `background` = 'situs-inversus' WHERE `slug` = 'situs-inversus'");
        $this->execute("UPDATE `missions` SET `background` = 'patient-zero' WHERE `slug` = 'patient-zero'");
        $this->execute("UPDATE `missions` SET `background` = 'nightcall' WHERE `slug` = 'nightcall'");
        $this->execute("UPDATE `missions` SET `background` = 'the-finish-line' WHERE `slug` = 'finish-line'");
        $this->execute("UPDATE `missions` SET `background` = 'three-headed-serpent' WHERE `slug` = 'three-headed-serpent'");
        $this->execute("UPDATE `missions` SET `background` = 'chasing-a-ghost' WHERE `slug` = 'chasing-a-ghost'");
        $this->execute("UPDATE `missions` SET `background` = 'another-life' WHERE `slug` = 'another-life'");
        $this->execute("UPDATE `missions` SET `background` = 'ark-society' WHERE `slug` = 'ark-society'");
        $this->execute("UPDATE `missions` SET `background` = 'prologue' WHERE `slug` = 'freeform-training'");
        $this->execute("UPDATE `missions` SET `background` = 'holiday-hoarders' WHERE `slug` = 'holiday-hoarders'");
        $this->execute("UPDATE `missions` SET `background` = 'hokkaido-snow-festival' WHERE `slug` = 'hokkaido-snow-festival'");
        $this->execute("UPDATE `missions` SET `background` = 'the-last-yardbird' WHERE `slug` = 'the-last-yardbird'");
        $this->execute("UPDATE `missions` SET `background` = 'the-pen-and-the-sword' WHERE `slug` = 'the-pen-and-the-sword'");
        $this->execute("UPDATE `missions` SET `background` = 'golden-handshake' WHERE `slug` = 'golden-handshake'");
        $this->execute("UPDATE `missions` SET `background` = 'embrace-of-the-serpent' WHERE `slug` = 'embrace-of-the-serpent'");
        $this->execute("UPDATE `missions` SET `background` = 'illusions-of-grandeur' WHERE `slug` = 'illusions-of-grandeur'");
        $this->execute("UPDATE `missions` SET `background` = 'crime-and-punishment' WHERE `slug` = 'crime-and-punishment'");
        $this->execute("UPDATE `missions` SET `background` = 'the-last-resort' WHERE `slug` = 'the-last-resort'");
        $this->execute("UPDATE `missions` SET `background` = 'nightcall' WHERE `slug` = 'the-mills-reverie'");
    }
}
