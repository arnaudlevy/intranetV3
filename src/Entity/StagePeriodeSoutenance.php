<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/StagePeriodeSoutenance.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StagePeriodeSoutenanceRepository")
 */
class StagePeriodeSoutenance extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StagePeriode", inversedBy="stagePeriodeSoutenances")
     */
    private $stagePeriode;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRenduRapport;

    public function getStagePeriode(): ?StagePeriode
    {
        return $this->stagePeriode;
    }

    public function setStagePeriode(?StagePeriode $stagePeriode): self
    {
        $this->stagePeriode = $stagePeriode;

        return $this;
    }

    public function getDateDebut(): ?DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDateRenduRapport(): ?DateTimeInterface
    {
        return $this->dateRenduRapport;
    }

    public function setDateRenduRapport(DateTimeInterface $dateRenduRapport): self
    {
        $this->dateRenduRapport = $dateRenduRapport;

        return $this;
    }
}
