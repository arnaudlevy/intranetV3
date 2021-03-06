<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/QualiteQuestionnaireSection.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QualiteQuestionnaireSectionRepository")
 */
class QualiteQuestionnaireSection extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QualiteQuestionnaire", inversedBy="qualiteQuestionnaireSections")
     */
    private $questionnaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QualiteSection", inversedBy="qualiteQuestionnaireSections")
     */
    private $section;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    public function getQuestionnaire(): ?QualiteQuestionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?QualiteQuestionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    public function getSection(): ?QualiteSection
    {
        return $this->section;
    }

    public function setSection(QualiteSection $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}
