<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Anonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Anonce controller.
 *
 * @Route("anonce")
 */
class AnonceController extends Controller
{
    /**
     * Lists all anonce entities.
     *
     * @Route("/", name="anonce_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($game = $request->query->get('game')) {
          $query = $em->createQuery("SELECT p FROM AppBundle:Anonce p WHERE p.jeux = $game");
           $anonces = $query->getResult();
        }
        else $anonces = $em->getRepository('AppBundle:Anonce')->findAll();

        return $this->render('anonce/index.html.twig', array(
            'anonces' => $anonces,
        ));
    }

    /**
     * Creates a new anonce entity.
     *
     * @Route("/new", name="anonce_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $session = $request->getSession();
        if($session->get("id") == NULL) {
          return $this->redirectToRoute('anonce_index');
        }
        $anonce = new Anonce();
        $form = $this->createForm('AppBundle\Form\AnonceType', $anonce);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:Utilisateur', $session->get("id"));
        if ($form->isSubmitted() && $form->isValid()) {
            $avoirs = $em->getRepository('AppBundle:Avoir')->findBy(array("utilisateur" => $session->get("id"), "jeux" => $anonce->getJeux()));
            if($avoirs != NULL) {
              $anonce->setUtilisateur($user);
              $em->persist($anonce);
              $em->flush();

              return $this->redirectToRoute('anonce_show', array('id' => $anonce->getId()));
            }
            return $this->render('anonce/new.html.twig', array(
                'anonce' => $anonce,
                'message' => "You don't have that game",
                'form' => $form->createView(),
            ));
        }

        return $this->render('anonce/new.html.twig', array(
            'anonce' => $anonce,
            'message' => "",
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a anonce entity.
     *
     * @Route("/{id}", name="anonce_show")
     * @Method("GET")
     */
    public function showAction(Anonce $anonce)
    {
        $deleteForm = $this->createDeleteForm($anonce);

        return $this->render('anonce/show.html.twig', array(
            'anonce' => $anonce,
            'message' => '',
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing anonce entity.
     *
     * @Route("/{id}/edit", name="anonce_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Anonce $anonce)
    {
        $deleteForm = $this->createDeleteForm($anonce);
        $editForm = $this->createForm('AppBundle\Form\AnonceType', $anonce);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('anonce_edit', array('id' => $anonce->getId()));
        }

        return $this->render('anonce/edit.html.twig', array(
            'anonce' => $anonce,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a anonce entity.
     *
     * @Route("/{id}", name="anonce_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Anonce $anonce)
    {
        $form = $this->createDeleteForm($anonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($anonce);
            $em->flush();
        }

        return $this->redirectToRoute('anonce_index');
    }

    /**
     * Creates a form to delete a anonce entity.
     *
     * @param Anonce $anonce The anonce entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Anonce $anonce)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('anonce_delete', array('id' => $anonce->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
