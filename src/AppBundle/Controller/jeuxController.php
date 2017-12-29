<?php

namespace AppBundle\Controller;

use AppBundle\Entity\jeux;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Jeux controller.
 *
 * @Route("jeux")
 */
class jeuxController extends Controller
{
    /**
     * Lists all jeux entities.
     *
     * @Route("/", name="jeux_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($search = $request->query->get('search')) {
          $query = $em->createQuery("SELECT p FROM AppBundle:jeux p WHERE p.libelle LIKE '%".$search."%'");
           $jeuxes = $query->getResult();
        }
        else if ($cat = $request->query->get('cat')) {
          $query = $em->createQuery("SELECT p FROM AppBundle:jeux p WHERE p.console LIKE '%".$cat."%'");
           $jeuxes = $query->getResult();
        }
        else $jeuxes = $em->getRepository('AppBundle:jeux')->findAll();

        return $this->render('jeux/index.html.twig', array(
            'jeuxes' => $jeuxes,
        ));
    }

    /**
     * Creates a new jeux entity.
     *
     * @Route("/new", name="jeux_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $jeux = new Jeux();
        $form = $this->createForm('AppBundle\Form\jeuxType', $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($jeux);
            $em->flush();

            return $this->redirectToRoute('jeux_show', array('id' => $jeux->getId()));
        }

        return $this->render('jeux/new.html.twig', array(
            'jeux' => $jeux,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a jeux entity.
     *
     * @Route("/{id}", name="jeux_show")
     * @Method("GET")
     */
    public function showAction(jeux $jeux)
    {
        $deleteForm = $this->createDeleteForm($jeux);

        return $this->render('jeux/show.html.twig', array(
            'jeux' => $jeux,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing jeux entity.
     *
     * @Route("/{id}/edit", name="jeux_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, jeux $jeux)
    {
        $deleteForm = $this->createDeleteForm($jeux);
        $editForm = $this->createForm('AppBundle\Form\jeuxType', $jeux);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jeux_edit', array('id' => $jeux->getId()));
        }

        return $this->render('jeux/edit.html.twig', array(
            'jeux' => $jeux,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a jeux entity.
     *
     * @Route("/{id}", name="jeux_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, jeux $jeux)
    {
        $form = $this->createDeleteForm($jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jeux);
            $em->flush();
        }

        return $this->redirectToRoute('jeux_index');
    }

    /**
     * Creates a form to delete a jeux entity.
     *
     * @param jeux $jeux The jeux entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(jeux $jeux)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jeux_delete', array('id' => $jeux->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
