<?php

namespace App\Service;

use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommerceInterface;
use Symfony\Component\Form\Form;

Class MailerService
{
    private $swiftMailer;
    private $twig;
    private $from;

    public function __construct(string $from, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->swiftMailer = $mailer;
        $this->twig = $twig;
        $this->from = $from;
    }

    public function send($from, $destinataires, $sujet, $body)
    {
        $mail = new \Swift_Message($sujet);
        $mail->setFrom($from)
            ->setBcc("jf@marche.be")
            ->setTo($destinataires);
        $mail->setBody($body);

        $this->swiftMailer->send($mail);
    }

    /**
     * @param CommerceInterface $commerce
     * @param Form $form
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendNewContact(CommerceInterface $commerce, Form $form)
    {
        $data = $form->getData();
        $from = $data->getEmail();
        $sujet = "Contact sur ecommerce";
        $destinataires = [];

        $body = $this->twig->render(
            'email/front/contact_commercant.html.twig',
            array(
                'data' => $data,
            )
        );

        try {
            $this->send($from, $destinataires, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

    /**
     * @param CommandeInterface $commande
     * @param $from
     * @param $to
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendCommandePaye(CommandeInterface $commande, $from, $to)
    {
        $sujet = "Nouvelle commande pour ".$commande->getCommerceNom()." de ".$commande->getUser();

        $body = $this->twig->render(
            'email/front/commande_paye.html.twig',
            array(
                'commande' => $commande,
            )
        );

        try {
            $this->send($from, $to, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

    /**
     * @param CommandeInterface $commande
     * @param $from
     * @param $to
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendCommandeLivre(CommandeInterface $commande, $from, $to)
    {
        $sujet = "livraison de la commande pour ".$commande->getCommerceNom()." de ".$commande->getUser();

        $body = $this->twig->render(
            'email/front/commande_livre.html.twig',
            array(
                'commande' => $commande,
            )
        );

        try {
            $this->send($from, $to, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

    /**
     * @param $email
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendPasswordLost($email)
    {
        $sujet = 'Rappel de votre compte';
        $body = $this->twig->render(
            'mail/_lost_password.html.twig',
            array(
                'email' => $email,
            )
        );

        try {
            $this->send($this->from, $email, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

    /**
     * @param \Swift_Message $message
     * @param array $vars
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function plainText(\Swift_Message $message, array $vars)
    {
        $message->addPart(
            $this->twig->render(
                'emails/registration.txt.twig',
                $vars
            ),
            'text/plain'
        );
    }

}
