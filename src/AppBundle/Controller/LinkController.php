<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use AppBundle\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
/**
 * Link controller.
 *
 * @Route("admin/liens")
 */
class LinkController extends Controller
{
    /**
     * Lists all link entities.
     *
     * @Route("/", name="link_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $links = $em->getRepository('AppBundle:Link')->findAll();

        return $this->render('link/index.html.twig', array(
            'links' => $links,
        ));
    }

    /**
     * Creates a new link entity.
     *
     * @Route("/new", name="link_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $subCategories = $em->getRepository('AppBundle:SubCategory')->findAll();
        $gestionCategorie = $this->container->get('app.categorie');
        $link = new Link();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Link');

        $category = $repository->findCategory();
        $subCategory = $repository->findSubCategory();

        $form = $this
            ->get('form.factory')
            ->create('AppBundle\Form\LinkType', $link)

            ->add('categories', ChoiceType::class, array(
                //  on inverse les clés et valeurs
                'choices'   => array_flip($category),
                'label'     => "Catégorie",
                'attr'  => ['class' => 'form-control'],
            ))
            ->add('sousCategories', ChoiceType::class, array(
                //  on inverse les clés et valeurs
                'choices'   => array_flip($subCategory),
                'label'     => "Sous-catégorie",
                'attr'  => ['class' => 'form-control'],
            ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gestionCat = $gestionCategorie->categorie($category, $form['categories']->getData(), $categories, $subCategory, $form['sousCategories']->getData(), $subCategories);

            $em = $this->getDoctrine()->getManager();
            $link->setSousCategories($gestionCat->getSousCategories());
            $link->setCategories($gestionCat->getCategories());
            $em->persist($link);
            $em->flush();

            return $this->redirectToRoute('link_show', array('id' => $link->getId()));
        }

        return $this->render('link/new.html.twig', array(
            'link' => $link,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a link entity.
     *
     * @Route("/{id}", name="link_show")
     * @Method("GET")
     */
    public function showAction(Link $link)
    {
        $deleteForm = $this->createDeleteForm($link);

        return $this->render('link/show.html.twig', array(
            'link' => $link,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing link entity.
     *
     * @Route("/{id}/edit", name="link_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Link $link)
    {
        $deleteForm = $this->createDeleteForm($link);
        $editForm = $this->createForm('AppBundle\Form\LinkType', $link);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_edit', array('id' => $link->getId()));
        }

        return $this->render('link/edit.html.twig', array(
            'link' => $link,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a link entity.
     *
     * @Route("/{id}", name="link_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Link $link)
    {
        $form = $this->createDeleteForm($link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($link);
            $em->flush();
        }

        return $this->redirectToRoute('link_index');
    }

    /**
     * Creates a form to delete a link entity.
     *
     * @param Link $link The link entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Link $link)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('link_delete', array('id' => $link->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
