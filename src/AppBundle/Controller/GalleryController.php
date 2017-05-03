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

class GalleryController extends Controller
{

    /**
     * @Route("/add/gallery")
     */
    public function addGallery(Request $request)
    {
     
       $gallery = new Gallery();
       $form = $this->createFormBuilder($gallery)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Dodaj galerię'))
            ->getForm();
       
   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $gallery = $form->getData();
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($gallery);
        $em->flush();

        $last_id = $gallery->getId();

        return $this->redirect('/show/gallery/'. $last_id );

    }

        return $this->render('add/gallery.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/show/galleries")
     */

    public function showGalleries(Request $request)
    {
       $locale = $request->getLocale();
       $repository = $this->getDoctrine()->getRepository('AppBundle:Gallery');
       $galleries = $repository->findAll(); 

       return $this->render('show/galleries.html.twig', array(
           'galleries' => $galleries,
           'locale' => $locale,
       ));
    }


    /**
     * @Route("/add/image")
     */

    public function addImage(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Gallery');
       $galleries = $repository->findAll(); 

       $image = new Image();
       $form = $this->createFormBuilder($image)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('gallery', EntityType::class, array(
              'class' => 'AppBundle:Gallery',
              'choice_label' => 'name',
            ))
            ->add('full_size', FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Dodaj obrazek'))
            ->getForm();
       
   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
        $image = $form->getData();
        $file = $image->getFullSize();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where images are stored
        $file->move(
            $this->getParameter('images_directory'),
            $fileName
        );
        $image->setFullSize($fileName);        
        $smallFileName = $fileName;

        //$imageToProcess = '/images/'.$image->getFullSize(); //$this->getParameter('images_directory').'/'.$image->getFullSize(); 
        //$processedImage = $this->container->get('liip_imagine.data.manager')->find('my_thumb', $imageToProcess );
        //$filteredImage = $this->container->get('liip_imagine.filter.manager')->getFilterConfiguration($request, 'my_thumb', $processedImage, $imageToProcess)->getContent();

        //$smallfileName = md5(uniqid()).'.'.$filteredImage_image->guessExtension();
        //$filteredImage->move(
        //    $this->getParameter('images_directory'),
        //    $smallfileName
        //);
        
        $image->setSmallSize($smallFileName);        
        
        $image->setAuthor($this->container->get('security.token_storage')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();
        $last_id = $image->getId();

        return $this->redirect('/show/image/'. $last_id );

    }

        return $this->render('add/image.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/show/images")
     */

    public function showImages()
    {
     
       $repository = $this->getDoctrine()->getRepository('AppBundle:Image');
       $images = $repository->findAll(); 

       return $this->render('show/images.html.twig', array(
           'images' => $images,
       ));
    }

    /**
     * @Route("/show/gallery/{gallery_id}", requirements={"page": "\d+"})
     */

    public function showGallery(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Gallery');
       $gallery_id = $request->attributes->get('gallery_id');
       $gallery = $repository->findOneById($gallery_id);
      
       return $this->render('show/gallery.html.twig', array(
           'gallery' => $gallery,
       ));
 
    }

    /**
     * @Route("/show/image/{image_id}", requirements={"page": "\d+"})
     */

    public function showImage(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Image');
       $image_id = $request->attributes->get('image_id');
       $image = $repository->findOneById($image_id);
      
       return $this->render('show/image.html.twig', array(
           'image' => $image,
       ));
 
    }

}
