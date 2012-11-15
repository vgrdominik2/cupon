<?php

namespace Cupon\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cupon\OfertaBundle\Entity\Oferta;
use Cupon\BackendBundle\Form\OfertaType;

/**
 * Oferta controller.
 *
 */
class OfertaController extends Controller
{
    /**
     * Lists all Oferta entities.
     *
     */
    public function indexAction()
    {
        $slug = $this->getRequest()->getSession()->get('ciudad');

        $em = $this->getDoctrine()->getManager();

        $paginador = $this->get('ideup.simple_paginator');

        $entities = $paginador->paginate(
            $em->getRepository('OfertaBundle:Oferta')->queryTodasLasOfertas($slug)
        )->getResult();

        return $this->render('BackendBundle:Oferta:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Oferta entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Oferta:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Oferta entity.
     *
     */
    public function newAction()
    {
        $entity = new Oferta();
        $form   = $this->createForm(new OfertaType(), $entity);

        return $this->render('BackendBundle:Oferta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Oferta entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Oferta();
        $form = $this->createForm(new OfertaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('oferta_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Oferta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Oferta entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $editForm = $this->createForm(new OfertaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Oferta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Oferta entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OfertaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Oferta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Oferta entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OfertaBundle:Oferta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Oferta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('BackendPortada'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
