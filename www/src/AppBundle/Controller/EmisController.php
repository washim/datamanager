<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Emis;
use AppBundle\Entity\Customers;
use AppBundle\Form\EmisType;

/**
 * Emis controller.
 *
 * @Route("/emis")
 */
class EmisController extends Controller
{
    /**
     * Creates a new Emis entity.
     *
     * @Route("/new", name="emis_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $emi = new Emis();
        $emi->setDueDt(new \DateTime('+1 week'));
        $form = $this->createForm('AppBundle\Form\EmisType', $emi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($emi);
            $em->flush();
            
            $this->addFlash(
				'success',
				'EMI created successfully.'
			);

            return $this->redirectToRoute('emis_new');
        }

        return $this->render('emis/new.html.twig', array(
            'emi' => $emi,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/{id}/create", name="emis_create")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request, Customers $customer)
    {
        if (empty($customer->getEmis()->last())) {
            $duedt = new \DateTime('+1 week');
        }
        else {
            $duedt = new \DateTime($customer->getEmis()->last()->getDueDt()->format('Y-m-d H:i:s'));
            $duedt->modify('+1 week');
        }
        
        $emi = new Emis();
        $emi->setCustomer($customer);
        $emi->setDueDt($duedt);
        
        $form = $this->createForm('AppBundle\Form\EmisType', $emi);
        $form->handleRequest($request);
        
        $expire = time() < strtotime(base64_decode($this->container->getParameter('product_key'))) ? false : true;
        
        if ($expire === true) {
            $this->addFlash(
                'error',
                base64_decode($this->container->getParameter('product_msg'))
            );
        }
        elseif ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($emi);
            $em->flush();
            
            $this->addFlash(
				'success',
				'EMI created successfully.'
			);

            return $this->redirectToRoute('customers_show', ['id' => $emi->getCustomer()->getId()]);
        }

        return $this->render('emis/new.html.twig', array(
            'emi' => $emi,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Emis entity.
     *
     * @Route("/{id}", name="emis_show")
     * @Method("GET")
     */
    public function showAction(Emis $emi)
    {
        $deleteForm = $this->createDeleteForm($emi);

        return $this->render('emis/show.html.twig', array(
            'emi' => $emi,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="emis_edit")
     */
    public function editAction(Request $request, Emis $emi)
    {
        $form = $this->createDeleteForm($emi);
        $form = $this->createForm('AppBundle\Form\EmisType', $emi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($emi);
            $em->flush();
            
            $this->addFlash(
				'success',
				'EMI updated successfully.'
			);

            return $this->redirectToRoute('customers_show', ['id' => $emi->getCustomer()->getId()]);
        }

        return $this->render('emis/new.html.twig', [
            'emi' => $emi,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="emis_delete")
     */
    public function deleteAction(Request $request, Emis $emi)
    {
        $em = $this->getDoctrine()->getManager();
		$em->remove($emi);
		$em->flush();
		
		$this->addFlash(
			'success',
			'Emi deleted successfully.'
		);
			
		return $this->redirectToRoute('customers_show', ['id' => $emi->getCustomer()->getId()]);
    }

    /**
     * Creates a form to delete a Emis entity.
     *
     * @param Emis $emi The Emis entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Emis $emi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('emis_delete', array('id' => $emi->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
