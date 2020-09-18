<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/appEtudiant/ProjetController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 18/09/2020 08:45

namespace App\Controller\appEtudiant;

use App\Controller\BaseController;
use App\Entity\Alternance;
use App\Entity\Constantes;
use App\Entity\ProjetEtudiant;
use App\Entity\StageEtudiant;
use App\Event\ProjetEvent;
use App\Event\StageEvent;
use App\Form\ProjetEtudiantEtudiantType;
use App\Form\StageEtudiantEtudiantType;
use App\Repository\ProjetPeriodeRepository;
use App\Repository\StagePeriodeRepository;
use Carbon\Carbon;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class StageController
 * @package App\Controller
 * @Route("/application/etudiant/projet")
 */
class ProjetController extends BaseController
{
    /**
     * @Route("/", name="application_etudiant_projet_index")
     * @param ProjetPeriodeRepository $projetPeriodeRepository
     *
     * @return Response
     */
    public function index(ProjetPeriodeRepository $projetPeriodeRepository): Response
    {
        $projetsPeriodes = $projetPeriodeRepository->findBySemestre($this->getConnectedUser()->getSemestre());
        $projetsEtudiants = [];

        foreach ($this->getConnectedUser()->getProjetEtudiants() as $projetEtudiant) {
            if ($projetEtudiant->getProjetPeriode() !== null) {
                $projetsEtudiants[$projetEtudiant->getProjetPeriode()->getId()] = $projetEtudiant;
            }
        }

        return $this->render('appEtudiant/projet/index.html.twig', [
            'projetsPeriodes'  => $projetsPeriodes,
            'projetsEtudiants' => $projetsEtudiants
        ]);
    }

//    /**
//     * @Route("/details/{id}", name="application_etudiant_stage_detail", methods={"GET"}, requirements={"id"="\d+"})
//     * @param StageEtudiant $stageEtudiant
//     *
//     * @return Response
//     */
//    public function detailsStage(StageEtudiant $stageEtudiant): Response
//    {
//        return $this->render('appEtudiant/stage/details.html.twig', [
//            'stageEtudiant' => $stageEtudiant
//        ]);
//    }

    /**
     * @Route("/formulaire/{projetEtudiant}", name="application_etudiant_projet_formulaire", methods="GET|POST")
     * @ParamConverter("projetEtudiant", options={"mapping": {"projetEtudiant": "uuid"}})
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param Request                  $request
     * @param ProjetEtudiant           $projetEtudiant
     *
     * @return Response
     */
    public function create(
        EventDispatcherInterface $eventDispatcher,
        Request $request,
        ProjetEtudiant $projetEtudiant
    ): Response {
        if ($projetEtudiant->getProjetPeriode() !== null) {
            $form = $this->createForm(ProjetEtudiantEtudiantType::class, $projetEtudiant, [
                'semestre' => $this->getConnectedUser()->getSemestre(),
                'attr'     => [
                    'data-provide' => 'validation'
                ]
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $projetEtudiant->setEtatProjet(ProjetEtudiant::ETAT_PROJET_DEPOSE);
                $projetEtudiant->setDateDepose(new Carbon('now'));
                $this->entityManager->flush();

                $event = new ProjetEvent($projetEtudiant);
                $eventDispatcher->dispatch($event, ProjetEvent::CHGT_ETAT_PROJET_DEPOSE);

                $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'projet_etudiant.formulaire.success.flash');

                return $this->redirectToRoute('application_index', ['onglet' => 'projet']);
            }

            return $this->render('appEtudiant/projet/formulaire.html.twig', [
                'stageEtudiant' => $projetEtudiant,
                'form'          => $form->createView(),
            ]);
        }

        return $this->render('bundles/TwigBundle/Exception/error500.html.twig');
    }

//    /**
//     * @Route("/periode/info/{id}", name="application_etudiant_stage_periode_info")
//     * @param StageEtudiant $stageEtudiant
//     *
//     * @return Response
//     */
//    public function periodeInfo(StageEtudiant $stageEtudiant): Response
//    {
//        return $this->render('appEtudiant/stage/periodeInfo.html.twig', [
//            'stageEtudiant' => $stageEtudiant,
//            'stagePeriode'  => $stageEtudiant->getStagePeriode()
//        ]);
//    }

//    /**
//     * @Route("/entreprise/stage/info/{id}", name="application_etudiant_stage_entreprise_info")
//     * @param StageEtudiant $stageEtudiant
//     *
//     * @return Response
//     */
//    public function entrepriseInfo(StageEtudiant $stageEtudiant): Response
//    {
//        return $this->render('appEtudiant/stage/entrepriseInfo.html.twig', [
//            'stageEtudiant' => $stageEtudiant
//        ]);
//    }

//    /**
//     * @Route("/entreprise/alternance/info/{id}", name="application_etudiant_alternance_entreprise_info")
//     * @param Alternance $alternance
//     *
//     * @return Response
//     */
//    public function entrepriseAlternanceInfo(Alternance $alternance): Response
//    {
//        return $this->render('appEtudiant/stage/entrepriseAlternanceInfo.html.twig', [
//            'alternance' => $alternance
//        ]);
//    }
}