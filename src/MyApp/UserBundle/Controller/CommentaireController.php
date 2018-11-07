<?php

namespace MyApp\UserBundle\Controller;

use MyApp\UserBundle\Entity\Commentaire;
use MyApp\UserBundle\Entity\CommetReply;
use MyApp\UserBundle\Entity\question;
use MyApp\UserBundle\Entity\Reponse;
use MyApp\UserBundle\Entity\Reponsequestionnaire;
use MyApp\UserBundle\Form\CommentaireType;
use MyApp\UserBundle\Form\CommetReplyType;
use MyApp\UserBundle\Form\RechercherType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }





    /**
     * Lists all demande entities.
     *
     */
    public function indexdAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentaires = $em->getRepository('MyAppUserBundle:Commentarire')->findAll();

        return $this->render('MyAppUserBundle:Commentaires:IndexCommentaire.html.twig', array(
            'demandes' => $commentaires,
        ));
    }
    function AjouterReplyAction(Request $req)
    {

        $reply = new CommetReply();
        $em = $this->getDoctrine()->getManager();

        if($req->getMethod()=='POST') {
            $idcom=$req->get('id');
               $com=$req->get('com');
               $Commentaire = $em->getRepository('MyAppUserBundle:Commentaire')->find($idcom);

                $reply->setCommentaireReply($com);
                $reply->setIdUser($this->getUser());
                 $reply->setIdCommentaire($Commentaire);
                $em->persist($reply);
                $em->flush();

            }

        return $this->redirectToRoute('AllMycomments_show');

    }

    public function newcommentaireAction(Request $request)
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setIdAnnonce();
            $commentaire->setIdUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', 'L\'article a bien été ajoutée !');
            return $this->redirectToRoute('AllMycomments_show');
        }

        return $this->render('MyAppUserBundle:Commentaires:AjoutCommentaire.html.twig', array(
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ));
    }

    public function sendquestionnaireAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->getMethod()=="POST")
        {
            $qst1=$request->get('qt1');
            $qst2=$request->get('qt2');
            $qst3=$request->get('qt3');
            $qst4=$request->get('qt4');
            $qst5=$request->get('qt5');
            $qst6=$request->get('qt6');

            $array = array($qst1,$qst2,$qst3,$qst4,$qst5,$qst6);

            foreach ($array as $value)
            {
                $reponse= new Reponsequestionnaire();


            }

        }



        return $this->redirectToRoute('AllMycomments_show' );



    }

    public function chartsAction()
    {

        return $this->render('MyAppUserBundle:Commentaires:Charts.html.twig'

        );



    }
    public function showAction(Commentaire $commentaire)
    {


        return $this->render('MyAppUserBundle:Commentaires:AllMyComments.html.twig', array(
            'commentaire' => $commentaire

        ));
    }
    public function showAllMycommentsAction(Request $request)
    {


            $em = $this->getDoctrine()->getManager();
            $reply = new CommetReply();
            $reply->setIdUser($this->getUser());
            $form = $this->createForm(CommetReplyType::class, $reply);
            $commentaires = $em->getRepository('MyAppUserBundle:Commentaire')->findAll();
            $CommentaireReply = $em->getRepository('MyAppUserBundle:CommetReply')->findBy(array('idCommentaire' =>$commentaires));

            $paginator = $this->get('knp_paginator');
            $result = $paginator->paginate(

                $commentaires,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 3)

            );

            return $this->render('MyAppUserBundle:Commentaires:AllMyComments.html.twig', array(
                'commentaires' => $result, 'form' => $form->createView(), 'reply' => $CommentaireReply,'com'=>$commentaires
            ));

    }


    /**
     * Displays a form to edit an existing demande entity.
     *
     */
    public function editAction($id,Request $req)
    {
        $idD =  $req->get('idD');
        $em = $this->getDoctrine()->getManager();
        $commentaire=$em->getRepository('MyAppUserBundle:Commentaire')->find($id);
        $form=$this->createForm('MyApp\UserBundle\Form\CommentaireType',$commentaire);
        if($form->handleRequest($req)->isValid())
        {
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('afficher Annonce', array('id' => $idD));

        }
        return $this->render('MyAppUserBundle:Commentaires:AjoutCommentaire.html.twig',
            array ('form'=>$form->createView()));
    }

    function deleteAction($id,Request $request)
    {
        $idD =  $request->get('idD');
        $em=$this->getDoctrine()->getManager();
        $demande=$em->getRepository('MyAppUserBundle:Commentaire')->find($id);
        $em->remove($demande);
        $em->flush();
        return $this->redirectToRoute('afficher Annonce', array('id' => $idD));
    }



    function afficherQuestionAction()
 {

     $em=$this->getDoctrine()->getManager();
     $question=$em->getRepository('MyAppUserBundle:question')->findAll();
      return $this->render('MyAppUserBundle:Commentaires:questionnaire.html.twig',array('questions'=>$question));

 }

    public function questionnaireAction(Request $request)


    {




        return $this->render('MyAppUserBundle:Commentaires:questionnaire.html.twig'

    );
    }



}
