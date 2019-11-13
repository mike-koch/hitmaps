<?php


namespace DataAccess\Models;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="node_categories")
 */
class NodeCategory {
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $type;

    /**
     * @ORM\Column(type="string", name="`group`")
     */
    public $group;

    /**
     * @ORM\Column(type="string")
     */
    public $subgroup;

    /**
     * @ORM\Column(type="string")
     */
    public $icon;

    /**
     * @ORM\Column(type="boolean", name="require_name")
     */
    public $requireName;

    /**
     * @ORM\Column(type="boolean", name="require_action")
     */
    public $requireAction;

    /**
     * @ORM\Column(type="boolean", name="require_target")
     */
    public $requireTarget;

    /**
     * @ORM\Column(type="string")
     */
    public $note;

    /**
     * @ORM\Column(type="boolean", name="require_pickup")
     */
    public $requirePickup;

    /**
     * @ORM\Column(type="boolean", name="require_direction")
     */
    public $requireDirection;

    /**
     * @ORM\Column(type="integer", name="`order`")
     */
    public $order;

    /**
     * @ORM\Column(type="boolean")
     */
    public $searchable;

    /**
     * @ORM\Column(type="boolean")
     */
    public $collapsible;

    /**
     * @ORM\Column(type="boolean", name="for_mission")
     */
    public $forMission;

    /**
     * @ORM\Column(type="boolean", name="for_sniper_assassin")
     */
    public $forSniperAssassin;

    // Only for the editor
    public $defaultQuantity = 1;

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
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group) {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getSubgroup() {
        return $this->subgroup;
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
     * @param mixed $subgroup
     */
    public function setSubgroup($subgroup) {
        $this->subgroup = $subgroup;
    }

    /**
     * @return mixed
     */
    public function getRequireName() {
        return $this->requireName;
    }

    /**
     * @param mixed $requireName
     */
    public function setRequireName($requireName) {
        $this->requireName = $requireName;
    }

    /**
     * @return mixed
     */
    public function getRequireAction() {
        return $this->requireAction;
    }

    /**
     * @param mixed $requireAction
     */
    public function setRequireAction($requireAction) {
        $this->requireAction = $requireAction;
    }

    /**
     * @return mixed
     */
    public function getRequireTarget() {
        return $this->requireTarget;
    }

    /**
     * @param mixed $requireTarget
     */
    public function setRequireTarget($requireTarget) {
        $this->requireTarget = $requireTarget;
    }

    /**
     * @return mixed
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note) {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getRequirePickup() {
        return $this->requirePickup;
    }

    /**
     * @param mixed $requirePickup
     */
    public function setRequirePickup($requirePickup) {
        $this->requirePickup = $requirePickup;
    }

    /**
     * @return mixed
     */
    public function getRequireDirection() {
        return $this->requireDirection;
    }

    /**
     * @param mixed $requireDirection
     */
    public function setRequireDirection($requireDirection) {
        $this->requireDirection = $requireDirection;
    }

    /**
     * @return mixed
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order) {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getSearchable() {
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
    public function getCollapsible() {
        return $this->collapsible;
    }

    /**
     * @param mixed $collapsible
     */
    public function setCollapsible($collapsible): void {
        $this->collapsible = $collapsible;
    }

    /**
     * @return mixed
     */
    public function getForMission() {
        return $this->forMission;
    }

    /**
     * @param mixed $forMission
     */
    public function setForMission($forMission): void {
        $this->forMission = $forMission;
    }

    /**
     * @return mixed
     */
    public function getForSniperAssassin() {
        return $this->forSniperAssassin;
    }

    /**
     * @param mixed $forSniperAssassin
     */
    public function setForSniperAssassin($forSniperAssassin): void {
        $this->forSniperAssassin = $forSniperAssassin;
    }
}