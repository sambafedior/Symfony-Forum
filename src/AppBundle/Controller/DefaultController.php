<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $PostsRepository = $this->getDoctrine()->getRepository("AppBundle:Post");



        $list = $repository->getAllTheme()->getArrayResult();

        $PostsGroupByYear = $PostsRepository->getPostsGroupByYear();

        return $this->render('default/index.html.twig', [
            "themeList" => $list,
            "PostsGroupByYear" => $PostsGroupByYear
        ]);
    }

    /**
     * @Route("/theme/{slug}", name="theme_details")
     * @param $slug
     * @return Response
     */
    public function themeAction($slug)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $theme = $repository->findOneBySlug($slug);

        $allTheme = $repository->getAllTheme()->getArrayResult();

        if (!$theme) {
            throw new NotFoundHttpException("Thème introuvable");
        }

        return $this->render('default/theme.html.twig', [
            "theme" => $theme,
            "postList" => $theme->getPosts(),
            "all" => $allTheme]);
    }
}
