<?php


namespace DataAccess\Models;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DataAccess\Repositories\MissionRepository")
 * @ORM\Table(name="missions")
 */
class Mission {
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="integer", name="location_id")
     */
    public $locationId;

    /**
     * @ORM\Column(type="integer", name="campaign_id")
     */
    public $campaignId;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="string")
     */
    public $slug;

    /**
     * @ORM\Column(type="integer", name="`order`")
     */
    public $order;


    /**
     * @ORM\Column(type="string", name="map_folder_name")
     */
    public $mapFolderName;

    /**
     * @ORM\Column(type="string", name="map_center_latitude")
     */
    public $mapCenterLatitude;

    /**
     * @ORM\Column(type="string", name="map_center_longitude")
     */
    public $mapCenterLongitude;

    /**
     * @ORM\Column(type="integer", name="lowest_floor_number")
     */
    public $lowestFloorNumber;

    /**
     * @ORM\Column(type="integer", name="highest_floor_number")
     */
    public $highestFloorNumber;

    /**
     * @ORM\Column(type="integer", name="starting_floor_number")
     */
    public $startingFloorNumber;

    /**
     * @ORM\Column(type="string", name="top_left_coordinate")
     */
    public $topLeftCoordinate;

    /**
     * @ORM\Column(type="string", name="bottom_right_coordinate")
     */
    public $bottomRightCoordinate;

    /**
     * @ORM\Column(type="boolean", name="satellite_view")
     */
    public $satelliteView;

    /**
     * @ORM\Column(type="datetime", name="begin_effective_date")
     */
    public $beginEffectiveDate;

    /**
     * @ORM\Column(type="datetime", name="end_effective_date")
     */
    public $endEffectiveDate;

    /**
     * @ORM\Column(type="string", name="mission_type")
     */
    public $missionType;

    // Not used by Doctrine
    /* @var $difficulties string[] */
    public $difficulties = [];

    /**
     * @ORM\Column(type="string", name="background_url")
     */
    public $backgroundUrl;

    /**
     * @ORM\Column(type="string", name="tile_url")
     */
    public $tileUrl;

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
    public function getLocationId(): int {
        return $this->locationId;
    }

    /**
     * @param mixed $locationId
     */
    public function setLocationId(int $locationId): void {
        $this->locationId = $locationId;
    }

    /**
     * @return mixed
     */
    public function getCampaignId(): ?int {
        return $this->campaignId;
    }

    /**
     * @param mixed $campaignId
     */
    public function setCampaignId(?int $campaignId): void {
        $this->campaignId = $campaignId;
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
    public function getSlug(): string {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug(string $slug): void {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getOrder(): int {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder(int $order): void {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getMapFolderName(): string {
        return $this->mapFolderName;
    }

    /**
     * @param mixed $mapFolderName
     */
    public function setMapFolderName($mapFolderName) {
        $this->mapFolderName = $mapFolderName;
    }



    /**
     * @return mixed
     */
    public function getMapCenterLatitude(): string {
        return $this->mapCenterLatitude;
    }

    /**
     * @param mixed $mapCenterLatitude
     */
    public function setMapCenterLatitude($mapCenterLatitude): void {
        $this->mapCenterLatitude = $mapCenterLatitude;
    }

    /**
     * @return mixed
     */
    public function getMapCenterLongitude(): string {
        return $this->mapCenterLongitude;
    }

    /**
     * @param mixed $mapCenterLongitude
     */
    public function setMapCenterLongitude($mapCenterLongitude): void {
        $this->mapCenterLongitude = $mapCenterLongitude;
    }

    /**
     * @return mixed
     */
    public function getLowestFloorNumber() {
        return $this->lowestFloorNumber;
    }

    /**
     * @param mixed $lowestFloorNumber
     */
    public function setLowestFloorNumber($lowestFloorNumber) {
        $this->lowestFloorNumber = $lowestFloorNumber;
    }

    /**
     * @return mixed
     */
    public function getHighestFloorNumber() {
        return $this->highestFloorNumber;
    }

    /**
     * @param mixed $highestFloorNumber
     */
    public function setHighestFloorNumber($highestFloorNumber) {
        $this->highestFloorNumber = $highestFloorNumber;
    }

    /**
     * @return mixed
     */
    public function getStartingFloorNumber() {
        return $this->startingFloorNumber;
    }

    /**
     * @param mixed $startingFloorNumber
     */
    public function setStartingFloorNumber($startingFloorNumber) {
        $this->startingFloorNumber = $startingFloorNumber;
    }

    /**
     * @return mixed
     */
    public function getTopLeftCoordinate() {
        return $this->topLeftCoordinate;
    }

    /**
     * @param mixed $topLeftCoordinate
     */
    public function setTopLeftCoordinate($topLeftCoordinate) {
        $this->topLeftCoordinate = $topLeftCoordinate;
    }

    /**
     * @return mixed
     */
    public function getBottomRightCoordinate() {
        return $this->bottomRightCoordinate;
    }

    /**
     * @param mixed $bottomRightCoordinate
     */
    public function setBottomRightCoordinate($bottomRightCoordinate) {
        $this->bottomRightCoordinate = $bottomRightCoordinate;
    }

    /**
     * @return mixed
     */
    public function getSatelliteView() {
        return $this->satelliteView;
    }

    /**
     * @param mixed $satelliteView
     */
    public function setSatelliteView($satelliteView): void {
        $this->satelliteView = $satelliteView;
    }

    /**
     * @return mixed
     */
    public function getBeginEffectiveDate() {
        return $this->beginEffectiveDate;
    }

    /**
     * @param mixed $beginEffectiveDate
     */
    public function setBeginEffectiveDate($beginEffectiveDate): void {
        $this->beginEffectiveDate = $beginEffectiveDate;
    }

    /**
     * @return mixed
     */
    public function getEndEffectiveDate() {
        return $this->endEffectiveDate;
    }

    /**
     * @param mixed $endEffectiveDate
     */
    public function setEndEffectiveDate($endEffectiveDate): void {
        $this->endEffectiveDate = $endEffectiveDate;
    }

    /**
     * @return mixed
     */
    public function getMissionType(): string {
        return $this->missionType;
    }

    /**
     * @param mixed $missionType
     */
    public function setMissionType(string $missionType): void {
        $this->missionType = $missionType;
    }

    /**
     * @return string
     */
    public function getBackgroundUrl(): string {
        return $this->backgroundUrl;
    }

    /**
     * @param string $backgroundUrl
     */
    public function setBackgroundUrl(string $backgroundUrl): void {
        $this->backgroundUrl = $backgroundUrl;
    }

    /**
     * @return mixed
     */
    public function getTileUrl() {
        return $this->tileUrl;
    }

    /**
     * @param mixed $tileUrl
     */
    public function setTileUrl($tileUrl): void {
        $this->tileUrl = $tileUrl;
    }
}