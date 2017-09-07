<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AnswerController extends Controller
{

    public function answerAction()
    {

        return $this->render("post/details.html.twig");
    }
}
