<?php


namespace AppBundle\Service\Mail;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use AppBundle\Controller\DefaultController;
use Symfony\component\HttpFoundation\Session\Session;

class ApplicationMailer
{

  public function sendEmail($mailBodyHTML, $mailer)
  {
    $session = new Session();


      //  Envoie d'email
      $message = \Swift_Message::newInstance();
      $message->setSubject("Mail à partir de Kartage");
      $message->setFrom('m.konatedev@gmail.com');
      $message->setTo('m.konatedev@gmail.com');
      // pour envoyer le message en HTML
      $message->setBody(
          $mailBodyHTML,
          'text/html');
      //envoi du message
      $mailer->send($message);
  }
}
