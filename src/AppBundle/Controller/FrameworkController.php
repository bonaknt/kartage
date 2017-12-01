<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Framework;
use AppBundle\Entity\Link;
use AppBundle\Entity\Category;
use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Framework controller.
 *
 * @Route("/admin/framework")
 */
class FrameworkController extends Controller
{
    /**
     * Lists all framework entities.
     *
     * @Route("/", name="admin_framework_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $frameworks = $em->getRepository('AppBundle:Framework')->findAll();

        return $this->render('framework/index.html.twig', array(
            'frameworks' => $frameworks,
        ));
    }

    /**
     * Creates a new framework entity.
     *
     * @Route("/new", name="admin_framework_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $framework = new Framework();
        $gestionCategorie = $this->container->get('app.categorie');
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Link');

        $category = $repository->findCategory();

        $form = $this
            ->get('form.factory')
            ->create('AppBundle\Form\FrameworkType', $framework)

        ->add('language', ChoiceType::class, array(
        //  on inverse les clés et valeurs
        'choices'   => array_flip($category),
        'label'     => "Catégorie",
        'attr'  => ['class' => 'form-control'],
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $gestionCat = $gestionCategorie->language($category, $form['language']->getData(), $categories);

            $em = $this->getDoctrine()->getManager();
            $framework->setLanguage($gestionCat->getCategories());
            $em = $this->getDoctrine()->getManager();
            $em->persist($framework);
            $em->flush();

            return $this->redirectToRoute('admin_framework_show', array('id' => $framework->getId()));
        }

        return $this->render('framework/new.html.twig', array(
            'framework' => $framework,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a framework entity.
     *
     * @Route("/{id}", name="admin_framework_show")
     * @Method("GET")
     */
    public function showAction(Framework $framework)
    {
        $deleteForm = $this->createDeleteForm($framework);

        return $this->render('framework/show.html.twig', array(
            'framework' => $framework,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing framework entity.
     *
     * @Route("/{id}/edit", name="admin_framework_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Framework $framework)
    {
        $deleteForm = $this->createDeleteForm($framework);
        $editForm = $this->createForm('AppBundle\Form\FrameworkType', $framework);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_framework_edit', array('id' => $framework->getId()));
        }

        return $this->render('framework/edit.html.twig', array(
            'framework' => $framework,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a framework entity.
     *
     * @Route("/{id}", name="admin_framework_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Framework $framework)
    {
        $form = $this->createDeleteForm($framework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($framework);
            $em->flush();
        }

        return $this->redirectToRoute('admin_framework_index');
    }

    /**
     * Creates a form to delete a framework entity.
     *
     * @param Framework $framework The framework entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Framework $framework)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_framework_delete', array('id' => $framework->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
