<?php
namespace BusinessLogic;


use Abraham\TwitterOAuth\TwitterOAuth;
use Config\Constants;
use Config\Settings;
use DataAccess\Models\ElusiveTarget;
use DataAccess\Models\Mission;
use DataAccess\Repositories\ElusiveTargetRepository;
use DataAccess\Repositories\MissionRepository;
use DG\Twitter\Twitter;
use Doctrine\ORM\EntityManager;
use Rollbar\Rollbar;

class ElusiveTargetNotificationSender {
    private $firebaseClient;
    private $entityManager;
    private $countdownComposer;

    public function __construct(FirebaseClient $firebaseClient,
                                EntityManager $entityManager,
                                CountdownComposer $countdownComposer) {
        $this->firebaseClient = $firebaseClient;
        $this->entityManager = $entityManager;
        $this->countdownComposer = $countdownComposer;
    }

    public function sendElusiveTargetNotification() {
        // Get the active ET from the database
        /* @var $missionRepository MissionRepository */
        /* @var $elusiveTargetRepository ElusiveTargetRepository */
        $missionRepository = $this->entityManager->getRepository(Mission::class);
        $elusiveTargetRepository = $this->entityManager->getRepository(ElusiveTarget::class);

        /* @var $elusiveTargets ElusiveTarget[] */
        $elusiveTargets = $elusiveTargetRepository->findBy(['endNotificationSent' => false], ['id' => 'DESC']);

        foreach ($elusiveTargets as $elusiveTarget) {
            // If an ET is coming up and no notification has been sent, send out the notification
            $settings = new Settings();
            $constants = new Constants();
            $twitter = new TwitterOAuth($settings->twitterConsumerKey,
                $settings->twitterConsumerSecret,
                $settings->twitterAccessToken,
                $settings->twitterAccessTokenSecret);
            $environment = $settings->loggingEnvironment;
            $beginningDate = $elusiveTarget->getBeginningTime()->format('F j, Y');
            $currentUtcTimeForNumberOfDays = new \DateTime('now', new \DateTimeZone('UTC'));
            $currentUtcTimeForNumberOfDays->modify('-1 day');
            $availableDays = $elusiveTarget->getEndingTime()->diff($currentUtcTimeForNumberOfDays)->format('%a');
            $url = $constants->siteDomain . $missionRepository->buildUrlForMissionAndDifficulty($elusiveTarget->getMissionId(), 'professional');

            if (!$elusiveTarget->getComingNotificationSent()) {
                $beginningDateForComparison = $elusiveTarget->getBeginningTime();
                $availableDays = $elusiveTarget->getEndingTime()->diff($beginningDateForComparison)->format('%a');

                if ($elusiveTarget->getReactivated()) {
                    $message = [
                        'title' => 'Reactivated Elusive Target Arriving',
                        'body' => "{$elusiveTarget->getName()} will be reactivated on {$beginningDate} and will be available for {$availableDays} days!",
                        'firebaseEnvironment' => "{$environment}-reactivation"
                    ];
                } else {
                    $message = [
                        'title' => 'Elusive Target Arriving',
                        'body' => "{$elusiveTarget->getName()} is arriving on {$beginningDate} and will be available for {$availableDays} days!",
                        'firebaseEnvironment' => $environment
                    ];
                }

                $title = $message['title'];
                $body = $message['body'];
                $firebaseEnvironment = $message['firebaseEnvironment'];
                $imageUrl = "/img/jpg{$elusiveTarget->getImageUrl()}.jpg";
                $response = $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-coming",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    "{$constants->siteDomain}{$imageUrl}",
                    $url);
                $media = $twitter->upload('media/upload', ['media' => __DIR__ . "/../..{$imageUrl}"]);
                $twitter->post('statuses/update', [
                    'status' => $body,
                    'media_ids' => $media->media_id_string
                ]);

                $elusiveTarget->setComingNotificationSent(true);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();

                Rollbar::info("Sent Notification: {$elusiveTarget->getName()} is arriving on {$beginningDate} and will be available for {$availableDays} days!", ['firebaseResponse' => $response]);
                return;
            }

            // If the ET isn't playable, don't proceed past here
            $realUtcTime = new \DateTime('now', new \DateTimeZone('UTC'));
            if ($realUtcTime < $elusiveTarget->getBeginningTime()) {
                return;
            }

            if ($availableDays > 7 && !$elusiveTarget->getPlayableNotificationSent()) {
                if ($elusiveTarget->getReactivated()) {
                    $message = [
                        'title' => 'Reactivated Elusive Target Arrived',
                        'firebaseEnvironment' => "{$environment}-reactivation"
                    ];
                } else {
                    $message = [
                        'title' => 'Elusive Target Arriving',
                        'firebaseEnvironment' => $environment
                    ];
                }

                $title = $message['title'];
                $body = "{$elusiveTarget->getName()} has arrived and will be available for {$availableDays} days!";
                $firebaseEnvironment = $message['firebaseEnvironment'];
                $imageUrl = $this->countdownComposer->composeElusiveTargetActiveImage($elusiveTarget);
                $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-playable",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    "{$constants->siteDomain}{$imageUrl}",
                    $url);
                $media = $twitter->upload('media/upload', ['media' => __DIR__ . "/../..{$imageUrl}"]);
                $twitter->post('statuses/update', [
                    'status' => $body,
                    'media_ids' => $media->media_id_string
                ]);

                $elusiveTarget->setPlayableNotificationSent(true);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();
                return;
            }

            if ($availableDays <= '7' && $availableDays > '5' && !$elusiveTarget->getSevenDaysLeftNotificationSent()) {
                $title = "{$elusiveTarget->getName()} - 7 Days Left";
                $body = "{$elusiveTarget->getName()} will be leaving in 7 days! Plan accordingly.";
                $firebaseEnvironment = $elusiveTarget->getReactivated() ? "{$environment}-reactivation" : $environment;
                $countdownImage = $this->countdownComposer->composeElusiveTargetImage($elusiveTarget, 7);
                $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-7",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    "{$constants->siteDomain}{$countdownImage}",
                    $url);
                $media = $twitter->upload('media/upload', ['media' => __DIR__ . "/../..{$countdownImage}"]);
                $twitter->post('statuses/update', [
                    'status' => $body,
                    'media_ids' => $media->media_id_string
                ]);

                $elusiveTarget->setSevenDaysLeftNotificationSent(true);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();
                return;
            }
            if ($availableDays <= '5' && $availableDays > '3' && !$elusiveTarget->getFiveDaysLeftNotificationSent()) {
                $title = "{$elusiveTarget->getName()} - 5 Days Left";
                $body = "{$elusiveTarget->getName()} will be leaving in 5 days. Be sure to eliminate the target before time is up.";
                $firebaseEnvironment = $elusiveTarget->getReactivated() ? "{$environment}-reactivation" : $environment;
                $countdownImage = $this->countdownComposer->composeElusiveTargetImage($elusiveTarget, 5);
                $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-5",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    "{$constants->siteDomain}{$countdownImage}",
                    $url);
                $media = $twitter->upload('media/upload', ['media' => __DIR__ . "/../..{$countdownImage}"]);
                $twitter->post('statuses/update', [
                    'status' => $body,
                    'media_ids' => $media->media_id_string
                ]);

                $elusiveTarget->setFiveDaysLeftNotificationSent(true);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();
                return;
            }
            if ($availableDays <= '3' && $availableDays > '1' && !$elusiveTarget->getThreeDaysLeftNotificationSent()) {
                $title = "{$elusiveTarget->getName()} - 3 Days Left";
                $body = "The contract on {$elusiveTarget->getName()} is only active for 3 more days! Eliminate the target before it's too late.";
                $firebaseEnvironment = $elusiveTarget->getReactivated() ? "{$environment}-reactivation" : $environment;
                $countdownImage = $this->countdownComposer->composeElusiveTargetImage($elusiveTarget, 3);
                $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-3",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    "{$constants->siteDomain}{$countdownImage}",
                    $url);
                $media = $twitter->upload('media/upload', ['media' => __DIR__ . "/../..{$countdownImage}"]);
                $twitter->post('statuses/update', [
                    'status' => $body,
                    'media_ids' => $media->media_id_string
                ]);

                $elusiveTarget->setThreeDaysLeftNotificationSent(true);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();
                return;
            }
            if ($availableDays <= '1' && $availableDays > '0' && !$elusiveTarget->getOneDayLeftNotificationSent()) {
                $title = "{$elusiveTarget->getName()} - Only One Day Left";
                $body = "{$elusiveTarget->getName()} will be leaving in just 24 hours. If you have not eliminated the target, there is not much time left!";
                $firebaseEnvironment = $elusiveTarget->getReactivated() ? "{$environment}-reactivation" : $environment;
                $countdownImage = $this->countdownComposer->composeElusiveTargetImage($elusiveTarget, 1);
                $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-1",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    "{$constants->siteDomain}{$countdownImage}",
                    $url);
                $media = $twitter->upload('media/upload', ['media' => __DIR__ . "/../..{$countdownImage}"]);
                $twitter->post('statuses/update', [
                    'status' => $body,
                    'media_ids' => $media->media_id_string
                ]);

                $elusiveTarget->setOneDayLeftNotificationSent(true);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();
                return;
            }

            if ($realUtcTime > $elusiveTarget->getEndingTime() && !$elusiveTarget->getEndNotificationSent()) {
                $title = "{$elusiveTarget->getName()} Has Left";
                $body = "{$elusiveTarget->getName()} has left and is no longer available to play.";
                $firebaseEnvironment = $elusiveTarget->getReactivated() ? "{$environment}-reactivation" : $environment;
                $this->firebaseClient->sendElusiveTargetMessage("hitmaps-{$firebaseEnvironment}-elusive-target-end",
                    $title,
                    $body,
                    "{$constants->siteDomain}/android-chrome-256x256.png",
                    $constants->siteDomain);

                $elusiveTarget->setEndNotificationSent(true);
                $this->countdownComposer->deleteAllCompositeImages($elusiveTarget);
                $this->entityManager->persist($elusiveTarget);
                $this->entityManager->flush();
                return;
            }
        }
    }
}
