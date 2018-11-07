<?php
/**
 * Created by PhpStorm.
 * User: amira
 * Date: 03/04/2017
 * Time: 22:40
 */

namespace MyApp\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController  extends Controller
{
    public function sendNotificationAction(Request $request)
    {

        $manager = $this->get('mgilet.notification');
        $notif = $manager->generateNotification('Hello world !');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        $manager->addNotification($this->getUser(), $notif);

        // or the one-line method :
        // $manager->createNotification($this->getUser(), 'Notification subject','Some random text','http://google.fr');

        return $this->redirectToRoute('Alldemande_show');
    }

}