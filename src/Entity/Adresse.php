<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/Adresse.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdresseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Adresse extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"adresse"})
     */
    private $adresse1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"adresse"})
     */
    private $adresse2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"adresse"})
     */
    private $adresse3;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"adresse"})
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"adresse"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"adresse"})
     */
    private $pays = 'France';

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Alternance", mappedBy="adresseAlternance", cascade={"persist", "remove"})
     */
    private $alternance;

    /**
     * Adresse constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     * @return null|string
     */
    public function getDisplay(): ?string
    {
        $html = $this->getAdresse1();
        if ($this->getAdresse2() !== '') {
            $html .= '<br />' . $this->getAdresse2();
        }

        if ($this->getAdresse3() !== '') {
            $html .= '<br />' . $this->getAdresse3();
        }

        $html .= '<br />' . $this->getCodePostal() . ' ' . $this->getVille();
        $html .= '<br />' . $this->getPays();

        return $html;
    }

    /**
     * @return null|string
     */
    public function getAdresse1(): ?string
    {
        return $this->adresse1;
    }

    /**
     * @param string $adresse1
     *
     * @return Adresse
     */
    public function setAdresse1(?string $adresse1 = ''): self
    {
        $this->adresse1 = $adresse1;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdresse2(): ?string
    {
        return trim($this->adresse2);
    }

    /**
     * @param $adresse2
     *
     * @return Adresse
     */
    public function setAdresse2(?string $adresse2 = ''): self
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdresse3(): ?string
    {
        return trim($this->adresse3);
    }

    /**
     * @param $adresse3
     *
     * @return Adresse
     */
    public function setAdresse3(?string $adresse3 = ''): self
    {
        $this->adresse3 = $adresse3;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    /**
     * @param string $codePostal
     *
     * @return Adresse
     */
    public function setCodePostal(?string $codePostal = ''): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getVille(): ?string
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     *
     * @return Adresse
     */
    public function setVille(?string $ville = ''): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPays(): ?string
    {
        return $this->pays;
    }

    /**
     * @param string $pays
     *
     * @return Adresse
     */
    public function setPays(?string $pays = 'France'): self
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @param $dataApogee
     */
    public function updateFromApogee($dataApogee): void
    {
        foreach ($dataApogee as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    public function getAlternance(): ?Alternance
    {
        return $this->alternance;
    }

    public function setAlternance(?Alternance $alternance): self
    {
        $this->alternance = $alternance;

        // set (or unset) the owning side of the relation if necessary
        $newAdresseAlternance = null === $alternance ? null : $this;
        if ($alternance->getAdresseAlternance() !== $newAdresseAlternance) {
            $alternance->setAdresseAlternance($newAdresseAlternance);
        }

        return $this;
    }
}
