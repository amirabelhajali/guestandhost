<?php
/**
 * Created by PhpStorm.
 * User: amira
 * Date: 30/03/2017
 * Time: 22:15
 */

namespace MyApp\UserBundle\Controller;
use MyApp\UserBundle\Entity\Proposition;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MyApp\UserBundle\Entity\Demande;



class PropositionController extends Controller
{
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $id_demande = $request->get('id_demande');
            $tAnnonces = $request->get('check');
            $demande = $em->getRepository('MyAppUserBundle:Demande')->find($id_demande);

            if (is_array($tAnnonces)) {
                foreach ($tAnnonces as $id_annonce) {
                    $annonce = $em->getRepository('MyAppUserBundle:Annonce')->find($id_annonce);
                    $proposition = new Proposition();
                    $proposition->setIdUser($this->getUser());
                    $proposition->setIdDemande($demande);
                    $proposition->setDescription("hello");
                    $proposition->setIdAnnonce($annonce);
                    $em->persist($proposition);
                    $em->flush();

                 //   $manager = $this->get('mgilet.notification');
                 //   $notif = $manager->generateNotification('Notification');
                  //  $notif->setMessage('vous avez une nouvelle notification sur votre demande à ');
                  //  $notif->setLink('http://localhost/guestandhost/web/app_dev.php/proposition/','/show');
                  //  $manager->addNotification($notif);

                }
            }
            $this->get('session')->getFlashBag()->add('message', 'L\'article a bien été ajoutée !');

            return $this->redirectToRoute('Alldemande_show');
        }
    }

    public function showAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();

        $propositions = $em->getRepository('MyAppUserBundle:Proposition')->findBy(array('idDemande'=>$id));
        $paginator  = $this->get('knp_paginator');


        $paginations = $paginator->paginate(
            $propositions,
            $request->query->getInt('page', 1),
            6    );
        return $this->render('MyAppUserBundle:demande:MesProposition.html.twig', array(
            'proposition' => $paginations
        ));
    }
    }