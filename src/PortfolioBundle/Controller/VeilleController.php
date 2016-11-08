<?php

namespace PortfolioBundle\Controller;

use PortfolioBundle\Entity\Veille;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Veille controller.
 *
 */
class VeilleController extends Controller
{
    /**
     * Lists all veille entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $veilles = $em->getRepository('PortfolioBundle:Veille')->findAll();

        return $this->render('veille/index.html.twig', array(
            'veilles' => $veilles,
        ));
    }

    /**
     * Creates a new veille entity.
     *
     */
    public function newAction(Request $request)
    {
        $veille = new Veille();
        $form = $this->createForm('PortfolioBundle\Form\VeilleType', $veille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $veille->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $veille->setImage($fileName);

            // ... persist the $product variable or any other work
            $em->persist($veille);
            $em->flush($veille);

            return $this->redirectToRoute('veille_show', array('id' => $veille->getId()));
        }

        return $this->render('veille/new.html.twig', array(
            'veille' => $veille,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a veille entity.
     *
     */
    public function showAction(Veille $veille)
    {
        $deleteForm = $this->createDeleteForm($veille);

        return $this->render('veille/show.html.twig', array(
            'veille' => $veille,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing veille entity.
     *
     */
    public function editAction(Request $request, Veille $veille)
    {
        $deleteForm = $this->createDeleteForm($veille);
        $editForm = $this->createForm('PortfolioBundle\Form\VeilleType', $veille);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('veille_edit', array('id' => $veille->getId()));
        }

        return $this->render('veille/edit.html.twig', array(
            'veille' => $veille,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a veille entity.
     *
     */
    public function deleteAction(Request $request, Veille $veille)
    {
        $form = $this->createDeleteForm($veille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($veille);
            $em->flush($veille);
        }

        return $this->redirectToRoute('veille_index');
    }

    /**
     * Creates a form to delete a veille entity.
     *
     * @param Veille $veille The veille entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Veille $veille)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('veille_delete', array('id' => $veille->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
