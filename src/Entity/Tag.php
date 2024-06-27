<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'tags')]
#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type('\DateTimeImmutable')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type('\DateTimeImmutable')]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $slug;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $title;

    #[ORM\JoinTable(name: 'tag_relations')]
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'tags')]
    private Collection $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->slug = '';
        $this->title = '';
        $this->tags = new ArrayCollection();
    }

    /**
     * Get the ID of the tag.
     *
     * @return int|null The ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the ID of the tag.
     *
     * @param int $id The ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the creation timestamp of the tag.
     *
     * @return \DateTimeImmutable|null The creation timestamp
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the creation timestamp of the tag.
     *
     * @param \DateTimeImmutable $createdAt The creation timestamp
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the last update timestamp of the tag.
     *
     * @return \DateTimeImmutable|null The last update timestamp
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the last update timestamp of the tag.
     *
     * @param \DateTimeImmutable $updatedAt The last update timestamp
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the slug of the tag.
     *
     * @return string|null The slug
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the slug of the tag.
     *
     * @param string $slug The slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Get the title of the tag.
     *
     * @return string|null The title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the tag.
     *
     * @param string $title The title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get the tags associated with this tag.
     *
     * @return Collection The tags associated with this tag
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag relation to this tag.
     *
     * @param Tag $tag The tag to add
     *
     * @return static
     */
    public function addTag(self $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove a tag relation from this tag.
     *
     * @param Tag $tag The tag to remove
     *
     * @return static
     */
    public function removeTag(self $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            // Do any additional logic here if needed
        }

        return $this;
    }

    /**
     * Returns the string representation of the tag (its title).
     *
     * @return string The title of the tag
     */
    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
