<?php
namespace Cupon\OfertaBundle\Twig\Extension;

class CuponExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'cupon';
    }

    public function getFunctions()
    {
        return array(
            'descuento' => new \Twig_Function_Method(
                $this,
                'descuento')
        );
    }

    public function getFilters()
    {
        return array(
            'mostrar_como_lista' => new \Twig_Filter_Method(
                $this,
                'mostrarComoLista',
                array('is_safe' => array('html'))),
            'cuenta_atras' => new \Twig_Filter_Method(
                $this,
                'cuentaAtras',
                array('is_safe' => array('html')))
        );
    }

    public function descuento($precio, $descuento, $decimales = 0)
    {
        if (!is_numeric($precio) || !is_numeric($descuento))
        {
            return '-';
        }

        if ($descuento == 0 || $descuento == null)
        {
            return '0%';
        }

        $precio_original = $precio + $descuento;
        $porcentaje = ($descuento / $precio_original) * 100;

        return '-'.number_format($porcentaje, $decimales).'%';
    }

    public function mostrarComoLista($value, $tipo='ul')
    {
        $html = "<".$tipo.">\n";
        $html .= "<li>\n".str_replace("\n", "\n</li>\n<li>\n", $value)."\n</li>\n";
        $html .= "</".$tipo.">";

        return $html;
    }

    public function cuentaAtras($fecha)
    {
        $fecha = json_encode(array(
            'ano'           => $fecha->format('Y'),
            'mes'           => $fecha->format('m'),
            'dia'           => $fecha->format('d'),
            'hora'          => $fecha->format('H'),
            'minuto'        => $fecha->format('i'),
            'segundo'       => $fecha->format('s')
        ));

        $idAleatorio = 'cuenta-atras-'.rand(1, 100000);
        $html = <<<END
<span id="$idAleatorio"></span>
<script type="text/javascript">
funcion_expira = function() {
    muestraCuentaAtras('$idAleatorio', $fecha);
}
if(!window.addEventListener) {
    window.attachEvent("onload", funcion_expira);
} else {
    window.addEventListener("load", funcion_expira);
}
</script>
END;
        return $html;
    }
}
