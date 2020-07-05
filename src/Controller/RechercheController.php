<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/RechercheController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Controller;

use App\Repository\EtudiantRepository;
use App\Repository\PersonnelRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RechercheController
 * @package App\Controller
 * @Route("/recherche")
 */
class RechercheController extends BaseController
{
    /**
     * @Route("/{keyword}", name="recherche", options={"expose":true})
     * @param EtudiantRepository  $etudiantRepository
     * @param PersonnelRepository $personnelRepository
     * @param                     $keyword
     *
     * @return JsonResponse
     */
    public function index(
        EtudiantRepository $etudiantRepository,
        PersonnelRepository $personnelRepository,
        $keyword
    ): JsonResponse {
        $t['etudiants'] = $etudiantRepository->search($keyword);
        $t['personnels'] = $personnelRepository->search($keyword);
        $t['autres'] = [];
        $t['basepath'] = getenv('BASE_URL') . 'upload/';

        return $this->json($t);
    }
}
