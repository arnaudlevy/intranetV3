<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/MyEvaluations.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 15/08/2020 09:06

/**
 * Created by PhpStorm.
 * User: davidannebicque
 * Date: 02/04/2018
 * Time: 17:24
 */

namespace App\Classes;

use App\Entity\AnneeUniversitaire;
use App\Entity\Evaluation;
use App\Entity\Matiere;
use App\Entity\Semestre;
use App\Repository\EvaluationRepository;
use App\Repository\MatiereRepository;

/**
 * Class MyEvaluations
 * @package App\Classes
 */
class MyEvaluations
{
    /**
     * @var Semestre
     */
    private $semestre;

    /** @var Matiere */
    private $matiere;

    /**
     * @var Evaluation[]
     */
    private $evaluations = [];

    /**
     * @var array
     */
    private $statistiques = [];

    /**
     * @var MatiereRepository
     */
    private $matiereRepository;

    /** @var MyEvaluation */
    private $myEvaluation;

    /**
     * @var EvaluationRepository
     */
    private $evaluationRespository;

    /**
     * MyEvaluations constructor.
     *
     * @param MyEvaluation         $myEvaluation
     * @param MatiereRepository    $matiereRepository
     * @param EvaluationRepository $evaluationRespository
     */
    public function __construct(
        MyEvaluation $myEvaluation,
        MatiereRepository $matiereRepository,
        EvaluationRepository $evaluationRespository
    ) {
        $this->matiereRepository = $matiereRepository;
        $this->evaluationRespository = $evaluationRespository;
        $this->myEvaluation = $myEvaluation;
    }


    /**
     * @param $semestre
     *
     * @return MyEvaluations
     */
    public function setSemestre($semestre): MyEvaluations
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * @param Matiere $matiere
     */
    public function setMatiere(Matiere $matiere): void
    {
        $this->matiere = $matiere;
    }

    /**
     * @return Matiere[]
     */
    public function getMatieresSemestre(): array
    {
        return $this->matiereRepository->findBySemestre($this->semestre);
    }

    /**
     * @param Semestre           $semestre
     * @param AnneeUniversitaire $anneeUniversitaire
     *
     * @return array
     */
    public function getEvaluationsSemestre(Semestre $semestre, AnneeUniversitaire $anneeUniversitaire): array
    {
        $evaluations = $this->evaluationRespository->findBySemestre($semestre,
            $anneeUniversitaire);
        $tab = [];
        /** @var Evaluation $eval */
        foreach ($evaluations as $eval) {
            if ($eval->getMatiere() !== null) {
                $matiereId = $eval->getMatiere()->getId();
                if (!array_key_exists($matiereId, $tab)) {
                    $tab[$matiereId] = [];
                }

                $tab[$matiereId][] = $eval;
                $this->statistiques[$eval->getId()] = $this->myEvaluation->setEvaluation($eval)->calculStatistiquesGlobales()->getStatistiques();
            }
        }

        return $tab;
    }

    /**
     * @param $annee
     */
    public function getEvaluationsMatiere($annee): void
    {
        $this->evaluations = $this->evaluationRespository->findByMatiere($this->matiere, $annee);

        foreach ($this->getEvaluations() as $evaluation) {
            $this->statistiques[$evaluation->getId()] = $this->myEvaluation->setEvaluation($evaluation)->calculStatistiquesGlobales()->getStatistiques();
        }
    }

    /**
     * @return Evaluation[]
     */
    public function getEvaluations(): array
    {
        return $this->evaluations;
    }

    /**
     * @return array
     */
    public function getStatistiques(): array
    {
        return $this->statistiques;
    }
}
