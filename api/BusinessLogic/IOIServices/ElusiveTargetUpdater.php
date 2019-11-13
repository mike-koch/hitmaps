<?php
namespace BusinessLogic\IOIServices;

use BusinessLogic\MissionCloner;
use Config\Settings;
use DataAccess\Models\ElusiveTarget;
use DataAccess\Models\Mission;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Rollbar\Rollbar;

class ElusiveTargetUpdater {
    private $entityManager;
    private $missionCloner;
    private $locationToSlugMap = [
        // HITMAN
        "Paris, France" => [
            'slug' => 'the-showstopper',
            'tileSaveLocation' => '/elusive-targets/legacy/'
        ],
        "Sapienza, Italy" => [
            'slug' => 'world-of-tomorrow',
            'tileSaveLocation' => '/elusive-targets/legacy/'
        ],
        "Marrakesh, Morocco" => [
            'slug' => 'a-gilded-cage',
            'tileSaveLocation' => '/elusive-targets/legacy/'
        ],
        "Bangkok, Thailand" => [
            'slug' => 'club-27',
            'tileSaveLocation' => '/elusive-targets/legacy/'
        ],
        "Colorado, USA" => [
            'slug' => 'freedom-fighters',
            'tileSaveLocation' => '/elusive-targets/legacy/'
        ],
        "Hokkaido, Japan" => [
            'slug' => 'situs-inversus',
            'tileSaveLocation' => '/elusive-targets/legacy/'
        ],
        // HITMAN 2
        "Hawke's Bay, New Zealand" => [
            'slug' => 'nightcall',
            'tileSaveLocation' => '/elusive-targets/'
        ],
        "Miami, USA" => [
            'slug' => 'finish-line',
            'tileSaveLocation' => '/elusive-targets/'
        ],
        "Santa Fortuna, Colombia" => [
            'slug' => 'three-headed-serpent',
            'tileSaveLocation' => '/elusive-targets/'
        ],
        "Mumbai, India" => [
            'slug' => 'chasing-a-ghost',
            'tileSaveLocation' => '/elusive-targets/'
        ],
        "Whittleton Creek, USA" => [
            'slug' => 'another-life',
            'tileSaveLocation' => '/elusive-targets/'
        ],
        "Isle of Sgàil, North Atlantic" => [
            'slug' => 'ark-society',
            'tileSaveLocation' => '/elusive-targets/'
        ]
    ];

    public function __construct(EntityManager $entityManager, MissionCloner $missionCloner) {
        $this->entityManager = $entityManager;
        $this->missionCloner = $missionCloner;
    }

    public function retrieveLatestElusiveTargetFromIOI(): bool {
        $client = new Client();
        $response = $client->request('GET', 'https://auth.hitman.io/status', [
            'headers' => [
                'User-Agent' => 'hitmaps.com',
                'Accept' => 'application/json'
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            Rollbar::error('Received a non-success response from the HITMAN Status API: ' . $response->getStatusCode(), [
                'response' => $response
            ]);

            return false;
        }

        $responseJson = json_decode($response->getBody());
        $elusiveTargetJsons = $responseJson->elusives->{'pc2-service.hitman.io'};
        if (empty($elusiveTargetJsons)) {
            return false;
        }

        foreach ($elusiveTargetJsons as $elusiveTargetJson) {
            $elusiveTarget = $this->entityManager->getRepository(ElusiveTarget::class)->findOneBy(['name' => $elusiveTargetJson->name]);

            if ($elusiveTarget === null) {
                $locationInfo = $this->locationToSlugMap[$elusiveTargetJson->location];
                if ($locationInfo === null) {
                    Rollbar::error("Could not find location slug for IOI location: {$elusiveTargetJson->location}!", [
                        'response' => $response
                    ]);
                    return false;
                }
                /* @var $mission Mission */
                $mission = $this->entityManager->getRepository(Mission::class)->findOneBy(['slug' => $locationInfo['slug']]);
                if ($mission === null) {
                    Rollbar::error("Could not find mission with slug '{$locationInfo['slug']}'!", [
                        'response' => $response
                    ]);
                    return false;
                }

                $saveName = str_replace(' ', '-', strtolower($elusiveTargetJson->name));
                $newMissionId = $this->missionCloner->cloneMissionForElusiveTarget($mission->getSlug(),
                    'professional',
                    $elusiveTargetJson->name,
                    $saveName,
                    new \DateTime($elusiveTargetJson->nextWindow->start),
                    new \DateTime($elusiveTargetJson->nextWindow->end));

                $elusiveTarget = new ElusiveTarget();
                $elusiveTarget->setName($elusiveTargetJson->name);
                $image = file_get_contents($elusiveTargetJson->tile);
                $settings = new Settings();
                $fileName = "{$locationInfo['tileSaveLocation']}{$saveName}";
                file_put_contents(__DIR__ . "/../../../img/jpg{$fileName}.jpg", $image);
                $elusiveTarget->setImageUrl($fileName);
                $elusiveTarget->setComingNotificationSent(false);
                $elusiveTarget->setPlayableNotificationSent(false);
                $elusiveTarget->setSevenDaysLeftNotificationSent(false);
                $elusiveTarget->setFiveDaysLeftNotificationSent(false);
                $elusiveTarget->setThreeDaysLeftNotificationSent(false);
                $elusiveTarget->setOneDayLeftNotificationSent(false);
                $elusiveTarget->setEndNotificationSent(false);
                $elusiveTarget->setMissionId($newMissionId);
            }
            $elusiveTarget->setBriefing($elusiveTargetJson->description);
            $elusiveTarget->setBeginningTime(new \DateTime($elusiveTargetJson->nextWindow->start));
            $elusiveTarget->setEndingTime(new \DateTime($elusiveTargetJson->nextWindow->end));
            $this->entityManager->persist($elusiveTarget);
            $this->entityManager->flush();
        }

        return true;
    }
}
