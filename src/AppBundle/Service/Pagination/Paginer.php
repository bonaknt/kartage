<?php
namespace AppBundle\Service\Pagination;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\component\HttpFoundation\Session\Session;
use AppBundle\Entity\Link;
use AppBundle\Entity\Category;
use AppBundle\Entity\Users;

class Paginer
{
  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  public function paginationLienAdmin($em, $published, $user)
  {
    $lienParPage = 10;

    $liensTotal = $em->getRepository('AppBundle:Link')->byPublishedTotal($published);

    $liensTotal = count($liensTotal);

    $pagesTotales = ceil($liensTotal / $lienParPage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $pagesTotales) {
       $_GET['page'] = intval($_GET['page']);
       $pageCourante = $_GET['page'];
    } else {
       $pageCourante = 1;
    }
    $depart = ($pageCourante-1)*$lienParPage;
    
    $links = $em->getRepository('AppBundle:Link')->byPublished($published, $depart, $lienParPage);

    $forRender = array($pagesTotales, $pageCourante, $links);

    return $forRender;


  }

  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  public function paginationLienUser($em, $published, $user)
  {
    $lienParPage = 10;

    $liensTotal = $em->getRepository('AppBundle:Link')->byUserTotal($user->getUsername(), $published);

    $liensTotal = count($liensTotal);

    $pagesTotales = ceil($liensTotal / $lienParPage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $pagesTotales) {
       $_GET['page'] = intval($_GET['page']);
       $pageCourante = $_GET['page'];
    } else {
       $pageCourante = 1;
    }
    $depart = ($pageCourante-1)*$lienParPage;

    $links = $em->getRepository('AppBundle:Link')->byUser($user->getUsername(), $published, $depart, $lienParPage);

    $forRender = array($pagesTotales, $pageCourante, $links);

    return $forRender;


  }

  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  public function paginationLienIndex($em, $published, $idSousCategorie, $id, $idFramework)
  {
    $lienParPage = 10;

    $liensTotal = $em->getRepository('AppBundle:Link')->bySousCategorieTotal($idSousCategorie, $id, $idFramework, $published);

    $liensTotal = count($liensTotal);

    $pagesTotales = ceil($liensTotal / $lienParPage);

    if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $pagesTotales) {
       $_GET['page'] = intval($_GET['page']);
       $pageCourante = $_GET['page'];
    } else {
       $pageCourante = 1;
    }
    $depart = ($pageCourante-1)*$lienParPage;
    
    $links = $em->getRepository('AppBundle:Link')->bySousCategorie($idSousCategorie, $id, $idFramework, $published, $depart, $lienParPage);

    $forRender = array($pagesTotales, $pageCourante, $links);

    return $forRender;


  }
}