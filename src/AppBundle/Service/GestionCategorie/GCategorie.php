<?php
namespace AppBundle\Service\GestionCategorie;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\component\HttpFoundation\Session\Session;

use AppBundle\Entity\Link;

class GCategorie
{
  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  public function categorie($category, $formCategorie, $categories, $subCategory, $formSousCategorie, $subCategories)
  {

    $link = new Link();

    foreach ($category as $key => $value) {
        if($key == $formCategorie){
            $maCategorie = $value;
        }
    }

    foreach ($categories as $keys => $values) {
        if($maCategorie == $values->getTitle()){
            $categorie = $values;
        }
    }

    foreach ($subCategory as $key => $value) {
        if($key == $formSousCategorie){
            $maSubCategorie = $value;
        }
    }

    foreach ($subCategories as $keys => $values) {
        if($maSubCategorie == $values->getTitle()){
            $subCategorie = $values;
        }
    }

    $link->setSousCategories($subCategorie->getId());
    //dump($form['sousCategories']->getData()); die();
    $link->setCategories($categorie->getId());


    return $link;

  }
}