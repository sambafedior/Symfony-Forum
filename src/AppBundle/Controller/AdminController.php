<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
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
     * @Route("/" , name="admin_home")
     * @return Response
     */
    public function indexAction(){
        return $this->render("admin/index.html.twig");
    }

    /**
     * @return Response
     * @Route("/login" , name="admin_login")
     */
    public function admin_LoginAction(){

        #controle d'erruer de mot de passe
        $securityUtils = $this->get("security.authentication_utils");
        $lastUserName = $securityUtils->getLastUsername();
        $error = $securityUtils->getLastAuthenticationError();


        return $this->render("default/generic-login.html.twig",[
            "action" =>$this->generateUrl("admin_login_check"), #nom de la route declarer dans sécurité et config .yml
            "title"=> "Login des administrateurs",
            "userName" => $lastUserName,
            "error" => $error
        ]);
    }

    /**
     * @Route("/themes", name="admin_themes")
     * @param Request $request
     * @return Response
     */
    public function themeAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $themeList = $repository->findAll();

        #Géneration du formulaire
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);

        #hydratation de l'entité
        $form->handleRequest($request);

        #Traitement du formulaire
        if ($form->isSubmitted() and $form->isValid()) {
            #persistance de l'entité
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            #redirection pour eviter de poster deux fois les données
            return $this->redirectToRoute("admin_themes");
        }


        return $this->render("admin/theme.html.twig", [
            "themeList" => $themeList,
            "themeForm" => $form->createView()]);
    }

    /**
     * @return Response
     * @Route("/secure", name="admin_only_super_admin")
     */
    public function superUserAction(){
        return $this->render("admin/super.htlm.twig");
    }
}