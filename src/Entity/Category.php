<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\{PrePersistEventArgs, PreUpdateEventArgs};

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: "name", columns: ["name"])]
class Category extends AbstractBaseEntity implements NamedEntityInterface
{
    #[ORM\Column(type: Types::STRING)]
    private string $name;
    
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
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
