<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\SubCategory;
use AppBundle\Entity\Link;
use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('default/index.html.twig', array(
            'categories' => $categories,
        ));
    }


    /**
     * Finds and displays a link entity.
     *
     * @Route("/contact", name="contact")
     * @Method({"GET", "POST"})
     */
    public function contactAction(Request $request)
    {

        $contact = new Contact();
        $form = $this->createForm('AppBundle\Form\ContactType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailer = $this->get('mailer');
            $mailBodyHTML = $this->render('default/email.html.twig', [
            'mail'                =>     $contact->getEmail(),
            'name'                =>     $contact->getName(),
            'subject'               =>     $contact->getSubject(),
            'content'             =>     $contact->getContent(),
            ])->getContent();



            $email = $this->container->get('app.mailer');
            $email->sendEmail($mailBodyHTML, $mailer);
            return $this->redirectToRoute('contact');

        }
        // replace this example code with whatever you need
        return $this->render('default/contact.html.twig', array(

            'form' => $form->createView(),
        ));

    }


    /**
     * Finds and displays a link entity.
     *
     * @Route("/lien/{id}", name="lien_view")
     * @Method("GET")
     */
    public function showlinkAction(Link $link)
    {

        return $this->render('default/lien.html.twig', array(
            'link' => $link,
        ));
    }


    /**
     * Finds and displays a category entity.
     *
     * @Route("/login", name="login")
     * @Method("GET")
     */
    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;
        return $this->render('default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="categorie_show")
     * @Method("GET")
     */
    public function showCategoryAction($id)
    {
    	//$id = 3;
    	//dump($id); die();
    	$em = $this->getDoctrine()->getManager();

        $subCategories = $em->getRepository('AppBundle:SubCategory')->findAll();
        $category = $em	->getRepository('AppBundle:Category')->find($id);
        $framework = $em ->getRepository('AppBundle:Framework')->byLanguage($id);


        //dump($category); die();
        return $this->render('default/category.html.twig', array(
            'category' => $category,
            'subCategories' => $subCategories,
            'framework'     => $framework,
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/framework/{id}/{idFramework}", name="framework_show")
     * @Method("GET")
     */
    public function showFrameworkAction($id, $idFramework)
    {
        //$id = 3;
        //dump($id); die();
        $em = $this->getDoctrine()->getManager();

        $subCategories = $em->getRepository('AppBundle:SubCategory')->findAll();
        //$category = $em ->getRepository('AppBundle:Category')->find($id);
        $framework = $em ->getRepository('AppBundle:Framework')->find($idFramework);

        $category = $em ->getRepository('AppBundle:Category')->find($id);


        //dump($category); die();
        return $this->render('default/frameworkshow.html.twig', array(
            'subCategories' => $subCategories,
            'framework'     => $framework,
            'category'   => $category, 
        ));
    }

    /**
     * Finds and displays a subCategory entity.
     *
     * @Route("/{id}/{idSousCategorie}", name="subcategorie_show")
     * @Method("GET")
     */
    public function showSubAction($idSousCategorie, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $published = true;
        $idFramework = 0;
        $pagination = $this->container->get('app.pagination');
        $categorie = $em->getRepository('AppBundle:Link')->byCategorie($id);

        $pagesTotales = $pagination->paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)[0];
        $pageCourante = $pagination->paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)[1];
        $links = $pagination->paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)[2];

        return $this->render('default/subcategory.html.twig', array(
            //'subCategory' => $subCategory,
            'links'		  => $links,
            'categorie'   => $categorie,
            'pagesTotales' => $pagesTotales, 
            'pageCourante' => $pageCourante,	
        )); 
    }

    /**
     * Finds and displays a subCategory entity.
     *
     * @Route("/framework/{id}/{idFramework}/{idSousCategorie}", name="framework_subcategorie_show")
     * @Method("GET")
     */
    public function showSubFrameworkAction($idSousCategorie, $id, $idFramework)
    {

        $em = $this->getDoctrine()->getManager();

        $published = true;

        $pagination = $this->container->get('app.pagination');

        $categorie = $em ->getRepository('AppBundle:Link')->byCategorie($id);

        $pagesTotales = $pagination->paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)[0];
        $pageCourante = $pagination->paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)[1];
        $links = $pagination->paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)[2];

        return $this->render('default/subcategory.html.twig', array(
            //'subCategory' => $subCategory,
            'links'       => $links,
            'categorie'   => $categorie,
            'pagesTotales' => $pagesTotales, 
            'pageCourante' => $pageCourante,     
        )); 
    }

}
