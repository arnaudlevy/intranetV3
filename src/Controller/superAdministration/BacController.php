<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/superAdministration/BacController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 18/09/2020 17:04

namespace App\Controller\superAdministration;

use App\Controller\BaseController;
use App\Entity\Bac;
use App\Entity\Constantes;
use App\Form\BacType;
use App\Classes\MyExport;
use App\Repository\BacRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administratif/bac")
 */
class BacController extends BaseController
{
    /**
     * @Route("/", name="sa_bac_index", methods={"GET"})
     * @param BacRepository $bacRepository
     *
     * @return Response
     */
    public function index(BacRepository $bacRepository): Response
    {
        return $this->render('super-administration/bac/index.html.twig', ['bacs' => $bacRepository->findAll()]);
    }

    /**
     * @Route("/export.{_format}", name="sa_bac_export", methods="GET", requirements={"_format"="csv|xlsx|pdf"})
     * @param MyExport            $myExport
     * @param BacRepository       $bacRepository
     *
     * @param                     $_format
     *
     * @return Response
     * @throws Exception
     */
    public function export(MyExport $myExport, BacRepository $bacRepository, $_format): Response
    {
        $bacs = $bacRepository->findAll();
        return $myExport->genereFichierGenerique(
            $_format,
            $bacs,
            'bacs',
            ['bac_administration'],
            ['libelle', 'libelleLong']
        );
    }

    /**
     * @Route("/new", name="sa_bac_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $bac = new Bac();
        $form = $this->createForm(BacType::class, $bac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bac);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'bac.add.success.flash');

            return $this->redirectToRoute('sa_bac_index');
        }

        return $this->render('super-administration/bac/new.html.twig', [
            'bac'  => $bac,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sa_bac_show", methods={"GET"})
     * @param Bac $bac
     *
     * @return Response
     */
    public function show(Bac $bac): Response
    {
        return $this->render('super-administration/bac/show.html.twig', ['bac' => $bac]);
    }

    /**
     * @Route("/{id}/edit", name="sa_bac_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Bac     $bac
     *
     * @return Response
     */
    public function edit(Request $request, Bac $bac): Response
    {
        $form = $this->createForm(BacType::class, $bac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'bac.edit.success.flash');

            if ($request->request->get('btn_update') !== null) {
                return $this->redirectToRoute('sa_bac_index');
            }
            return $this->redirectToRoute('sa_bac_edit', ['id' => $bac->getId()]);
        }

        return $this->render('super-administration/bac/edit.html.twig', [
            'bac'  => $bac,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/duplicate", name="sa_bac_duplicate", methods="GET|POST")
     * @param Bac $bac
     *
     * @return Response
     */
    public function duplicate(Bac $bac): Response
    {
        $newBac = clone $bac;

        $this->entityManager->persist($newBac);
        $this->entityManager->flush();
        $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'bac.duplicate.success.flash');

        return $this->redirectToRoute('sa_bac_edit', ['id' => $newBac->getId()]);
    }

    /**
     * @Route("/{id}", name="sa_bac_delete", methods="DELETE")
     * @param Request $request
     * @param Bac     $bac
     *
     * @return Response
     */
    public function delete(Request $request, Bac $bac): Response
    {
        $id = $bac->getId();
        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $this->entityManager->remove($bac);
            $this->entityManager->flush();

            return $this->json($id, Response::HTTP_OK);
        }

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
