<?php


use Phinx\Migration\AbstractMigration;

class AddBackgroundUrlToLocations extends AbstractMigration {
    public function up() {
        $this->table('locations')
            ->addColumn('background_url', 'string')
            ->update();
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_siberia/background.jpg' WHERE `slug` = 'ica-facility'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_paris/background.jpg' WHERE `slug` = 'paris'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_coastaltown/background.jpg' WHERE `slug` = 'sapienza'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_marrakech/background.jpg' WHERE `slug` = 'marrakesh'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_bangkok/background.jpg' WHERE `slug` = 'bangkok'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_colorado/background.jpg' WHERE `slug` = 'colorado'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_hokkaido/background.jpg' WHERE `slug` = 'hokkaido'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_newzealand/background.jpg' WHERE `slug` = 'hawkes-bay'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_miami/background.jpg' WHERE `slug` = 'miami'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_colombia/background.jpg' WHERE `slug` = 'santa-fortuna'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_mumbai/background.jpg' WHERE `slug` = 'mumbai'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_northamerica/background.jpg' WHERE `slug` = 'whittleton-creek'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_northsea/background.jpg' WHERE `slug` = 'isle-of-sgail'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_austria/background.jpg' WHERE `slug` = 'himmelstein'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_salty/background.jpg' WHERE `slug` = 'hantu-port'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_greedy_raccoon/background.jpg' WHERE `slug` = 'new-york'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_caged_falcon/background.jpg' WHERE `slug` = 'siberia'");
        $this->execute("UPDATE `locations` SET `background_url` = 'https://media.hitmaps.com/img/locations/location_opulent_stingray/background.jpg' WHERE `slug` = 'haven-island'");
    }

    public function down() {
        $this->table('locations')
            ->removeColumn('background_url')
            ->update();
    }
}
