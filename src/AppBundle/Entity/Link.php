<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LinkRepository")
 */
class Link
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, unique=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="addUser", type="string", length=255)
     */
    private $addUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = false;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(name="categories", type="integer")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="SubCategory", inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(name="sous_categories", type="integer")
     */
    private $sousCategories;

    /**
     * @ORM\ManyToOne(targetEntity="Framework", inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(name="framework", type="integer")
     */
    private $frameworks;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Link
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Link
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Link
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set addUser
     *
     * @param string $addUser
     *
     * @return Link
     */
    public function setAddUser($addUser)
    {
        $this->addUser = $addUser;

        return $this;
    }

    /**
     * Get addUser
     *
     * @return string
     */
    public function getAddUser()
    {
        return $this->addUser;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Link
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set categories
     *
     * @param integer $categories
     *
     * @return Link
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return integer
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set sousCategories
     *
     * @param integer $sousCategories
     *
     * @return Link
     */
    public function setSousCategories($sousCategories)
    {
        $this->sousCategories = $sousCategories;

        return $this;
    }

    /**
     * Get sousCategories
     *
     * @return integer
     */
    public function getSousCategories()
    {
        return $this->sousCategories;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Link
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set frameworks
     *
     * @param integer $frameworks
     *
     * @return Link
     */
    public function setFrameworks($frameworks)
    {
        $this->frameworks = $frameworks;

        return $this;
    }

    /**
     * Get frameworks
     *
     * @return integer
     */
    public function getFrameworks()
    {
        return $this->frameworks;
    }
}
