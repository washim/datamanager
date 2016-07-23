<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Groups;
use AppBundle\Form\GroupsType;

/**
 * Groups controller.
 *
 * @Route("/groups")
 */
class GroupsController extends Controller
{
    /**
     * Lists all Groups entities.
     *
     * @Route("/", name="groups_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $where = array();
		$em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT c FROM AppBundle:Groups c";
		foreach ($request->query->all() as $key => $value) {
			if (!empty($key) && !empty($value))
			$where[] = "c." . $key . "='$value'";
		}
		if (count($where) > 0) {
			$where = " WHERE " . implode(' AND ', $where);
			$dql .= $where;
		}
        $query = $em->createQuery($dql);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            100
        );

        $groups = $em->getRepository('AppBundle:Groups')->findAll();

        return $this->render('groups/index.html.twig', array(
            'groups' => $pagination,
        ));
    }

    /**
     * Creates a new Groups entity.
     *
     * @Route("/new", name="groups_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $group = new Groups();
		$group->author = $this->getUser();
		
        $form = $this->createForm('AppBundle\Form\GroupsType', $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
			
			$this->addFlash(
				'success',
				'Group created successfully.'
			);

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }

        return $this->render('groups/new.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Groups entity.
     *
     * @Route("/{id}", name="groups_show")
     * @Method("GET")
     */
    public function showAction(Groups $group)
    {
        return $this->render('groups/show.html.twig', array(
            'group' => $group,
        ));
    }

    /**
     * Displays a form to edit an existing Groups entity.
     *
     * @Route("/{id}/edit", name="groups_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Groups $group)
    {
        $form = $this->createForm('AppBundle\Form\GroupsType', $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
			
			$this->addFlash(
				'success',
				'Group updated successfully.'
			);

            return $this->redirectToRoute('groups_edit', array('id' => $group->getId()));
        }

        return $this->render('groups/new.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a Groups entity.
     *
     * @Route("/{id}/delete", name="groups_normal_delete")
     * @Method("GET")
     */
    public function delAction(Request $request, Groups $group)
    {
        try
		{
			$em = $this->getDoctrine()->getManager();
			$em->remove($group);
			$em->flush();
			
			$this->addFlash(
				'success',
				'Group deleted successfully.'
			);

			return $this->redirectToRoute('groups_index');
		}
		catch(\Exception $e)
		{
			$this->addFlash('error', 'Delete may not possible for now because one of your customer exist with this group.');
		}
		
		return $this->redirectToRoute('groups_index');
    }
	
	/**
     * Deletes a Groups entity.
     *
     * @Route("/{id}", name="groups_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Groups $group)
    {
        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
			
			$this->addFlash(
				'success',
				'Group deleted successfully.'
			);
        }

        return $this->redirectToRoute('groups_index');
    }

    /**
     * Creates a form to delete a Groups entity.
     *
     * @param Groups $group The Groups entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groups $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groups_delete', array('id' => $group->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
