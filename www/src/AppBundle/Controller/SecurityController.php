<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;

class SecurityController extends Controller
{
	/**
     * @Route("/login", name="login")
     */
	public function loginAction(Request $request)
    {
		$authenticationUtils = $this->get('security.authentication_utils');
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		
		return $this->render(
			'security/login.html.twig',
			array(
				'last_username' => $lastUsername,
				'error'         => $error,
			)
		);
    }
	
	/**
     * @Route("/logout", name="logout")
     */
	public function logoutAction()
	{
		
	}
	
	/**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);
        
        $validator = $this->get('validator');
        $validator->validate($user);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'You looged in successfully.'
            );

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }
}