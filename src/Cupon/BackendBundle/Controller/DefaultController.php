<?php

namespace Cupon\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    public function ciudadCambiarAction($ciudad)
    {
        $this->getRequest()->getSession()->set('ciudad', $ciudad);

        $dondeEstaba = $this->getRequest()->server->get('HTTP_REFERER');
        return new RedirectResponse($dondeEstaba, 302);
    }
}
