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
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Doctrine\Common\Collections\ArrayCollection;
#use Symfony\Component\Security\Core\Role\Role;
use DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Event');
       $events = $repository->findAll(); 
     
       uasort($events, function($a, $b){
         if ($a->getTimestamp() == $b->getTimestamp()) {
           return 0;
         }
         return ($a->getTimestamp()< $b->getTimestamp()) ? -1 : 1;
       }); 
       
       $now = new DateTime('now');
       $now_timestamp = $now->getTimestamp();
       $closest_events = [];
       $i = 0;
       foreach($events as $event){
         if($event->getTimestamp() > $now_timestamp and $i < 4){
           array_push($closest_events,$event);
           $i += 1;
         }
       }
       
       $events = $closest_events;

       $now2 = new DateTime('now');
       $tommorow = $now2->modify('+1 day');
       $diffs = [];
       foreach($events as $event){
       if($event->getAnniversary()){
         $year = (int) $event->getDate()->format('Y');
         $yearDiff = 2017 - $year;
         $event->setDate($event->getDate()->modify('+'. $yearDiff .' years'));
       }
         $diff = ltrim($now->diff($event->getDate())->format('%R%a'),"+");
         if($diff == "0"){
           array_push($diffs, $this->get('translator')->trans('jest dziś'));
         }
         else if($diff == "1"){
            $msg = $this->get('translator')->trans('będzie jutro');
            array_push($diffs,$msg);
         }
         else if($now<$event->getDate()){
           $msg = $this->get('translator')->trans('będzie za') . " " . $diff . " " . $this->get('translator')->trans('dni');
           array_push($diffs,$msg);
         }else{
           array_push($diffs, $this->get('translator')->trans('już było'));
         }
       }
       
       #print_r($diffs);

       return $this->render('default/index.html.twig', array(
           'events' => $events,
           'diffs' => $diffs,
           'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
       ));
        //return $this->render('default/index.html.twig', [
        //    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //]);
    }



   /**
    * @Route("/change_language/{_locale}", requirements={"_locale" = "pl|en"})
    */
    public function changeLanguage(Request $request){ 
        return $this->redirect($request->headers->get('referer','/'));
    }

}
