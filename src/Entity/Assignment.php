<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssignmentRepository")
 */
class Assignment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "La note doit être supérieure ou égale à 1",
     *      maxMessage = "La note doit être inférieure ou égale à 10"
     * )
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice({"Actif", "Supprimé"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Interim", inversedBy="assignments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $interim;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contract", inversedBy="assignments", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contract;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInterim(): ?interim
    {
        return $this->interim;
    }

    public function setInterim(?interim $interim): self
    {
        $this->interim = $interim;

        return $this;
    }

    public function getContract(): ?contract
    {
        return $this->contract;
    }

    public function setContract(contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->contract->getInterim() != $this->interim) {
            $context->buildViolation("Choisissez un contrat appartenant à l'intérimaire sélectionné")
                ->atPath('contract')
                ->addViolation();
        }
    }

// https://symfony.com/doc/current/form/dynamic_form_modification.html#form-events-submitted-data
// TODO instead of callback validate = use dynamic form modif to have only contracts related to chosen interim, using ajax ?
// problem display when many contracts and interim ?
}
