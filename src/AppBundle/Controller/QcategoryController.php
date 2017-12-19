<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Qcategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Qcategory controller.
 *
 * @Route("qcategory")
 */
class QcategoryController extends Controller
{
    /**
     * Lists all qcategory entities.
     *
     * @Route("/", name="qcategory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qcategories = $em->getRepository('AppBundle:Qcategory')->findAll();

        return $this->render('qcategory/index.html.twig', array(
            'qcategories' => $qcategories,
        ));
    }

    /**
     * Creates a new qcategory entity.
     *
     * @Route("/new", name="qcategory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qcategory = new Qcategory();
        $form = $this->createForm('AppBundle\Form\QcategoryType', $qcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qcategory);
            $em->flush();

            return $this->redirectToRoute('qcategory_show', array('id' => $qcategory->getId()));
        }

        return $this->render('qcategory/new.html.twig', array(
            'qcategory' => $qcategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qcategory entity.
     *
     * @Route("/{id}", name="qcategory_show")
     * @Method("GET")
     */
    public function showAction(Qcategory $qcategory)
    {
        $deleteForm = $this->createDeleteForm($qcategory);

        return $this->render('qcategory/show.html.twig', array(
            'qcategory' => $qcategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qcategory entity.
     *
     * @Route("/{id}/edit", name="qcategory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Qcategory $qcategory)
    {
        $deleteForm = $this->createDeleteForm($qcategory);
        $editForm = $this->createForm('AppBundle\Form\QcategoryType', $qcategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('qcategory_edit', array('id' => $qcategory->getId()));
        }

        return $this->render('qcategory/edit.html.twig', array(
            'qcategory' => $qcategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qcategory entity.
     *
     * @Route("/{id}", name="qcategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Qcategory $qcategory)
    {
        $form = $this->createDeleteForm($qcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qcategory);
            $em->flush();
        }

        return $this->redirectToRoute('qcategory_index');
    }

    /**
     * Creates a form to delete a qcategory entity.
     *
     * @param Qcategory $qcategory The qcategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Qcategory $qcategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('qcategory_delete', array('id' => $qcategory->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
