<?php
namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SitioController extends Controller
{
    public function estaticaAction($pagina)
    {
        return $this->render(sprintf(
            'OfertaBundle:Sitio:%s%s.html.twig',
            $this->getRequest()->gerLocale(),
            $pagina
        ));
    }
}