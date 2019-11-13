<?php


namespace DataAccess\Models;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DataAccess\Repositories\NodeRepository")
 * @ORM\Table(name="nodes")
 */
class Node {
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="integer", name="mission_id")
     */
    public $missionId;

    /**
     * @ORM\Column(type="string")
     */
    public $type;

    /**
     * @ORM\Column(type="string")
     */
    public $subgroup;

    /**
     * @ORM\Column(type="string")
     */
    public $icon;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $target;

    /**
     * @ORM\Column(type="integer")
     */
    public $level;

    /**
     * @ORM\Column(type="string")
     */
    public $latitude;

    /**
     * @ORM\Column(type="string")
     */
    public $longitude;

    /**
     * @ORM\Column(type="string")
     */
    public $difficulty;

    /**
     * @ORM\Column(type="string", name="`group`")
     */
    public $group;

    /**
     * @ORM\Column(type="boolean")
     */
    public $approved;

    /**
     * @ORM\Column(type="integer", name="created_by")
     */
    public $createdBy;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    public $dateCreated;

    /**
     * @ORM\Column(type="boolean")
     */
    public $searchable;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $image;

    /**
     * @ORM\Column(type="string")
     */
    public $tooltip;

    /**
     * @ORM\Column(type="integer")
     */
    public $quantity;

    public function __construct() {
        $this->dateCreated = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMissionId(): int {
        return $this->missionId;
    }

    /**
     * @param mixed $missionId
     */
    public function setMissionId(int $missionId): void {
        $this->missionId = $missionId;
    }

    /**
     * @return mixed
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType(string $type): void {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSubgroup(): string {
        return $this->subgroup;
    }

    /**
     * @param mixed $subgroup
     */
    public function setSubgroup(string $subgroup): void {
        $this->subgroup = $subgroup;
    }

    /**
     * @return mixed
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): void {
        $this->icon = $icon;
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
    public function getTarget(): string {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget(string $target): void {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getLevel(): int {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel(int $level): void {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLatitude(): string {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude(string $latitude): void {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude(): string {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude(string $longitude): void {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getDifficulty(): string {
        return $this->difficulty;
    }

    /**
     * @param mixed $difficulty
     */
    public function setDifficulty(string $difficulty): void {
        $this->difficulty = $difficulty;
    }

    public function setGroup(string $group): void {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup(): string {
        return $this->group;
    }

    /**
     * @return mixed
     */
    public function getApproved(): bool {
        return $this->approved;
    }

    /**
     * @param mixed $approved
     */
    public function setApproved(bool $approved) {
        $this->approved = $approved;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy(): int {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy(int $createdBy) {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getDateCreated(): \DateTime {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated(\DateTime $dateCreated): void {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool {
        return $this->searchable;
    }

    /**
     * @param mixed $searchable
     */
    public function setSearchable($searchable): void {
        $this->searchable = $searchable;
    }

    /**
     * @return mixed
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getTooltip() {
        return $this->tooltip;
    }

    /**
     * @param mixed $tooltip
     */
    public function setTooltip($tooltip): void {
        $this->tooltip = $tooltip;
    }

    /**
     * @return mixed
     */
    public function getQuantity(): int {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }
}