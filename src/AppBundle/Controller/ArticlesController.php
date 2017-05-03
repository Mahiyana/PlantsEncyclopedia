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

class ArticlesController extends Controller
{

   /**
     * @Route("/add/category")
     */
    public function addCategory(Request $request)
    {
     
       $category = new Category();
       $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Dodaj kategorię'))
            ->getForm();
       
   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $category = $form->getData();
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $last_id = $category->getId();

        return $this->redirect('/show/category/'. $last_id );

    }

        return $this->render('add/category.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/show/categories")
     */

    public function showCategories()
    {
     
       $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
       $categories = $repository->findAll(); 

       return $this->render('show/categories.html.twig', array(
           'categories' => $categories,
       ));
    }
   
    /**
     * @Route("/show/category/{category_id}", requirements={"page": "\d+"})
     */

    public function showCategory(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
       $category_id = $request->attributes->get('category_id');
       $category = $repository->findOneById($category_id);
      
       return $this->render('show/category.html.twig', array(
           'category' => $category,
       ));
 
    }

    /**
     * @Route("/add/article")
     */

    public function addArticle(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
       $categories = $repository->findAll(); 

       $article = new Article();
       $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('category', EntityType::class, array(
              'class' => 'AppBundle:Category',
              'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class, array('label' => 'Dodaj artykuł'))
            ->getForm();
       
   
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
        $article = $form->getData();

        $article->setAuthor($this->container->get('security.token_storage')->getToken()->getUser());
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        $last_id = $article->getId();

        return $this->redirect('/show/article/'. $last_id );

    }

        return $this->render('add/article.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/show/article/{article_id}", requirements={"page": "\d+"})
     */

    public function showArticle(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
       $article_id = $request->attributes->get('article_id');
       $article = $repository->findOneById($article_id);
      
       return $this->render('show/article.html.twig', array(
           'article' => $article,
       ));
 
    }

    /**
     * @Route("/edit/article/{article_id}", requirements={"page": "\d+"})
     */

    public function editArticle(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
       $categories = $repository->findAll(); 

       $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
       $article_id = $request->attributes->get('article_id');
       $article = $repository->findOneById($article_id);
       
       $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('category', EntityType::class, array(
              'class' => 'AppBundle:Category',
              'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class, array('label' => 'Zapisz'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
        $article = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        $last_id = $article->getId();

        return $this->redirect('/show/article/'. $last_id );

    }

        return $this->render('edit/article.html.twig', array(
            'form' => $form->createView(),
        ));

    }



}
