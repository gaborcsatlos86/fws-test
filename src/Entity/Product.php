<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\{Collection, ArrayCollection};
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\{PrePersistEventArgs, PreUpdateEventArgs};

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: "name", columns: ["name"])]
class Product extends AbstractBaseEntity implements NamedEntityInterface
{
    #[ORM\Column(type: Types::STRING)]
    private string $name;
    
    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $price = 0;
    
    #[ORM\JoinTable(name: 'products_categories')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $categories;
    
    public function __construct() {
        $this->categories = new ArrayCollection();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getPrice(): int
    {
        return $this->price;
    }
    
    public function setPrice(int $price): self
    {
        $this->price = $price;
        
        return $this;
    }
    
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
        
        return $this;
    }
   
    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
        
        return $this;
    }
    
    public function setCategories(ArrayCollection $categories): self
    {
        $this->categories = $categories;
        
        return $this;
    }
    
    public function __toString(): string
    {
        return $this->name;
    }
    
    #[ORM\PrePersist]
    public function onPrePersist(PrePersistEventArgs $eventArgs)
    {
        $this->onCreateSetTimes();
    }
    
    #[ORM\PreUpdate]
    public function onPreUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->onUpdateSetTime();
    }
    
}
