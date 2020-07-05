<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/AbsenceJustificatif.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Entity;

use App\Entity\Traits\UuidTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Uid\Uuid;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AbsenceJustificatifRepository")
 * @Vich\Uploadable
 */
class AbsenceJustificatif extends BaseEntity implements Serializable
{
    public const ACCEPTE = 'A';
    public const REFUSE = 'R';
    public const DEPOSE = 'D';

    Public const ETATLONG = [
        'A' => 'Accepté',
        'R' => 'Refusé',
        'D' => 'Déposé'
    ];

    use UuidTrait;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="time")
     */
    private $heureDebut;

    /**
     * @ORM\Column(type="time")
     */
    private $heureFin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etudiant", inversedBy="absenceJustificatifs")
     */
    private $etudiant;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $fichierName;

    /**
     * @var UploadedFile
     *
     * @Vich\UploadableField(mapping="justificatif", fileNameProperty="fichierName")
     */
    private $fichierFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnneeUniversitaire")
     */
    private $anneeUniversitaire;

    /**
     * AbsenceJustificatif constructor.
     *
     * @param Etudiant $etudiant
     *
     * @throws Exception
     */
    public function __construct(Etudiant $etudiant)
    {
        $this->etat = 'D';
        $this->setUuid(Uuid::v4());
        $this->anneeUniversitaire = $etudiant !== null ? $etudiant->getAnneeUniversitaire() : null;
        $this->setEtudiant($etudiant);
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

    public function getHeureDebut(): ?DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * @param File|null $document
     *
     * @throws Exception
     */
    public function setFichierFile(?File $document = null): void
    {
        $this->fichierFile = $document;

        if (null !== $document) {
            $this->setUpdated(new DateTime());
        }
    }

    /**
     * @return null|File
     */
    public function getFichierFile(): ?File
    {
        return $this->fichierFile;
    }

    /**
     * @return string
     */
    public function getFichierName(): ?string
    {
        return $this->fichierName;
    }

    /**
     * @param string $fichierName
     */
    public function setFichierName(?string $fichierName): void
    {
        $this->fichierName = $fichierName;
    }


    public function getEtatLong()
    {
        $tabEtat = [
            'A' => 'Accepté, absences justifiées',
            'R' => 'Refusé',
            'D' => 'Déposé, en attente de validation'
        ];

        return $tabEtat[$this->etat];
    }

    public function getAnneeUniversitaire(): ?AnneeUniversitaire
    {
        return $this->anneeUniversitaire;
    }

    public function setAnneeUniversitaire(?AnneeUniversitaire $anneeUniversitaire): self
    {
        $this->anneeUniversitaire = $anneeUniversitaire;

        return $this;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function etatLong()
    {
        return self::ETATLONG[$this->etat];
    }

    public function serialize()
    {
        return serialize($this->getId());
    }

    public function unserialize($serialized)
    {
        $this->uuid = unserialize($serialized, ['allowed_classes' => false]);

    }
}
