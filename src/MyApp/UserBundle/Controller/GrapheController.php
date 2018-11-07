<?php
/**
 * Created by PhpStorm.
 * User: MohamedKhyari
 * Date: 20/03/2017
 * Time: 18:26
 */


namespace MyApp\UserBundle\Controller;



use MyApp\UserBundle\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Doctrine\ORM\Query\Expr;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;


class GrapheController extends Controller
{
    public function chartLineAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $experience = $em->getRepository('MyAppUserBundle:Experience')->findAll();
        $tab = array();
        $categories = array();
        foreach ($experience as $e) {
            array_push($tab, $e->getId());
            array_push($categories, $e->getTitre());
        }
// Chart
        $series = array(
            array("name" => " Experience", "data" => $tab)
        );
        $ob1 = new Highchart();
        $ob1->chart->renderTo('linechart'); // #id du div où afficher le graphe
        $ob1->title->text('Nombre des Experience par ');
        $ob1->xAxis->title(array('text' => "Experience"));
        $ob1->yAxis->title(array('text' => "Nb Experience"));
        $ob1->xAxis->categories($categories);
        $ob1->series($series);
       // return $this->render('MyAppUserBundle:experience/Graphe:LineChart.html.twig', array('chart' => $ob1));
    return $ob1;
    }

    public function chartHistogrammeAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $classes = $em->getRepository('MyAppUserBundle:Experience')->findAll();
        $categories = array();
        $nbEtudiants = array();
        foreach ($classes as $classe) {
            array_push($categories, $classe->getId());
            array_push($nbEtudiants, $classe->getTitre());
        }
        $series = array(array('name' => 'Etudiant',
            'type' => 'column',
            'color' => '#4572A7',
            'yAxis' => 0,
            'data' => $nbEtudiants,
        )
        );
        $yData = array(array('labels' => array('formatter' => new Expr('function () { return this.value + "" }'),
            'style' => array('color' => '#4572A7')),
            'gridLineWidth' => 0,
            'title' => array('text' => 'Nombre des étudiants',
                'style' => array('color' => '#4572A7')),
        ),
        );
        $ob = new Highchart();
        $ob->chart->renderTo('container'); // The #id of the div where to render the chart $ob->chart->type('column');
        // $ob->title->text('Nombre des étudiants par niveau');
        // $ob->xAxis->categories($categories);

        $ob->yAxis($yData);
        $ob->legend->enabled(false);
        $formatter = new Expr('function () { var unit = { "Etudiant": "étudiant(s)", }[this.series.name]; return this.x + ": <b>" + this.y + "</b> " + unit; }');
        $formatter = new Expr('function () {
var unit = {
"Etudiant": "étudiant(s)",
}[this.series.name];
return this.x + ":'+"<b>"+'" + this.y + "</b> " + unit;
}');
        $ob->tooltip->formatter($formatter);
        $ob->series($series);
        return $this->render('MyAppUserBundle:experience/Graphe:histogramme.html.twig', array('chart' => $ob));
    }


    public function chartPieAction()
    {        if ($this->get('security.token_storage')->getToken()->getRoles() == NULL) {
        $this->get('ras_flash_alert.alert_reporter')->addError("Access denied");

        return ($this->redirectToRoute('fos_user_security_login'));
    }
        $form = $this->createFormBuilder()
            ->add('Pays', ChoiceType::class)
            ->getForm();
        $user = $this->get('security.token_storage')->getToken()->getUser();
    $nbp= $em = $this->getDoctrine()->getManager()
        ->getRepository('MyAppUserBundle:Experience')
        ->findByidUser(array('idUser' => $user));
        $ob1= GrapheController::chartLineAction();


        return $this->render('MyAppUserBundle:experience/Graphe:pie.html.twig', array('fo'=>$form->createView(),'user'=>$user,'nbp'=>$nbp,'chart' => $ob1));
    }


    public function dataChartAction($idPays){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->container->get('doctrine')->getEntityManager();
        $experiences = $em->getRepository('MyAppUserBundle:Experience')->ExpressionParVille($idPays,$user);
        $totalExperience = 0;
        foreach ($experiences as $experience) {
            $totalExperience = $totalExperience + $experience['nbExp'];
        }
        $data = array();
        foreach ($experiences as $experience) {
            $stat = array();
            array_push($stat, $experience['libelleville'], (($experience['nbExp']) * 100) / $totalExperience);
            //var_dump($stat);
            array_push($data, $stat);
        }

        $response = new JsonResponse();
        return $response->setData($data);
    }
}
