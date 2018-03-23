<?php

namespace App\Controller;

use App\Form\Security\LostPasswordType;
use App\Form\Security\RegistrationType;
use App\Manager\UserManager;
use App\Repository\Security\UserRepository;
use App\Service\MailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterController
 *
 * @Route("/register")
 *
 */
class RegisterController extends AbstractController
{
    /**
     * @Route("/", name="acecommerce_register")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function index(Request $request, UserManager $userManager)
    {
        $user = $userManager->newInstance();

        $form = $this->createForm(RegistrationType::class, $user)
            ->add('submit', SubmitType::class, ['label' => 'Créer']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->insert($user);
            $this->addFlash('success', 'Compte créé');

            return $this->redirectToRoute('acecommerce_home');
        }

        return ['form' => $form->createView()];

    }

    /**
     * @Route("/lost", name="acecommerce_lost_password")
     * @Method({"GET", "POST"})
     *
     */
    public function lost(
        Request $request,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        MailerService $mailer
    ) {
        $form = $this->createForm(
            LostPasswordType::class
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData();
            $violations = $validator->validate($email, [new Email()]);

            if (0 !== count($violations)) {
                $this->addFlash('warning', 'Adresse mail invalide');

                return $this->redirectToRoute('login');
            }

            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user) {
                $mailer->sendPasswordLost($email);
                $this->addFlash('success', 'Un mail vous a été envoyé');
            } else {
                $this->addFlash('warning', 'Aucun utilisateur trouvé avec cette adresse mail');
            }
        }

        return $this->redirectToRoute('login');
    }
}
