<?php

namespace Birk\MyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Birk\MyBundle\Entity\Categories;

class CategoriesController extends Controller
{
    /**
     * @Route("/categoriesall")
     * name=categoriesall
     */
    public function categoriesAllAction()
    {
        $doctrine=$this->getDoctrine();
        $repo = $doctrine->getRepository('BirkMyBundle:Categories');
        $allCategories= $repo->findAll();
        
        $content = $this->renderView('BirkMyBundle:Default:categoriesAll.html.twig', ['categories'=>$allCategories]);
        
        return new Response($content);
    }
    
}
