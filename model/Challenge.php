<?php

class Challenge
{
    private $id;
    private $theme;
    private $description;
    private $monthYear;
    private $createdAt;

    // Constructor
    public function __construct($id = null, $theme = null, $description = null, $monthYear = null, $createdAt = null)
    {
        $this->id = $id;
        $this->theme = $theme;
        $this->description = $description;
        $this->monthYear = $monthYear;
        $this->createdAt = $createdAt;
    }

    // Getters and Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getMonthYear()
    {
        return $this->monthYear;
    }

    public function setMonthYear($monthYear)
    {
        $this->monthYear = $monthYear;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}

?>
