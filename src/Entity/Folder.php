<?php

namespace App\Entity;

use App\Entity\Trait\AtDateTrait;
use App\Repository\FolderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FolderRepository::class)]
#[Vich\Uploadable]
class Folder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'folders', fileNameProperty: 'name')]
    private ?File $folderFile = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'folders')]
    private ?Task $task = null;

    use AtDateTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFolderFile(?File $folderFile = null): void
    {
        $this->folderFile = $folderFile;

        if (null !== $folderFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFolderFile(): ?File
    {
        return $this->folderFile;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }
}
