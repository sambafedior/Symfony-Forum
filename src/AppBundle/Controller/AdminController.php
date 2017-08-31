<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{

    /**
     * @Route("/themes", name="admin_themes")
     * @return Response
     */
    public function themeAction(){
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $themeList = $repository->findAll();

        return $this->render("admin/theme.html.twig", ["themeList" => $themeList]);
    }

}