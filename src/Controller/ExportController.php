<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/ExportController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Controller;

use App\MesClasses\MyExportListing;
use App\Repository\MatiereRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExportController
 * @package App\Controller
 * @Route("/export")
 */
class ExportController extends AbstractController
{
    /**
     * @Route("/listing", name="export_listing")
     * @param MatiereRepository $matiereRepository
     * @param MyExportListing   $myExport
     * @param Request           $request
     *
     * @return bool|null|StreamedResponse
     * @throws Exception
     */
    public function listing(MatiereRepository $matiereRepository, MyExportListing $myExport, Request $request)
    {
        $matiere = $request->request->get('matiere');
        if ($matiere !== 0) {
            $matiere = $matiereRepository->find($matiere);
        }

        $exportTypeDocument = $request->request->get('exportTypeDocument');
        $exportChamps = $request->request->get('exportChamps');
        $exportFormat = $request->request->get('exportFormat');
        $exportFiltre = $request->request->get('exportFiltre');

        return $myExport->genereFichier($exportTypeDocument, $exportFormat, $exportChamps, $exportFiltre, $matiere);
    }
}
