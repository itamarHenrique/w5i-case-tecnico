<?php

function calcularTempo($dataInicio, $dataFim, $tempoEstimadoPorHora){
    
    if (!$dataInicio) {
        return [
            'formatado' => '---',
            'estourou' => false
        ];
    }

    $inicio = new DateTime($dataInicio);

    if ($dataFim) {
        $fim = new DateTime($dataFim);
    } else {
        $fim = new DateTime();
    }

    $intervalo = $inicio->diff($fim);


    $horasTotaisUsadas = ($intervalo->days * 24) + $intervalo->h + ($intervalo->i / 60);

    $estourou = $horasTotaisUsadas >= $tempoEstimadoPorHora;

    if ($intervalo->days > 0){
        $formatado = $intervalo->format('%a dias, %h horas, %i minutos e %s segundos');
    }else{
        $formatado = $intervalo->format('%h horas, %i minutos e %s segundos');
    }

    return [
        'formatado' => $formatado,
        'estourou' => $estourou
    ];
}