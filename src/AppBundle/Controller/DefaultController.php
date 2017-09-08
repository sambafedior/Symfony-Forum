<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Entity\Post;
use AppBundle\Form\AuthorType;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository("AppBundle:Theme");

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
    public function themeAction($slug, Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $theme = $repository->findOneBySlug($slug);

        $allTheme = $repository->getAllTheme()->getArrayResult();

        if (!$theme) {
            throw new NotFoundHttpException("Thème introuvable");
        }

        #Gestion des nouveau post
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $formView = null;


        #affichage du formulaire que si le role de l'utilisateur est ROLE_AUTHOR
        if (in_array("ROLE_AUTHOR", $roles)) {

            #création du formulaire
            $post = new Post();
            $post->setCreatedAt(new \DateTime());
            $post->setAuthor($user);
            $post->setTheme($theme);

            #$form = $this->createForm(PostType::class, $post);

            #hydratation de l'entité
            #$form->handleRequest($request);
            $formHandler = $this->get("post.form_handler");
            $formHandler->setPost($post);

            #Traitement du formulaire
            if ($formHandler->process()) {
                #redirection pour eviter de poster deux fois les données
                return $this->redirectToRoute("homepage");
            }
            #fin de la gestion des nouveau posts
            #$formView = $form->createView();
            $formView=$formHandler->getFormView();

        }

        return $this->render('default/theme.html.twig', [
            "theme" => $theme,
            "postList" => $theme->getPosts(),
            "all" => $allTheme,
            "postForm" => $formView
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/inscription", name="author_registration")
     */
    public function registrationAction(Request $request)
    {
        #instatioation de l'antité author
        $author = new Author();
        #creation du formulaire
        $form = $this->createForm(AuthorType::class, $author);
        #hydratation
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            #récuperation de l'entité manager
            $em = $this->getDoctrine()->getManager();

            #encodage du mot de passe
            #récuperation de l'encoder qui est lié a une entité particulière
            $encoderFactory = $this->get("security.encoder_factory");
            $encoder = $encoderFactory->getEncoder($author);
            $author->setPassword($encoder->encodePassword($author->getPlainPassword(), null));
            $author->setPlainPassword(null);

            #persistance de l'entité
            $em->persist($author);
            $em->flush();
        }

        return $this->render("default/author-registration.htlm.twig", ["registrationForm" => $form->createView()]);

    }

    /**
     * @return Response
     * @Route("/author/login", name="author_login" )
     */
    public function authorLoginAction()
    {

        $securityUtils = $this->get("security.authentication_utils");
        $lasUsername = $securityUtils->getLastUsername();
        $error = $securityUtils->getLastAuthenticationError();

        return $this->render("default/generic-login.html.twig", [
            "title" => "Identification des auteurs",
            "action" => $this->generateUrl("author_login_check"),
            "userName" => $lasUsername,
            "error" => $error

        ]);
    }

    /**
     * @return Response
     * @Route("/test-service")
     */
    public function testService()
    {
        $helloService = $this->get("service.hello");
        $helloService->setName("Samba");
        $newHelloService = $this->get("service.hello");
        $message = $helloService->sayHello() . " " . $newHelloService->sayHello();
        return $this->render("default/test-service.html.twig", array("message" => $message));
    }
}
