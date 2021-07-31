<?php

include 'vendor/autoload.php';

$resultado_pdf = "ED_6__2019__DPDF_DEFENSOR_RES_PROVISORIO_OBJETIVA.PDF";

$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($resultado_pdf);

$text = $pdf->getText(); //Obtendo string do pdf

$listas = explode("1.1.1", $text); //separando as listas de candidatos 

function criaLista($stringFromPdf, $tipoLista){
    $stringFromPdf = preg_replace('/\s\d\b/',"",$stringFromPdf); //removendo números de páginas
    $stringFromPdf = preg_replace('/\n/'," ",$stringFromPdf); // removendo quebra de linhas

    preg_match_all('/\d+\,.+\w+\,.+\d+\.\d+(\/|\.)/', $stringFromPdf, $res); // separando lista de candidatos
    $candidatos = explode("/",$res[0][0]); //separando candidatos

    //Cabeçalho da tabela
    $dadosDeSaida = "Número de Inscrição,Nome do candidato,Número de acertos na prova objetiva,Nota provisória na prova objetiva\n";

    foreach($candidatos as $candidato){
        if($candidato[-1] == "."){
            $candidato = substr_replace($candidato, "", -1); // remover o ponto final dos candidos que o possuem na ultima nota
        }
        $candidato = preg_replace('/( | )+/'," ", $candidato); // removendo excesso de espaços
        $candidato = preg_replace('/\,( )/',",", $candidato); // removendo espaco do início
        $dadosDeSaida = $dadosDeSaida.$candidato."\n"; // concatena a string para criação do csv
    }

    $csv = fopen($tipoLista.".csv","w"); // cria o arquivo de saída
    fwrite($csv,$dadosDeSaida);          // escreve o arquivo de saída
}

criaLista($listas[0],"Ampla_concorrencia");
criaLista($listas[1],"Candidatos_com_deficiencia");

?>