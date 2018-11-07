<?php

namespace MyApp\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }


    //execute le vote "j'aime"
    public function executeVotePlus(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    $message=$this->getDoctrine()->getManager('Message')->findById();
        //on récupère le message courant
        //on effectue le vote positif

        $this->$message->votePlus();
        //on génère le partial du message, c'est ce résultat qui sera utilisé par le link_to_remote pour mettre à jour la div spécifiée, cela évite également le passage par le template votePlusSuccess.php (qui donc n'est pas à créer)
       // return $this->render('message/rating',array('message' => $this->$message));



        return $this->render('MyAppUserBundle:Commentaires:IndexCommentaire.html.twig', array(
            'message' => $message,
        ));

    }

    //execute le vote "j'aime pas"
    public function executeVoteMoins(sfWebRequest $request)
    {
        //on récupère le message courant
        $this->message = Doctrine::getTable('Message')->find($request->getParameter('id'));
        //on effectue le vote négatif
        $this->message->voteMoins();
        //on génère le partial du message, c'est ce résultat qui sera utilisé par le link_to_remote pour mettre à jour la div spécifiée, cela évite également le passage par le template voteMoinsSuccess.php (qui donc n'est pas à créer)
        return $this->renderPartial('message/rating',array('message' => $this->message));
    }
}
