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
       //$request = $this->getRequest();
       $user_id = $request->attributes->get('user_id');
       $user = $repository->findOneById($user_id);
      
       return $this->render('show/user.html.twig', array(
           'user' => $user,
       ));
 
    }


}
