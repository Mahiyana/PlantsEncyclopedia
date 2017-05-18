<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\Image;
use AppBundle\Entity\Role;
use AppBundle\Entity\Category;
use AppBundle\Entity\Article;
use AppBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Doctrine\Common\Collections\ArrayCollection;
#use Symfony\Component\Security\Core\Role\Role;

class CallendarController extends Controller
{
    /**
     * @Route("show/callendar")
     */
    public function showCallendar(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Event');
       $events = $repository->findAll(); 

       return $this->render('show/callendar.html.twig', array(
           'events' => $events,
       ));

        
        return $this->render('show/callendar.html.twig');
    }

    /**
     * @Route("/add/event")
     */
    public function addEvent(Request $request)
    {
     
       $event = new Event();
       $form = $this->createFormBuilder($event)
            ->add('name', TextType::class, array('label' => $this->get('translator')->trans('Nazwa')))
            ->add('date', DateTimeType::class, array('label' => $this->get('translator')->trans('Data') ))
            ->add('anniversary', CheckboxType::class, array(
              'label' => $this->get('translator')->trans('Rocznica'),
              'required' => false,
              ))
            ->add('save', SubmitType::class, array('label' => $this->get('translator')->trans('Dodaj wydarzenie')))
            ->getForm();
       
   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $event = $form->getData();
        
        $event->setAuthor($this->container->get('security.token_storage')->getToken()->getUser());
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        return $this->redirect('/show/callendar' );
        
        }
        return $this->render('add/event.html.twig', array(
            'form' => $form->createView(),
        ));


   }


}
