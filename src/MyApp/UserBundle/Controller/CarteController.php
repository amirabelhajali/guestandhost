<?php

namespace MyApp\UserBundle\Controller;

use MyApp\UserBundle\Entity\Carte;
use MyApp\UserBundle\Form\AjoutCarteType;
use MyApp\UserBundle\Form\CarteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CarteController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    function rechargerCompteAction(Request $request)
    {
        $carte = new Carte();
        $em=$this->getDoctrine()->getManager();
    $userCo = $em->getRepository('MyAppUserBundle:User')->find($this->getUser()->getId());


        $form=$this->createForm(AjoutCarteType::class,$carte);

        if($form->handleRequest($request)->isValid()){

            $code = $form->get('code')->getData();
            $carteRecharge = $em->getRepository('MyAppUserBundle:Carte')->findOneBy(array('code'=>$code));
            if($carteRecharge == null)
            {
                $msg='Cette carte est invalide.';
                $this->get('ras_flash_alert.alert_reporter')->addError($msg);

            }else {
                $userCo->setSolde($userCo->getSolde() + $carteRecharge->getMontant());
                $em->persist($userCo);
                $em->remove($carteRecharge);
                $em->flush();
                return $this->redirectToRoute('Recharger Compte');
            }
        }
        return $this->render('MyAppUserBundle:carte:RechargerCompte.html.twig',array('f'=>$form->createView()));

    }


}
