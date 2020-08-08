<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/EtudiantController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 08/08/2020 10:20

namespace App\Controller\administration;

use App\Classes\MyExport;
use App\Controller\BaseController;
use App\Entity\Constantes;
use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Form\ImportEtudiantType;
use App\Repository\EtudiantRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EtudiantController
 * @package App\Controller\administration
 * @Route("/administration/etudiant")
 */
class EtudiantController extends BaseController
{
    /**
     * @Route("/", name="administration_etudiant_index")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('administration/etudiant/index.html.twig', [
        ]);
    }

    /**
     * @Route("/import", name="administration_etudiant_import", methods="POST")
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request): Response
    {
        $form = $this->createForm(
            ImportEtudiantType::class,
            null,
            [
                'departement' => $this->dataUserSession->getDepartement(),
                'attr'        => [
                    'data-provide' => 'validation'
                ]
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //todo: gérer l'import. En commun avec SuperAdministration/EtudiantController
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'etudiant.import.success.flash');
        }

        return $this->redirectToRoute('administration_etudiant_semestre_add');
    }

    /**
     * @Route("/edit/{id}", name="administration_etudiant_edit", methods="{GET|POST}")
     * @param Request  $request
     *
     * @param Etudiant $etudiant
     *
     * @return Response
     */
    public function edit(Request $request, Etudiant $etudiant): Response
    {
        $form = $this->createForm(
            EtudiantType::class,
            $etudiant,
            [
                'attr'        => [
                    'data-provide' => 'validation'
                ],
                'departement' => $this->dataUserSession->getDepartement()
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($etudiant);
            $this->entityManager->flush();

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'etudiant.add.success.flash');
            $this->redirectToRoute('administration_etudiant_index', ['semestre' => $etudiant->getSemestre()->getId()]);
        }

        return $this->render('administration/etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form'     => $form->createView()
        ]);
    }


    /**
     * @Route("/add", name="administration_etudiant_add", methods="POST")
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function create(Request $request): Response
    {
        $etudiant = new Etudiant();

        $form = $this->createForm(
            EtudiantType::class,
            $etudiant,
            [
                'attr'        => [
                    'data-provide' => 'validation'
                ],
                'departement' => $this->dataUserSession->getDepartement()
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($etudiant);
            $this->entityManager->flush();

            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'etudiant.add.success.flash');
        }

        return $this->redirectToRoute('administration_etudiant_semestre_add');
    }


    /**
     * @Route("/export.{_format}", name="administration_all_etudiant_export", methods="GET",
     *                             requirements={"_format"="csv|xlsx|pdf"})
     * @param MyExport           $myExport
     * @param EtudiantRepository $etudiantRepository
     * @param                    $_format
     *
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(MyExport $myExport, EtudiantRepository $etudiantRepository, $_format): Response
    {
        $etudiants = $etudiantRepository->getByDepartement($this->getDepartement(), []);

        return $myExport->genereFichierGenerique(
            $_format,
            $etudiants,
            'etudiants_' . $this->getDepartement()->getLibelle(),
            ['etudiants_administration'],
            ['nom', 'prenom', 'civilite', 'numEtudiant', 'mailUniv', 'semestre' => ['libelle']]
        );
    }


}
