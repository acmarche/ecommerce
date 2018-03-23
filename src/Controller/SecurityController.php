<?php

namespace App\Controller;

use App\Form\Security\LostPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        $form_lost = $this->createForm(
            LostPasswordType::class,
            null,
            [
                'action' => $this->generateUrl('acecommerce_lost_password'),
            ]
        );

        return $this->render(
            'security/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
                'form_lost' => $form_lost->createView(),
            )
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {

    }


}
