<?php

namespace App\Entity;

use App\Entity\Trait\AtDateTrait;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(length: 255)]
    private ?string $priority = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    use AtDateTrait;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tasks')]
    private Collection $assignedUserId;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Project $projectId = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'taskId')]
    private Collection $comments;

    /**
     * @var Collection<int, SubTask>
     */
    #[ORM\OneToMany(targetEntity: SubTask::class, mappedBy: 'taskId')]
    private Collection $subTasks;

    /**
     * @var Collection<int, Folder>
     */
    #[ORM\OneToMany(targetEntity: Folder::class, mappedBy: 'task')]
    private Collection $folders;

    public function __construct()
    {
        $this->assignedUserId = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->subTasks = new ArrayCollection();
        $this->folders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssignedUserId(): Collection
    {
        return $this->assignedUserId;
    }

    public function addAssignedUserId(User $assignedUserId): static
    {
        if (!$this->assignedUserId->contains($assignedUserId)) {
            $this->assignedUserId->add($assignedUserId);
            $assignedUserId->addTask($this);
        }

        return $this;
    }

    public function removeAssignedUserId(User $assignedUserId): static
    {
        if ($this->assignedUserId->removeElement($assignedUserId)) {
            $assignedUserId->removeTask($this);
        }

        return $this;
    }

    public function getProjectId(): ?Project
    {
        return $this->projectId;
    }

    public function setProjectId(?Project $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTaskId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTaskId() === $this) {
                $comment->setTaskId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubTask>
     */
    public function getSubTasks(): Collection
    {
        return $this->subTasks;
    }

    public function addSubTask(SubTask $subTask): static
    {
        if (!$this->subTasks->contains($subTask)) {
            $this->subTasks->add($subTask);
            $subTask->setTaskId($this);
        }

        return $this;
    }

    public function removeSubTask(SubTask $subTask): static
    {
        if ($this->subTasks->removeElement($subTask)) {
            // set the owning side to null (unless already changed)
            if ($subTask->getTaskId() === $this) {
                $subTask->setTaskId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Folder>
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function addFolder(Folder $folder): static
    {
        if (!$this->folders->contains($folder)) {
            $this->folders->add($folder);
            $folder->setTask($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): static
    {
        if ($this->folders->removeElement($folder)) {
            // set the owning side to null (unless already changed)
            if ($folder->getTask() === $this) {
                $folder->setTask(null);
            }
        }

        return $this;
    }
}
