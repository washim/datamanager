<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Entity\Customers;
use AppBundle\Entity\Emis;
use AppBundle\Form\CustomersType;

/**
 * Customers controller.
 *
 * @Route("/customers")
 */
class CustomersController extends Controller
{
    /**
     * @Route("/", name="customers_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
		$where = array();
		$em = $this->get('doctrine.orm.entity_manager');
		$dql = "SELECT c FROM AppBundle:Customers c";
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

        return $this->render('customers/index.html.twig', array(
            'customers' => $pagination,
        ));
    }

    /**
     * @Route("/new", name="customers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $expire = time() < strtotime(base64_decode($this->container->getParameter('product_key'))) ? false : true;
        $customer = new Customers();
        $form = $this->createForm(CustomersType::class, $customer);
        $form->handleRequest($request);

        if ($expire === true) {
            $this->addFlash(
                'error',
                base64_decode($this->container->getParameter('product_msg'))
            );
        }
        elseif ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if (!empty($customer->getPath())) {
                $file = $customer->getPath();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->container->getParameter('customerpic'), $fileName);
                $customer->setPath($fileName);
            }
            $em->persist($customer);
            $em->flush();
            
			$this->addFlash(
				'success',
				'Customer created successfully.'
			);

            return $this->redirectToRoute('customers_show', array('id' => $customer->getId()));
        }

        return $this->render('customers/new.html.twig', array(
            'customer' => $customer,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="customers_show")
     * @Method("GET")
     */
    public function showAction(Customers $customer)
    {
        $deleteForm = $this->createDeleteForm($customer);

        return $this->render('customers/show.html.twig', array(
            'customer' => $customer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="customers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Customers $customer)
    {
        $path = $customer->getPath();
        $form = $this->createForm('AppBundle\Form\CustomersType', $customer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if (empty($customer->getPath())) {
                $customer->setPath($path);
            }
            else {
                $file = $customer->getPath();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->container->getParameter('customerpic'), $fileName);
                $customer->setPath($fileName);
            }
            $em->persist($customer);
            $em->flush();
			
			$this->addFlash(
				'success',
				'Customer updated successfully.'
			);

            return $this->redirectToRoute('customers_edit', array('id' => $customer->getId()));
        }

        return $this->render('customers/new.html.twig', array(
            'customer' => $customer,
            'form' => $form->createView(),
        ));
    }
	
	/**
     * @Route("/{id}/delete", name="customers_normal_delete")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Method({"GET"})
     */
	public function delAction(Request $request, Customers $customer) {
		$em = $this->getDoctrine()->getManager();
		$em->remove($customer);
		$em->flush();
		
		$this->addFlash(
			'success',
			'Customer deleted successfully.'
		);
			
		return $this->redirectToRoute('customers_index');
	}

    /**
     * @Route("/{id}", name="customers_delete")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Customers $customer)
    {
        $form = $this->createDeleteForm($customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customer);
            $em->flush();
			
			$this->addFlash(
				'success',
				'Customer deleted successfully.'
			);
        }

        return $this->redirectToRoute('customers_index');
    }

    private function createDeleteForm(Customers $customer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customers_delete', array('id' => $customer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
