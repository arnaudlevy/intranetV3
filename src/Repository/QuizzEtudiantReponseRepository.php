<?php

namespace App\Repository;

use App\Entity\Etudiant;
use App\Entity\QualiteQuestionnaire;
use App\Entity\QualiteQuestionnaireSection;
use App\Entity\QuizzEtudiant;
use App\Entity\QuizzEtudiantReponse;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method QuizzEtudiantReponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizzEtudiantReponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizzEtudiantReponse[]    findAll()
 * @method QuizzEtudiantReponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizzEtudiantReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizzEtudiantReponse::class);
    }

    /**
     * @param                      $cle
     * @param QuizzEtudiant        $quizzEtudiant
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findExistQuestion(
        $cle,
        QuizzEtudiant $quizzEtudiant
    ) {
        return $this->createQueryBuilder('q')
            ->where('q.cleQuestion = :cle')
            ->andWhere('q.quizzEtudiant = :quizzEtudiant')
            ->setParameter('cle', $cle)
            ->setParameter('quizzEtudiant', $quizzEtudiant->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByQuestionnaire(QuizzEtudiant $quizzEtudiant): array
    {
        $reponses = $this->createQueryBuilder('q')
            ->where('q.quizzEtudiant = :quizzEtudiant')
            ->setParameter('quizzEtudiant', $quizzEtudiant->getId())
            ->getQuery()
            ->getResult();

        $t = [];
        /** @var QuizzEtudiantReponse $reponse */
        foreach ($reponses as $reponse) {
            $t[$reponse->getCleQuestion()] = $reponse;
        }

        return $t;
    }
}