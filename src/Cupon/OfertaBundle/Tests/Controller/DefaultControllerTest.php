<?php

namespace Cupon\OfertaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /** @test */
    public function condicionesDePortada()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Portada de una ciudad (status 200)');

        $ofertasActivas = $crawler->filter(
            'article section.descripcion a:contains("Comprar")' )->count();

        $this->assertEquals(1, $ofertasActivas,
            'La portada muestra una única oferta activa que se puede comprar');

        $numeroEnlacesRegistrarse = $crawler->filter(
            'html:contains("Regístrate")'
        )->count();

        $this->assertGreaterThan(0, $numeroEnlacesRegistrarse,
            'La portada muestra al menos un enlace o botón para registrarse');
    }

    /** @test */
    public function losUsuariosAnonimosVenLaCiudadPorDefecto()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/');

        $ciudadPorDefecto = $client->getContainer()->getParameter(
            'cupon.ciudad_por_defecto'
        );

        $ciudadPortada = $crawler->filter(
            'header nav select option[selected="selected"]'
        )->attr('value');

        $this->assertEquals($ciudadPorDefecto, $ciudadPortada,
            'Los usuarios anónimos ven seleccionada la ciudad por defecto');
    }
}
