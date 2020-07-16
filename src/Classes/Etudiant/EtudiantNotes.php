<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/Etudiant/EtudiantNotes.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 16/07/2020 08:41

namespace App\Classes\Etudiant;


use App\Classes\Tools;
use App\Entity\AnneeUniversitaire;
use App\Entity\Etudiant;
use App\Entity\Evaluation;
use App\Entity\ModificationNote;
use App\Entity\Note;
use App\Entity\Personnel;
use App\Entity\Semestre;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class EtudiantNotes
{
    public NoteRepository $noteRepository;
    private $notes;
    private Etudiant $etudiant;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * EtudiantNotes constructor.
     *
     */
    public function __construct(NoteRepository $noteRepository, EntityManagerInterface $entityManager)
    {
        $this->noteRepository = $noteRepository;
        $this->entityManager = $entityManager;
    }


    public function setEtudiant(Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;
    }

    public function getNotesParSemestresEtAnneeUniversitaire(Semestre $semestre, AnneeUniversitaire $anneeUniversitaire)
    {
        $this->notes = $this->noteRepository->findByEtudiantSemestre($this->etudiant, $semestre, $anneeUniversitaire);

        return $this->notes;
    }

    /**
     * @param Evaluation $evaluation
     * @param            $data
     *
     * @param Personnel  $personnel
     *
     * @return bool
     * @throws Exception
     */
    public function addNote(Evaluation $evaluation, $data, Personnel $personnel): bool
    {
        //on cherche si deja une note de présente
        $note = $this->noteRepository->findBy([
            'evaluation' => $evaluation->getId(),
            'etudiant'   => $this->etudiant->getId()
        ]);

        if (count($note) === 1) {
            //update
            $modif = new ModificationNote();
            $modif->setNote($note[0]);
            $modif->setAncienneNote($note[0]->getNote());
            $modif->setNouvelleNote(Tools::convertToFloat($data['note']));
            $modif->setPersonnel($personnel);
            $this->entityManager->persist($modif);
            $note[0]->setNote(Tools::convertToFloat($data['note']));
            $note[0]->setCommentaire($data['commentaire']);
            if (isset($data['absence']) && $data['absence'] === 'true') {
                $note[0]->setAbsenceJustifie(true);
            } else {
                $note[0]->setAbsenceJustifie(false);
            }

            $this->entityManager->persist($note[0]);
            $this->entityManager->flush();
        } elseif (count($note) === 0) {
            //creation

            $newnote = new Note();
            $newnote->setEtudiant($this->etudiant);
            $newnote->setEvaluation($evaluation);
            $newnote->setNote(Tools::convertToFloat($data['note']));
            $newnote->setCommentaire($data['commentaire']);

            $this->entityManager->persist($newnote);
            $this->entityManager->flush();
        }

        return false;
    }

    public function suppressionNotes(): void
    {
        $ml = $this->noteRepository->findBy(['etudiant' => $this->etudiant->getId()]);
        foreach ($ml as $a) {
            $this->entityManager->remove($a);
        }
        $this->entityManager->flush();

    }
}