<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\SubCategory;
use AppBundle\Entity\Link;
use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class AdminController extends Controller
{
	/**
	 * @Route("/admin/", name="admin")
	 */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $subCategories = $em->getRepository('AppBundle:SubCategory')->findAll();

        $links = $em->getRepository('AppBundle:Link')->findAll();

        return $this->render('admin/index.html.twig', array(
            'categories' => $categories,
            'subCategories' => $subCategories,
            'links' => $links,
        ));
    }

}
