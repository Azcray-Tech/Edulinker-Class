<?php

function tiempo_transcurrido($datetime) {
    $ahora = new DateTime();
    $pasado = new DateTime($datetime);
    $intervalo = $ahora->diff($pasado);

    $tiempo = '';

    if ($intervalo->y > 0) {
        $tiempo .= $intervalo->y . ' año' . ($intervalo->y > 1 ? 's' : '');
    } elseif ($intervalo->m > 0) {
        $tiempo .= $intervalo->m . ' mes' . ($intervalo->m > 1 ? 'es' : '');
    } elseif ($intervalo->d > 0) {
        $tiempo .= $intervalo->d . ' día' . ($intervalo->d > 1 ? 's' : '');
    } elseif ($intervalo->h > 0) {
        $tiempo .= $intervalo->h . ' hora' . ($intervalo->h > 1 ? 's' : '');
    } elseif ($intervalo->i > 0) {
        $tiempo .= $intervalo->i . ' minuto' . ($intervalo->i > 1 ? 's' : '');
    } else {
        $tiempo .= $intervalo->s . ' segundo' . ($intervalo->s != 1 ? 's' : '');
    }

    return 'hace ' . $tiempo;
}

?>

