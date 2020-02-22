<?php

namespace DataAccess\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DataAccess\Repositories\ElusiveTargetRepository")
 * @ORM\Table(name="elusive_targets")
 */
class ElusiveTarget {
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="integer", name="mission_id")
     */
    public $missionId;

    /**
     * @ORM\Column(type="string")
     */
    public $briefing;

    /**
     * @ORM\Column(type="string", name="video_briefing_url")
     */
    public $videoBriefingUrl;

    /**
     * @ORM\Column(type="datetime", name="beginning_time")
     */
    public $beginningTime;

    /**
     * @ORM\Column(type="datetime", name="ending_time")
     */
    public $endingTime;

    /**
     * @ORM\Column(type="string", name="image_url")
     */
    public $imageUrl;

    /**
     * @ORM\Column(type="boolean", name="coming_notification_sent")
     */
    private $comingNotificationSent;

    /**
     * @ORM\Column(type="boolean", name="playable_notification_sent")
     */
    private $playableNotificationSent;

    /**
     * @ORM\Column(type="boolean", name="`7_days_left_notification_sent`")
     */
    private $sevenDaysLeftNotificationSent;

    /**
     * @ORM\Column(type="boolean", name="`5_days_left_notification_sent`")
     */
    private $fiveDaysLeftNotificationSent;

    /**
     * @ORM\Column(type="boolean", name="`3_days_left_notification_sent`")
     */
    private $threeDaysLeftNotificationSent;

    /**
     * @ORM\Column(type="boolean", name="`1_day_left_notification_sent`")
     */
    private $oneDayLeftNotificationSent;

    /**
     * @ORM\Column(type="boolean", name="end_notification_sent")
     */
    private $endNotificationSent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reactivated;

    /**
     * @return mixed
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getMissionId() {
        return $this->missionId;
    }

    /**
     * @param mixed $missionId
     */
    public function setMissionId($missionId): void {
        $this->missionId = $missionId;
    }

    /**
     * @return mixed
     */
    public function getBriefing() {
        return $this->briefing;
    }

    /**
     * @param mixed $briefing
     */
    public function setBriefing($briefing): void {
        $this->briefing = $briefing;
    }

    /**
     * @return mixed
     */
    public function getVideoBriefingUrl() {
        return $this->videoBriefingUrl;
    }

    /**
     * @param mixed $videoBriefingUrl
     */
    public function setVideoBriefingUrl($videoBriefingUrl): void {
        $this->videoBriefingUrl = $videoBriefingUrl;
    }

    /**
     * @return \DateTime
     */
    public function getBeginningTime() {
        return $this->beginningTime;
    }

    /**
     * @param mixed $beginningTime
     */
    public function setBeginningTime($beginningTime): void {
        $this->beginningTime = $beginningTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndingTime() {
        return $this->endingTime;
    }

    /**
     * @param mixed $endingTime
     */
    public function setEndingTime($endingTime): void {
        $this->endingTime = $endingTime;
    }

    /**
     * @return mixed
     */
    public function getImageUrl() {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl): void {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return bool
     */
    public function isPlayable(): bool {
        $currentUtcTime = new \DateTime('now', new \DateTimeZone('UTC'));

        return $currentUtcTime > $this->getBeginningTime();
    }

    /**
     * @return mixed
     */
    public function getComingNotificationSent() {
        return $this->comingNotificationSent;
    }

    /**
     * @param mixed $comingNotificationSent
     */
    public function setComingNotificationSent($comingNotificationSent): void {
        $this->comingNotificationSent = $comingNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getPlayableNotificationSent() {
        return $this->playableNotificationSent;
    }

    /**
     * @param mixed $playableNotificationSent
     */
    public function setPlayableNotificationSent($playableNotificationSent): void {
        $this->playableNotificationSent = $playableNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getSevenDaysLeftNotificationSent() {
        return $this->sevenDaysLeftNotificationSent;
    }

    /**
     * @param mixed $sevenDaysLeftNotificationSent
     */
    public function setSevenDaysLeftNotificationSent($sevenDaysLeftNotificationSent): void {
        $this->sevenDaysLeftNotificationSent = $sevenDaysLeftNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getFiveDaysLeftNotificationSent() {
        return $this->fiveDaysLeftNotificationSent;
    }

    /**
     * @param mixed $fiveDaysLeftNotificationSent
     */
    public function setFiveDaysLeftNotificationSent($fiveDaysLeftNotificationSent): void {
        $this->fiveDaysLeftNotificationSent = $fiveDaysLeftNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getThreeDaysLeftNotificationSent() {
        return $this->threeDaysLeftNotificationSent;
    }

    /**
     * @param mixed $threeDaysLeftNotificationSent
     */
    public function setThreeDaysLeftNotificationSent($threeDaysLeftNotificationSent): void {
        $this->threeDaysLeftNotificationSent = $threeDaysLeftNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getOneDayLeftNotificationSent() {
        return $this->oneDayLeftNotificationSent;
    }

    /**
     * @param mixed $oneDayLeftNotificationSent
     */
    public function setOneDayLeftNotificationSent($oneDayLeftNotificationSent): void {
        $this->oneDayLeftNotificationSent = $oneDayLeftNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getEndNotificationSent() {
        return $this->endNotificationSent;
    }

    /**
     * @param mixed $endNotificationSent
     */
    public function setEndNotificationSent($endNotificationSent): void {
        $this->endNotificationSent = $endNotificationSent;
    }

    /**
     * @return mixed
     */
    public function getReactivated() {
        return $this->reactivated;
    }

    /**
     * @param mixed $reactivated
     */
    public function setReactivated($reactivated): void {
        $this->reactivated = $reactivated;
    }
}