<?php
/**
 * Created by PhpStorm.
 * User: apprenti
 * Date: 06/11/16
 * Time: 18:18
 */

namespace PortfolioBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PortfolioBundle\Entity\Veille;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class FrontController extends  Controller
{
    public function indexAction()
    {
        /**$veille = new Veille();
        $veille->setTitle('Simple Css');
        $veille->setImage('');
        $veille->setUrl('http://korben.info/category/infos/developpement');
        $veille->setDescription('
Vous devez réaliser une feuille de style pour votre site, mais vous n\'avez pas le temps d\'apprendre le CSS ?

Pas de panique, j\'ai la solution ! Avec Simple CSS, vous allez pouvoir créer la feuille de style basique de vos rêve, en mode click\'o\'drome.');




        $em->persist($veille);

        $em->flush();
        **/

        $repository = $this->getDoctrine()->getRepository("PortfolioBundle:Veille");
        $veilles = $repository->findAll();

        return $this->render('default/index.html.twig', array(
          "veilles" => $veilles));

    }
}