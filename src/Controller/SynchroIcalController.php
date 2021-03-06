<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/SynchroIcalController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 08/09/2020 11:46

namespace App\Controller;


use App\Classes\Edt\MyEdtExport;
use App\Repository\EtudiantRepository;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EdtController
 * @package App\Controller
 * @Route("/api/synchronisation/ical")
 */
class SynchroIcalController extends AbstractController
{
    /**
     * @Route("/intervenant/{code}.{_format}", name="edt_intervenant_synchro_ical")
     * @param MyEdtExport         $myEdtExport
     * @param PersonnelRepository $personnelRepository
     * @param                     $code
     * @param                     $_format
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function synchroIntervenantIcal(
        MyEdtExport $myEdtExport,
        PersonnelRepository $personnelRepository,
        $code,
        $_format
    ): Response {

        //Toutes les semaines
        $personnel = $personnelRepository->findByCode($code);
        if ($personnel !== null) {
            $ical = $myEdtExport->export($personnel, $_format, 'Personnel');
            $timestamp = new \DateTime('now');

            return new Response($ical, 200, [
                'Content-Type'        => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'inline; filename="ical' . $timestamp->format('YmdHis') . '.ics"'
            ]);
        }

        return new Response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @Route("/etudiant/{code}.{_format}", name="edt_etudiant_synchro_ical")
     * @param MyEdtExport        $myEdtExport
     * @param EtudiantRepository $etudiantRepository
     * @param                    $code
     * @param                    $_format
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function synchroEtudiantIcal(
        MyEdtExport $myEdtExport,
        EtudiantRepository $etudiantRepository,
        $code,
        $_format
    ): Response {
        $etudiant = $etudiantRepository->findByCode($code);
        if ($etudiant !== null) {
            $ical = $myEdtExport->export($etudiant, $_format, 'Etudiant');
            $timestamp = new \DateTime('now');

            return new Response($ical, 200, [
                'Content-Type'        => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'inline; filename="ical' . $timestamp->format('YmdHis') . '.ics"'
            ]);
        }

        return new Response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
