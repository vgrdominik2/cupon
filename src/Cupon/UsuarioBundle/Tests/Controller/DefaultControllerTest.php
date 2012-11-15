<?php

namespace Cupon\UsuarioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider generaUsuarios
     */
    public function testRegistro($usuario)
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/');

        $enlaceRegistro = $crawler->selectLink('Registrate')->link();
        $crawler = $client->click($enlaceRegistro);

        $this->assertGreaterThan(
            0,
            $crawler->filter(
                'html:contains("Regístrate gratis como usuario")'
            )->count(),
            'Después de pulsar el botón Regístrate de la portada, se carga la página
            con el formulario de registro.'
        );

        $formulario = $crawler->selectButton('Registrarme')->form($usuario);

        $this->assertRegExp(
            '/(\d|[a-z])+/',
            $client->getCookieJar()->get('MOCKSESSID')->getValue(),
            'La aplicación ha enviado una cookie de sesión'
        );

        $perfil = $crawler->filter('aside section#login')->selectLink(
            'Ver mi perfil')->link();

        $crawler = $client->click($perfil);

        $perfil = $crawler->filter('aside section#login')->selectLink(
            'Ver mi perfil'
        )->link();

        $crwler = $client->click($perfil);

        $this->assertEquals(
            $usuario['frontend_usuario[email]'],
            $crawler->filter(
                'form inpùt[name="frontend_usuario[email]"]'
            )->attr('value'),
            'El usuario se ha registrado correctamente y sus datos se han guardado en
            la base de datos'
        );
    }

    public function generaUsuarios()
    {
        return array(
            'frontend_usuario[nombre]'              => 'Anónimo',
            'frontend_usuario[apellidos]'           => 'Apellido1 Apellido2',
            'frontend_usuario[email]'               =>
                'anonimo'.uniqid().'@localhost.localdomain',
            'frontend_usuario[password][first]'     => 'anonimo1234',
            'frontend_usuario[password][second]'    => 'anonimo1234',
            'frontend_usuario[direccion]'           => 'Calle....',
            'frontend_usuario[dni]'                 => '11111111H',
            'frontend_usuario[numero_tarjeta]'      => '123456789012345',
            'frontend_usuario[ciudad]'              => '1',
            'frontend_usuario[permite_email]'       => '1'
        );
    }
}
