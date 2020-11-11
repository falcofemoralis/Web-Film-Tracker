<?php

class Actor
{
    private string $person_id, $name, $category;
    private int $born, $died;
    private array $characters;

    function __construct($person_id, $name, $born, $died, $characters, $category)
    {
        $this->person_id = $person_id;
        $this->name = $name;
        $this->born = $born;
        $this->died = $died;

        $characters = str_replace("N", "", $characters);
        $characters = str_replace('"', "", $characters);
        $characters = str_replace('[', "", $characters);
        $characters = str_replace(']', "", $characters);
        $this->characters = preg_split('/,/', $characters, -1);
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getPersonId(): string
    {
        return $this->person_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getBorn(): int
    {
        return $this->born;
    }

    /**
     * @return int
     */
    public function getDied(): int
    {
        return $this->died;
    }

    /**
     * @return array
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
}