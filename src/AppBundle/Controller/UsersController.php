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
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Doctrine\Common\Collections\ArrayCollection;
#use Symfony\Component\Security\Core\Role\Role;

class UsersController extends Controller
{

    /**
     * @Route("/new/user")
     */

    public function addUser()
    {
        $role = new Role("ROLE_GALLERY_ADD");
        $user = new User();
        $user->setUsername('gal_admin');
        $user->setPlainPassword('admin');
        $user->setEmail('tru_gal_admin@admin.com');
        $user->setEnabled(true);
        $user->addRole($role);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('Dodano nowego użytkownika o ID '.$user->getId());
    }

    /**
     * @Route("/show/users")
     */

    public function showUsers()
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $users = $repository->findAll(); 
      
       return $this->render('show/users.html.twig', array(
           'users' => $users,
       ));
 
    }

    /**
     * @Route("/show/user/{user_id}", requirements={"page": "\d+"})
     */

    public function showUser(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();
       $roles_array = [];
       $name_roles_array = ['ROLE_GALLERY_ADD' => 'Uprawnienia do edytowania galerii', 
                       'ROLE_ARTICLE_ADD' => 'Uprawnienia do edytowania artykułów',
                       'ROLE_CALLENDAR_ADD' => 'Uprawnienia do edytowania kalendarium',
                        'ROLE_USER_ADD' => 'Uprawnienia do edytowania użytkowników', 
                      ];
       foreach($name_roles_array as $role => $_) {
        $roles_array[$role] = in_array($role, $user_roles); 
       }
       return $this->render('show/user.html.twig', array(
           'user' => $user,
           'roles' => $roles_array,
           'name_roles' => $name_roles_array,
       ));
 
    }

    /**
     * @Route("/change_role/{user_id}/{role_name}", requirements={"id" = "\d+"})
     */

    public function changeGalleryRole(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $role_name = $request->attributes->get('role_name');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();
       if(in_array($role_name, $user_roles)){
        $role = new Role($role_name);
        $user->removeRole($role);
        $response = new JsonResponse(array('role' => false));      
       }else{
        $role = new Role($role_name);
        $user->addRole($role);
        $response = new JsonResponse(array('role' => true));      
       }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
       
      return $response;
    }

    /**
     * @Route("/change_article_role/{user_id}", requirements={"id" = "\d+"})
     */

     public function changeArticleRole(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();
       if(in_array('ROLE_ARTICLE_ADD', $user_roles)){
        $role = new Role("ROLE_ARTICLE_ADD");
        $user->removeRole($role);
       }else{
        $role = new Role("ROLE_ARTICLE_ADD");
        $user->addRole($role);
       }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
       
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();

$roles_array = [in_array('ROLE_GALLERY_ADD', $user_roles), in_array('ROLE_ARTICLE_ADD', $user_roles), in_array('ROLE_CALLENDAR_ADD', $user_roles), in_array('ROLE_USER_ADD', $user_roles)     ];
      
      return $this->render('show/user.html.twig', array(
           'user' => $user,
           'roles' => $roles_array,
       ));
 
    }

    /**
     * @Route("/change_callendar_role/{user_id}", requirements={"id" = "\d+"})
     */

    public function changeCallendarRole(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();
       if(in_array('ROLE_CALLENDAR_ADD', $user_roles)){
        $role = new Role("ROLE_CALLENDAR_ADD");
        $user->removeRole($role);
       }else{
        $role = new Role("ROLE_CALLENDAR_ADD");
        $user->addRole($role);
       }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
       
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();

$roles_array = [in_array('ROLE_GALLERY_ADD', $user_roles), in_array('ROLE_ARTICLE_ADD', $user_roles), in_array('ROLE_CALLENDAR_ADD', $user_roles), in_array('ROLE_USER_ADD', $user_roles)     ];
      
      return $this->render('show/user.html.twig', array(
           'user' => $user,
           'roles' => $roles_array,
       ));
 
    }

    /**
     * @Route("/change_user_role/{user_id}", requirements={"id" = "\d+"})
     */

    public function changeUserRole(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();
       if(in_array('ROLE_USER_ADD', $user_roles)){
        $role = new Role("ROLE_USER_ADD");
        $user->removeRole($role);
       }else{
        $role = new Role("ROLE_USER_ADD");
        $user->addRole($role);
       }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
       
       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
       $user_roles = $user->getRoles();

$roles_array = [in_array('ROLE_GALLERY_ADD', $user_roles), in_array('ROLE_ARTICLE_ADD', $user_roles), in_array('ROLE_CALLENDAR_ADD', $user_roles), in_array('ROLE_USER_ADD', $user_roles)     ];
      
      return $this->render('show/user.html.twig', array(
           'user' => $user,
           'roles' => $roles_array,
       ));
 
    }


  
 }
