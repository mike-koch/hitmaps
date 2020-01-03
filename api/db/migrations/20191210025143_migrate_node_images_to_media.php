<?php


use Phinx\Migration\AbstractMigration;

class MigrateNodeImagesToMedia extends AbstractMigration {
    public function up() {
        foreach ($this->getReplacementMap() as $old => $new) {
            $this->execute("UPDATE `nodes` SET `image` = '{$new}' WHERE `image` = '{$old}'");
            $this->execute("UPDATE `items` SET `image` = '{$new}' WHERE `image` = '{$old}'");
        }
    }

    private function getReplacementMap() {
        return [
            '/weapons/non-lethal-melee/shovel' => 'https://media.hitmaps.com/img/unlockables/item_perspective_1066917f-2e04-4c54-b8cb-55cb1dcc2f26_0.jpg',
            '/weapons/non-lethal-melee/crowbar' => 'https://media.hitmaps.com/img/unlockables/item_perspective_b48bc55e-aaf7-4c62-9fbd-07bae9b8e22b_0.jpg',
            '/weapons/non-lethal-melee/hammer' => 'https://media.hitmaps.com/img/unlockables/item_perspective_a5a561be-e832-4ac0-98c9-741f66bdeabd_0.jpg',
            '/weapons/lethal-melee/screwdriver' => 'https://media.hitmaps.com/img/unlockables/item_perspective_12cb6b51-a6dd-4bf5-9653-0ab727820cac_0.jpg',
            '/weapons/lethal-melee/fire-axe' => 'https://media.hitmaps.com/img/unlockables/item_perspective_a8bc4325-718e-45ba-b0e4-000729c70ce4_0.jpg',
            '/weapons/distractions/radio' => 'https://media.hitmaps.com/img/unlockables/item_perspective_25a4d780-3123-448d-a6e7-3dfdbb8c8260_0.jpg',
            '/weapons/explosives/octane-booster' => 'https://media.hitmaps.com/img/unlockables/item_perspective_c82fefa7-febe-46c8-90ec-c945fbef0cb4_0.jpg',
            '/weapons/non-lethal-melee/soda-can' => 'https://media.hitmaps.com/img/unlockables/item_perspective_004ecac9-6aee-4b30-a073-4399a94535d8_0.jpg',
            '/weapons/distractions/coin' => 'https://media.hitmaps.com/img/unlockables/item_perspective_dda002e9-02b1-4208-82a5-cf059f3c79cf_0.jpg',
            '/weapons/non-lethal-melee/lead-pipe' => 'https://media.hitmaps.com/img/unlockables/item_perspective_7aeb740f-3d60-4e49-8d27-15a98067ce9f_0.jpg',
            '/weapons/explosives/fire-extinguisher' => 'https://media.hitmaps.com/img/unlockables/item_perspective_d64eb5f2-1e9b-402d-855b-c714cfde50db_0.jpg',
            '/weapons/non-lethal-melee/android-arm' => 'https://media.hitmaps.com/img/unlockables/item_perspective_d1f29c76-5751-4e06-b534-e6eb7522b128_0.jpg',
            '/weapons/non-lethal-melee/wrench' => 'https://media.hitmaps.com/img/unlockables/item_perspective_6adddf7e-6879-4d51-a7e2-6a25ffdca6ae_0.jpg',
            '/weapons/poisons/rat-poison' => 'https://media.hitmaps.com/img/unlockables_override/item_perspective_8b37a3a8-8a20-4262-81c5-0fcd15f4bba9_0.jpg',
            '/weapons/lethal-melee/scissors' => 'https://media.hitmaps.com/img/unlockables/item_perspective_6ecf1f15-453c-4783-9c70-8777c83934d7_0.jpg',
            '/weapons/explosives/propane-flask' => 'https://media.hitmaps.com/img/unlockables/item_perspective_a8a0c154-c36f-413e-8f29-b83a1b7a22f0_0.jpg',
            '/weapons/distractions/apricot' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e755471f-e6fd-438f-b343-7c98fbb50107_0.jpg',
            '/weapons/smgs/dak-x2-covert' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e638b949-9b96-4c41-bec4-0a8fbfb05c75_0.jpg',
            '/weapons/pistols/bartoli-75r' => 'https://media.hitmaps.com/img/unlockables/item_perspective_55ed7196-2303-4af6-9fa3-45b691134561_0.jpg',
            '/weapons/lethal-melee/kitchen-knife' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e17172cc-bf70-4df6-9828-d9856b1a24fd_0.jpg',
            '/weapons/non-lethal-melee/baseball' => 'https://media.hitmaps.com/img/unlockables/item_perspective_ce633778-7424-4784-8bc2-f9d717a23709_0.jpg',
            '/weapons/non-lethal-melee/police-baton' => 'https://media.hitmaps.com/img/unlockables/item_perspective_510c62c2-1f40-4a4d-9e42-da677bd116e7_0.jpg',
            '/weapons/non-lethal-melee/golf-club' => 'https://media.hitmaps.com/img/unlockables/item_perspective_17615866-32e7-4e1e-951d-7ef2ada796e9_0.jpg',
            '/weapons/poisons/lethal-poisonous-frog' => 'https://media.hitmaps.com/img/unlockables_override/item_perspective_963123fd-8a53-41b6-8950-335495b3f3af_0.jpg',
            '/weapons/shotguns/tactical-bartoli-12g' => 'https://media.hitmaps.com/img/unlockables/item_perspective_901a3b51-51a0-4236-bdf2-23d20696b358_0.jpg',
            '/weapons/lethal-melee/battle-axe' => 'https://media.hitmaps.com/img/unlockables/item_perspective_58dceb1c-d7db-41dc-9750-55e3ab87fdf0_0.jpg',
            '/weapons/lethal-melee/old-axe' => 'https://media.hitmaps.com/img/unlockables/item_perspective_369c68f7-cbef-4e45-83c7-8acd0dc2d237_0.jpg',
            '/weapons/non-lethal-melee/bust' => 'https://media.hitmaps.com/img/unlockables/item_perspective_a6bcac8b-9772-424e-b2c4-3bdb4da0e349_0.jpg',
            '/weapons/lethal-melee/saber' => 'https://media.hitmaps.com/img/unlockables/item_perspective_94f52181-b9ec-4363-baef-d53b4e424b74_0.jpg',
            '/weapons/lethal-melee/cleaver' => 'https://media.hitmaps.com/img/unlockables/item_perspective_1bbf0ed5-0515-4599-a4c9-454ce59cff44_0.jpg',
            '/weapons/non-lethal-melee/fire-poker' => 'https://media.hitmaps.com/img/unlockables/item_perspective_987d9c9f-203d-44d9-bbf8-bf703f349565_0.jpg',
            '/weapons/non-lethal-melee/baseball-bat' => 'https://media.hitmaps.com/img/unlockables/item_perspective_248cbd89-9923-452a-8cda-a5f76d8930dc_0.jpg',
            '/weapons/lethal-melee/hatchet' => 'https://media.hitmaps.com/img/unlockables/item_perspective_3a8207bb-84f5-438f-8f30-5c83aef2af80_0.jpg',
            '/weapons/non-lethal-melee/car-battery' => 'https://media.hitmaps.com/img/unlockables/item_perspective_dac32c27-4c49-4933-bccb-56c8f526515f_0.jpg',
            '/weapons/lethal-melee/scalpel' => 'https://media.hitmaps.com/img/unlockables/item_perspective_5d8ca32a-fe4c-4597-b074-51e36c3de898_0.jpg',
            '/weapons/poisons/lethal-poison-pill-jar' => 'https://media.hitmaps.com/img/unlockables/item_perspective_ecf7f361-c2aa-4d96-b66d-e973c3e87154_0.jpg',
            '/weapons/lethal-melee/letter-opener' => 'https://media.hitmaps.com/img/unlockables/item_perspective_f1f89faf-a441-4492-b442-9a923b5ecfd1_0.jpg',
            '/weapons/pistols/rude-ruby' => 'https://media.hitmaps.com/img/unlockables/item_perspective_092f6514-c34e-4d04-8d28-7ebbe14230d1_0.jpg',
            '/weapons/rifles/fusil-g2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_d8aa6eba-0cb7-4ed4-ab99-975f2793d731_0.jpg',
            '/weapons/explosives/remote-explosive' => 'https://media.hitmaps.com/img/unlockables/item_perspective_7c691c03-7c6b-4eb4-9a68-898efe5eedaa_0.jpg',
            '/weapons/non-lethal-melee/coconut' => 'https://media.hitmaps.com/img/unlockables/item_perspective_22183fd3-d837-47c6-9c44-05637300af93_0.jpg',
            '/weapons/lethal-melee/amputation-knife' => 'https://media.hitmaps.com/img/unlockables/item_perspective_62c2ac2e-329e-4648-822a-e45a29a93cd0_0.jpg',
            '/weapons/non-lethal-melee/brick' => 'https://media.hitmaps.com/img/unlockables/item_perspective_5cc4d1ea-b4fa-4667-ba3a-b6e859f03059_0.jpg',
            '/weapons/smgs/dak-x2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_d75bef38-8a65-45f6-9cd1-ca5e23e2f79a_0.jpg',
            '/weapons/pistols/hackl-9s-covert' => 'https://media.hitmaps.com/img/unlockables/item_perspective_35efd6dc-0387-4b56-83f0-4e6609bac93f_0.jpg',
            '/weapons/rifles/rs-15' => 'https://media.hitmaps.com/img/unlockables/item_perspective_2f6eec38-45ea-49df-83a2-0b98a858e60a_0.jpg',
            '/weapons/rifles/tac-4-ar-auto' => 'https://media.hitmaps.com/img/unlockables/item_perspective_a15af673-8e21-47e3-bdfa-f5dea7b5f9e9_0.jpg',
            '/weapons/rifles/fusil-g1-4c' => 'https://media.hitmaps.com/img/unlockables/item_perspective_6b93848c-8f1d-42eb-816f-bab61b56d8a5_0.jpg',
            '/weapons/shotguns/bartoli-12g' => 'https://media.hitmaps.com/img/unlockables/item_perspective_7f31d897-a62f-448c-be0d-79d565e2faa7_0.jpg',
            '/weapons/rifles/shashka-a33' => 'https://media.hitmaps.com/img/unlockables/item_perspective_6e4afb04-417e-4cfc-aaa2-43f3ecca9037_0.jpg',
            '/weapons/non-lethal-melee/pool-ball' => 'https://media.hitmaps.com/img/unlockables/item_perspective_95d1c5bd-72de-4236-97c0-b96fc5d92fa8_0.jpg',
            '/weapons/lethal-melee/folding-knife' => 'https://media.hitmaps.com/img/unlockables/item_perspective_a2c56798-026f-4d0b-9480-de0d2525a119_0.jpg',
            '/weapons/lethal-melee/circumcision-knife' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e312a416-5b56-4cb5-8994-1d4bc82fbb84_0.jpg',
            '/weapons/non-lethal-melee/toy-tank' => 'https://media.hitmaps.com/img/unlockables/item_perspective_ce8e7099-e60d-47e8-bfd6-4918777f2c8b_0.jpg',
            '/weapons/lethal-melee/combat-knife' => 'https://media.hitmaps.com/img/unlockables/item_perspective_2c037ef5-a01b-4532-8216-1d535193a837_0.jpg',
            '/weapons/non-lethal-melee/cowboy-bust' => 'https://media.hitmaps.com/img/unlockables/item_perspective_c86ce2f4-7bd1-4949-acc4-54e5428d9396_0.jpg',
            '/weapons/lethal-melee/katana' => 'https://media.hitmaps.com/img/unlockables/item_perspective_5631dace-7f4a-4df8-8e97-b47373b815ff_0.jpg',
            '/weapons/rifles/hx-7' => 'https://media.hitmaps.com/img/unlockables/item_perspective_3fd9825d-8aa5-48e0-97a9-ec8f541f871a_0.jpg',
            '/weapons/rifles/shashka-a33-gold' => 'https://media.hitmaps.com/img/unlockables/item_perspective_79b48d90-26aa-4b17-9332-599ed8e0bd7f_0.jpg',
            '/weapons/rifles/fusil-g1-4' => 'https://media.hitmaps.com/img/unlockables/item_perspective_16edb112-58cc-4069-a7dd-ebd258b14044_0.jpg',
            '/weapons/rifles/hx-10' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e206ed81-0559-4289-9fec-e6a3e9d4ee7c_0.jpg',
            '/weapons/non-lethal-melee/branding-iron' => 'https://media.hitmaps.com/img/unlockables/item_perspective_51f6ed96-4985-4d09-8218-e3b912d025b9_0.jpg',
            '/weapons/non-lethal-melee/mannequin-arm' => 'https://media.hitmaps.com/img/unlockables/item_perspective_8d937ed4-dc85-476b-8048-e96a8900e7bf_0.jpg',
            '/weapons/rifles/hx-7-covert' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e5b2a2fb-8655-4462-9f05-3b02d0a86299_0.jpg',
            '/weapons/poisons/lethal-poison' => 'https://media.hitmaps.com/img/unlockables/item_perspective_cbf40151-cb96-435a-b683-6430370a07f2_0.jpg',
            '/weapons/pistols/striker' => 'https://media.hitmaps.com/img/unlockables/item_perspective_15291f69-88d0-4a8f-b31b-71605ba5ff38_0.jpg',
            '/weapons/shotguns/bartoli-12g-short-h' => 'https://media.hitmaps.com/img/unlockables/item_perspective_eca66732-a356-4c13-8e33-d0f7e87b5860_0.jpg',
            '/weapons/shotguns/enram-hv-covert' => 'https://media.hitmaps.com/img/unlockables/item_perspective_545ff36e-b43c-4a35-9ab3-680b23b9e354_0.jpg',
            '/weapons/pistols/concept-5' => 'https://media.hitmaps.com/img/unlockables/item_perspective_4e66bf97-e6da-4cb6-b873-10a9af274bf4_0.jpg',
            '/weapons/pistols/hwk-21-covert' => 'https://media.hitmaps.com/img/unlockables/item_perspective_f91cf558-04a5-4fd8-8814-b1c765668b39_0.jpg',
            '/weapons/pistols/hackl-9s' => 'https://media.hitmaps.com/img/unlockables/item_perspective_1e11fbea-cd51-48bf-8316-a050772d6135_0.jpg',
            '/weapons/pistols/el-matador' => 'https://media.hitmaps.com/img/unlockables/item_perspective_c44d3dc4-5e2f-403d-9f69-40111f3643db_0.jpg',
            '/weapons/pistols/kalmer-1' => 'https://media.hitmaps.com/img/unlockables/item_perspective_d0438077-24eb-4c1d-a0f3-abd53d18fc27_0.jpg',
            '/weapons/pistols/custom-5mm' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e55c71d6-cbf6-41b8-8838-2d1be1d07e1c_0.jpg',
            '/weapons/pistols/hwk-21-2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_304fd49f-0624-4691-8506-149a4b16808e_0.jpg',
            '/weapons/pistols/ica-19' => 'https://media.hitmaps.com/img/unlockables/item_perspective_73875794-5a86-410e-84a4-1b5b2f7e5a54_0.jpg',
            '/weapons/pistols/ica-19-fa' => 'https://media.hitmaps.com/img/unlockables/item_perspective_be4e7b4e-d895-47c1-979d-d79bfbe79a02_0.jpg',
            '/weapons/pistols/ica-19-fa-stealth' => 'https://media.hitmaps.com/img/unlockables/item_perspective_214004ec-5c86-4c26-8403-9e83a9bcdd24_0.jpg',
            '/weapons/pistols/ica-19-black-lilly' => 'https://media.hitmaps.com/img/unlockables/item_perspective_f93b99a3-aef6-419f-b303-59470577696d_0.jpg',
            '/weapons/pistols/ica-19-silverballer' => 'https://media.hitmaps.com/img/unlockables/item_perspective_e70adb5b-0646-4f88-bd4a-85bea7a2a654_0.jpg',
            '/weapons/pistols/ica-19-silverballer-2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_4bbe3b64-8bce-416e-a3e9-00bcd1571980_0.jpg',
            '/weapons/pistols/ica-19-chrome' => 'https://media.hitmaps.com/img/unlockables/item_perspective_341ba426-d52d-4ae3-97a9-40b9b3633d76_0.jpg',
            '/weapons/pistols/krugermeier-2-2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_c8a09c31-a53e-436f-8421-a4dc4115f633_0.jpg',
            '/weapons/smgs/tac-smg' => 'https://media.hitmaps.com/img/unlockables/item_perspective_ba102d90-b8c9-47b9-97eb-b462344b46c3_0.jpg',
            '/weapons/smgs/tac-smg-2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_224822b8-7c8b-4c42-9194-8b307eadb31b_0.jpg',
            '/weapons/smgs/tac-smg-s' => 'https://media.hitmaps.com/img/unlockables/item_perspective_7bf3a6e6-b5aa-4c88-b953-c2c378d36118_0.jpg',
            '/weapons/smgs/tac-smg-covert' => 'https://media.hitmaps.com/img/unlockables/item_perspective_b1cb79d7-9960-4d5c-8b43-81213c8594cd_0.jpg',
            '/weapons/shotguns/enram-hv' => 'https://media.hitmaps.com/img/unlockables/item_perspective_d5728a0f-fe8d-4e2d-9350-03cf4243c98e_0.jpg',
            '/weapons/shotguns/enram-hv-cm' => 'https://media.hitmaps.com/img/unlockables/item_perspective_f6657618-d723-419f-a71b-84d0e93402e3_0.jpg',
            '/weapons/shotguns/enram-hv-covert-2' => 'https://media.hitmaps.com/img/unlockables/item_perspective_b0314606-caa4-4a2c-a3e2-bd011f98cfdf_0.jpg',
            '/weapons/rifles/shashka-a33-h' => 'https://media.hitmaps.com/img/unlockables/item_perspective_f5ebb935-9bec-422b-b772-37adc3ba23db_0.jpg',
            '/weapons/rifles/tac-4-ar-stealth' => 'https://media.hitmaps.com/img/unlockables/item_perspective_7373fafa-7adb-4c14-ac02-225895f9eb7f_0.jpg',
            '/weapons/rifles/tac-4-sa' => 'https://media.hitmaps.com/img/unlockables/item_perspective_7e1b2364-a190-41f7-a16d-a7d7a9a2f623_0.jpg',
            '/weapons/pistols/hackl-9r' => 'https://media.hitmaps.com/img/unlockables/item_perspective_1cae7d71-55c8-401a-9dfb-cd0909c4f6ee_0.jpg',
        ];
    }

    public function down() {
        foreach ($this->getReplacementMap() as $old => $new) {
            $this->execute("UPDATE `nodes` SET `image` = '{$old}' WHERE `image` = '{$new}'");
            $this->execute("UPDATE `items` SET `image` = '{$old}' WHERE `image` = '{$new}'");
        }
    }
}
