<?php

namespace BusinessLogic\Twitch;


use Config\Settings;

class TwitchStreamRetriever {
    const HITMAN_2_GAME_ID = 506342;

    public function getCurrentStreams() {
        $settings = new Settings();
        $clientID = ['Client-ID:' . $settings->twitchClientId];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'https://api.twitch.tv/helix/streams?game_id='.self::HITMAN_2_GAME_ID);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $clientID);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}