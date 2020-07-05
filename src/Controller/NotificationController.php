<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/NotificationController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Controller;

use App\Entity\Notification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessagerieController
 * @package App\Controller
 * @Route("/notification")
 *
 */
class NotificationController extends BaseController
{
    // todo: gérer les notifications anciennes... Suppression après xx jours => tache cron ?
    // todo: gérer le "lu" lorsque l'on clique sur un lien
    // todo: comment détailler plus que le type ? des "sous-classes" ? En sauvegardant le type d'objet et un id ?


    /**
     * @Route("/", name="notification_index")
     */
    public function index(): Response
    {
        return $this->render('notification/index.html.twig', [
            'notifications' => $this->getConnectedUser()->getNotifications(),
        ]);
    }

    /**
     * @Route("/marquer", name="notification_marquer_lu", options={"expose":true})
     *
     * @return Response
     */
    public function marquerCommeLu(): Response
    {
        //vérifier si c'est le bon user ?
        $notifications = $this->getConnectedUser()->getNotifications(); //todo: améliorer en récupérant uniquement les nom lu.

        /** @var Notification $notif */
        foreach ($notifications as $notif) {
            $notif->setLu(true);
            $this->entityManager->persist($notif);
        }
        $this->entityManager->flush();

        return new Response('ok', 200);
    }
}
