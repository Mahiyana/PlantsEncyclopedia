<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="`image`")
 */

class Image
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    
    /**
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="images")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     */
    private $gallery;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="images")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=100)
     */
    private $name;

     /**
     * @ORM\Column(type="text")
     */
    private $description;

   /**
    * @ORM\Column(type="string")
    *
    * @Assert\NotBlank(message="Please, upload the picture as a jpg file.")
    * @Assert\File(mimeTypes={ "image/*" })
    */
   private $full_size; 
   
   /**
    * @ORM\Column(type="string")
    */
   private $small_size; 

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Image
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
     * Set fullSize
     *
     * @param integer $fullSize
     *
     * @return Image
     */
    public function setFullSize($fullSize)
    {
        $this->full_size = $fullSize;

        return $this;
    }

    /**
     * Get fullSize
     *
     * @return integer
     */
    public function getFullSize()
    {
        return $this->full_size;
    }

    /**
     * Set smallSize
     *
     * @param integer $smallSize
     *
     * @return Image
     */
    public function setSmallSize($smallSize)
    {
        $this->small_size = $smallSize;

        return $this;
    }

    /**
     * Get smallSize
     *
     * @return integer
     */
    public function getSmallSize()
    {
        return $this->small_size;
    }

    /**
     * Set gallery
     *
     * @param \AppBundle\Entity\Gallery $gallery
     *
     * @return Image
     */
    public function setGallery(\AppBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \AppBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Image
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
