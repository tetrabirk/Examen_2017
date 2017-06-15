<?php

namespace Birk\MyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Birk\MyBundle\Entity\Events;
use Birk\MyBundle\Entity\Categories;
use Birk\MyBundle\Entity\User;
use Birk\MyBundle\Entity\Image;

class EventsController extends Controller
{
    /**
     * @Route("/")
     * name=events
     */
    public function eventsAllAction()
    {
        $doctrine=$this->getDoctrine();
        $repo = $doctrine->getRepository('BirkMyBundle:Events');
        $allEvents= $repo->findAll();
        
        $content = $this->renderView('BirkMyBundle:Default:eventsAll.html.twig', ['events'=>$allEvents]);
        
        return new Response($content);
    }
    
    /**
     * @Route("/event/{id}")
     * name=eventsOne
     * requirement={"id"="\d+"}
     */
    public function eventsOneAction($id=0)
    {
        if($id !==0){
            $doctrine=$this->getDoctrine();
            $repo = $doctrine->getRepository('BirkMyBundle:Events');
            $event= $repo->find($id);

            $content = $this->renderView('BirkMyBundle:Default:eventsOne.html.twig', ['event'=>$event]);

            return new Response($content);
        }else{
            return new Response('404');
        }
    }
    
    /**
     * @Route("/eventsadd")
     * name=eventsAdd
     */
    public function eventsAddAction()
    {
        
        $emptyEvent = new Events();
        $event = $this->generator($emptyEvent);
        
        $doctrine = $this->getDoctrine();
        
        $em=$doctrine->getManager();
        $em->persist($event);
        $em->flush();
        
        $this->addFlash('notice', 'new event');

        return $this->redirectToRoute('birk_my_events_eventsall');
        
        
    }
    
    /**
     * @Route("/eventupdate/{id}")
     * name=eventsupdate
     * requirement={"id"="\d+"}
     */
    public function eventsUpdateAction($id)
    {
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('BirkMyBundle:Events');
        $oldEvent= $repo->find($id);
        
        $oldEvent = new Events();
        $event = $this->generator($oldEvent);
        
        $em=$doctrine->getManager();
        $em->persist($event);
        $em->flush();
        
        $this->addFlash('notice', 'new event');

        return $this->redirectToRoute('birk_my_events_eventsall');
        
        
    }
    
    
    /**
     * @Route("/eventdelete/{id}")
     * name=eventsdelete
     * requirement={"id"="\d+"}
     */
    public function eventsDeleteAction($id)
    {
        
              
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('BirkMyBundle:Events');
        $event= $repo->find($id);
        
        $em=$doctrine->getManager();
        $em->remove($event);
        $em->flush();
        
        $this->addFlash('notice', 'deleted event');

        return $this->redirectToRoute('birk_my_events_eventsall');
        
        
    }
    
    public function generator($event){
        
        $faker = \Faker\Factory::create('fr_BE');
        
        $event->setNom($faker->text(100));
        $event->setDescription($faker->text(255));
        $event->setDebut($faker->dateTimeBetween('-10 days', 'now'));
        $event->setFin($faker->dateTimeBetween('now','+10 days'));
        
        $categorie = new Categories();
        $categorie->setNom($faker->sentence(2));
        $categorie->setDescription($faker->text(500));
        $event->setCategories($categorie);
        
        $user = new User();
        $user->setUsername($faker->username());
        $user->setProfilePicture($faker->imageUrl(300, 300, 'people'));
        
        $event->addUser($user);
        
        $image = new Image();
        $image->setAlt($faker->text(45));
        $image->setUrl($faker->imageUrl(300, 300, 'abstract'));
        
        $event->setImage($image);
        
        return $event;
        
    }
}
