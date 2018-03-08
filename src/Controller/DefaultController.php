<?php

namespace App\Controller;

use App\Entity\Helper\FormHelper;
use App\Service\FileService;
use App\Service\TableMaker;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $fileService = new FileService();
        $logs = $fileService->getLogModels();
        return $this->render('base.html.twig', array(
            'logs' => $logs
        ));
    }
}