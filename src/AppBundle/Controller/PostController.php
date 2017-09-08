<?php

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use AppBundle\Entity\Answer;
use AppBundle\Entity\Vote;
use AppBundle\Form\AnswerType;
use AppBundle\Form\PostType;
use AppBundle\Form\VoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Post;

class PostController extends Controller
{

    /**
     * @param $slug
     * @Route("/post/{slug}",name="post_details")
     * @return Response
     */
    public function detailsAction($slug, Request $request)
    {

        $repository = $this->getDoctrine()->getRepository("AppBundle:Post");

        /**
         * @var $post Post
         */
        $post = $repository->findOneBySlug($slug);

        if (!$post) {
            throw new NotFoundHttpException("post introuvable");
        }

        $author = $this->getUser();

        #instatiation de l'antité answer
        $answer = new Answer();



        #configuration par defaut
        $answer->setAuthor($author);
        $answer->setPost($post);
        $answer->setCreatedAt(new \DateTime());



        #création du formulaire
        $form = $this->createForm(AnswerType::class, $answer);


        #hydratation de l'entité avec la requete
        $form->handleRequest($request);


        if ($form->isValid() and $form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);

            #persistance du vote

            $em->flush();

            return $this->redirectToRoute("post_details", ["slug" => $post->getSlug()
            ]);
        }

        return $this->render("post/details.html.twig", [
            "post" => $post,
            "answerList" => $post->getAnswers(),
            "answerForm" => $form->createView(),

        ]);
    }


     /**
     * @Route("/answer-vote-like/{id}", name="answer_vote_like")
     * @param Answer $answer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function voteLikeAction(Answer $answer, Request $request)
    {

        $vote = new Vote();
        $vote->setAnswer($answer)->setAuthor($this->getUser())->setVote(1);

        $em = $this->getDoctrine()->getManager();

        #persistance du vote
        $em->persist($vote);
        $em->flush();

        $referer = $request->headers->get("referer");

        return $this->redirect($referer);

    }

    /**
     * @Route("/answer-vote-dislike/{id}", name="answer_vote_dislike")
     * @param Answer $answer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function voteDislikeAction(Answer $answer, Request $request){
        $vote = new Vote();
        $vote->setAnswer($answer)->setAuthor($this->getUser())->setVote(-1);

        $em = $this->getDoctrine()->getManager();

        #persistance du vote
        $em->persist($vote);
        $em->flush();

        $referer = $request->headers->get("referer");

        return $this->redirect($referer);
    }

    /**
     * @param $year
     * @return Response
     * @Route("/post-par-annee/{year}",
     *          name="post_by_year",
     *          requirements={"year":"\d{4}"})
     */
    public function postByYearAction($year)
    {

        $PostRepository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        return $this->render("default/theme.html.twig", [
            "title" => "Listes des postes de l'années $year ",
            "postList" => $PostRepository->getPostByYear($year)
        ]);

    }


    /**
     * @param Request $request
     * @param Post $post
     * @param $slug
     * @return Response
     * @Route("/post/modif/{slug}", name="post_edit")
     */
    public function editAction(Request $request, Post $post, $slug)
    {
        #sécurisation de l'operation
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $userId = isset($user) ? $user->getId() : null;
        if (!in_array("ROLE_AUTHOR", $roles) || $userId != $post->getAuthor()->getId()) {
            throw new AccessDeniedException("Vous n'avez pas les droits pour modifier ce post ");
        }

        #création du formulaire
        $form = $this->createForm(PostType::class, $post);

        #hydratation de l'entité avec la requete
        $form->handleRequest($request);

        if ($form->isValid() and $form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("post_details", ["slug" => $post->getSlug()]
            );
        }

        return $this->render("post/edit.html.twig", ["postForm" => $form->createView()]);

    }

}