<?php 


namespace App\Service ;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * service chargé de créer et d'envoyer des emails
 */
class EmailSender
{
private $mailer;

public function __construct(MailerInterface $mailer)
{
    $this->mailer = $mailer;
}

    /**
     * créer un mail préconfiguré
     * @param string $subject   le sujet du mail
     */
    private function createTemplatedEmail(string $subject): TemplatedEmail
    {
        return (new TemplatedEmail())
        ->from(new Address('Satkamy@gmail.com','toto'))   #expediteur
        ->subject("\u{1F3A7} kritik| $subject")
        ;

    }
    /**
     * Enovyer un email de confirmation de compte suite à l'inscription
     */

     public function sendAccountConfirmationEmail(User $user):void
     {
        $email = $this->createTemplatedEmail('confirmation du compte')
        ->to(new Address($user->getEmail(), $user->getPseudo())) #Destinataire
        ->htmlTemplate('email/account_confirmation.html.twig') #template twig du message
        ->context([
            'user'=> $user,
             ]) 
        ;
        //envoi de l'email
        $this->mailer->send($email);
     }
}