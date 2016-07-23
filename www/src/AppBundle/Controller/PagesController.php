<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Customer;

class PagesController extends Controller
{
    /**
     * @Route("/", name="pages_index")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('customers_index');
    }
}