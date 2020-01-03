<?php

namespace DataAccess\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="games")
 */
class Game {
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $slug;

    /**
     * @ORM\Column(type="string", name="full_name")
     */
    public $fullName;

    /**
     * @ORM\Column(type="string")
     */
    public $tagline;

    /**
     * @ORM\Column(type="string")
     */
    public $type;

    /**
     * @ORM\Column(type="string")
     */
    public $icon;

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
    public function setId(int $id): void {
        $this->id = $id;
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
    public function getFullName(): string {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName(string $fullName): void {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getTagline(): string {
        return $this->tagline;
    }

    /**
     * @param mixed $tagline
     */
    public function setTagline(string $tagline): void {
        $this->tagline = $tagline;
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
    public function setType($type): void {
        $this->type = $type;
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