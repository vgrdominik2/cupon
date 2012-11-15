<?php

namespace Cupon\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cupon\CiudadBundle\Entity\Ciudad;
use Cupon\BackendBundle\Form\CiudadType;

/**
 * Ciudad controller.
 *
 */
class CiudadController extends Controller
{
    /**
     * Lists all Ciudad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CiudadBundle:Ciudad')->findAll();

        return $this->render('BackendBundle:Ciudad:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Ciudad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Ciudad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Ciudad entity.
     *
     */
    public function newAction()
    {
        $entity = new Ciudad();
        $form   = $this->createForm(new CiudadType(), $entity);

        return $this->render('BackendBundle:Ciudad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Ciudad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Ciudad();
        $form = $this->createForm(new CiudadType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_ciudad_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Ciudad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ciudad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $editForm = $this->createForm(new CiudadType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Ciudad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Ciudad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ciudad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CiudadType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_ciudad_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Ciudad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Ciudad entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CiudadBundle:Ciudad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ciudad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('backend_ciudad'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
