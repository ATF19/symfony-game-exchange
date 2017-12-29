<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Avoir;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();

        $anonces = $em->getRepository('AppBundle:Anonce')->findBy(array(), null, "8","0");

        return $this->render('default/index.html.twig', array(
            'anonces' => $anonces,
        ));

    }

    /**
     * @Route("/addgame", name="add")
     */
    public function addGameAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $session = $request->getSession();
        $user = $em->find('AppBundle:Utilisateur', $session->get("id"));
        $game = $em->find('AppBundle:jeux', $id);
        $avoir = new Avoir();
        $avoir->setJeux($game);
        $avoir->setUtilisateur($user);
        $em->persist($avoir);
        $em->flush();
        return $this->redirectToRoute("jeux_index",array("success" => " added to your games !"));
    }

    /**
     * Lists all jeux entities.
     *
     * @Route("/mygames", name="mygames")
     */
    public function mygamesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $avoirs = $em->getRepository('AppBundle:Avoir')->findBy(array("utilisateur" => $session->get("id")));
        $avoirs_jeux=array();
        foreach ($avoirs as $value) {
          array_push($avoirs_jeux, $value->getJeux());
        }
        return $this->render('jeux/mygames.html.twig', array(
            'jeuxes' => $avoirs_jeux,
        ));
    }

    /**
    * @Route("/removegame", name="removegame")
    */
   public function removegameAction(Request $request)
   {
       $em = $this->getDoctrine()->getManager();
       $session = $request->getSession();
       $id = $request->query->get('id');
       $avoir = $em->getRepository('AppBundle:Avoir')->findOneBy(array("utilisateur" => $session->get("id"), "jeux" => $id));
       $em->remove($avoir);
       $em->flush();
       $avoirs = $em->getRepository('AppBundle:Avoir')->findBy(array("utilisateur" => $session->get("id")));
       $jeuxes = $em->getRepository('AppBundle:jeux')->findBy(array("id" => $avoirs));
       return $this->redirectToRoute('mygames', array(
           'jeuxes' => $jeuxes,
       ));
   }

    /**
     * @Route("/exchange", name="exchange")
     */
    public function exchangeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $anonceId = $request->query->get('id');
        $jeux = $request->query->get('jeux');
        $jeuxDemandee = $request->query->get('jeuxDemandee');
        $utilisateur = $request->query->get('utilisateur');
        $utilisateurCourant = $session->get("id");

        $avoirUtilisateur = $em->getRepository('AppBundle:Avoir')->findOneBy(array("utilisateur" => $utilisateur, "jeux" => $jeux));
        $avoirUtilisateurCourant = $em->getRepository('AppBundle:Avoir')->findOneBy(array("utilisateur" => $utilisateurCourant, "jeux" => $jeuxDemandee));
        if(!$avoirUtilisateurCourant)
          return $this->redirect('http://127.0.0.1:8000/anonce/'.$anonceId.'?error=true');
        $avoirUtilisateur->setJeux($em->getRepository('AppBundle:jeux')->find($jeuxDemandee));
        $avoirUtilisateurCourant->setJeux($em->getRepository('AppBundle:jeux')->find($jeux));
        $anonce = $em->getRepository('AppBundle:Anonce')->find($anonceId);
        $em->remove($anonce);
        $em->flush();
        return $this->redirect('http://127.0.0.1:8000/mygames');
    }
}
