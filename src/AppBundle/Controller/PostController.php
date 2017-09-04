<?php

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Post;

class PostController extends Controller
{

    /**
     * @param $slug
     * @Route("/post/{slug}",
     *          name="post_details"
     * )
     * @return Response
     */
    public function detailsAction($slug){

        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        /**
         * @var $post Post
         */
        $post = $repository->findOneBySlug($slug);

        if(! $post){
            throw new NotFoundHttpException("post introuvable");
        }

        return $this->render("post/details.html.twig", [
            "post" => $post,
            "answerList" => $post->getAnswers(),


        ]);
    }

    /**
     * @param $year
     * @return Response
     * @Route("/post-par-annee/{year}",
     *          name="post_by_year",
     *          requirements={"year":"\d{4}"})
     */
    public function postByYearAction($year){

        $PostRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");


        return $this->render("default/theme.html.twig",[
            "title" => "Listes des postes de l'années $year ",
            "postList"=>$PostRepository->getPostByYear($year)
        ]);

    }

}