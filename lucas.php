<?php

echo date('Y-m-d H:i:s') . "<hr>";
/*
  $str = "Lucas";
  $tam = 0;
  $numLinha = 1;
  for ($i = 0; $i < strlen($str); $i++) {
  $tam += ord($str[$i]);
  }

  $path = "teste.txt";
  $modulo = ($tam % 20);
  $arquivoAutor = file_exists($path) ? fopen('teste.txt', 'r+') : fwrite($path, "");

  echo "Tamanho: " . $tam;
  echo "<br>";
  echo "Modulo: " . $modulo;
  echo "<br>";

  if ($arquivoAutor == false)
  die('Não foi possível criar o arquivo.');

  if ($arquivoAutor) {
  while (true) {
  $linha = fgets($arquivoAutor);
  //echo "Linha: " . $linha . "<br>";
  if ($linha == null)
  break;


  if ($numLinha == $modulo) {
  $string .= str_replace("\r", "", str_replace("\n", "", $linha)) . ", " . $str . "\r\n"; //assim?
  } else {
  $string .= $linha;
  }

  echo "Linha " . $numLinha . ": " . $string;
  echo "<br>";
  $numLinha++;
  }
  // move o ponteiro para o inicio pois o ftruncate() nao fara isso
  rewind($arquivoAutor);
  // truca o arquivo apagando tudo dentro deleÏ
  ftruncate($arquivoAutor, 0);
  // reescreve o conteudo dentro do arquivo
  if (!fwrite($arquivoAutor, $string))
  die('Não foi possível atualizar o arquivo.');

  echo 'Arquivo atualizado com sucesso';
  fclose($arquivoAutor);
  }
  ?>
 * */

function escreve($filename, $conteudo) {
    // Primeiro vamos ter certeza de que o arquivo existe e pode ser alterado
    if (is_writable($filename)) {
        // Em nosso exemplo, nós vamos abrir o arquivo $filename
        // em modo de adição. O ponteiro do arquivo estará no final
        // do arquivo, e é pra lá que $conteudo irá quando o 
        // escrevermos com fwrite().
        if (!$handle = fopen($filename, 'a')) {
            die("Não foi possível abrir o arquivo ($filename)");
        }

        // Escreve $conteudo no nosso arquivo aberto.
        if (fwrite($handle, $conteudo) === FALSE) {
            die("Não foi possível escrever no arquivo ($filename)");
        }


        fclose($handle);
    } else {
        die("O arquivo $filename não pode ser alterado");
    }
}

function criaVinteLinhas($path) {
    for ($index = 0; $index < 20; $index++) {
        escreve($path, PHP_EOL);
    }
}

function abreArquivo($path) {
    if (file_exists($path) === FALSE) {
        echo "Arquivo nao existe. abreArquivo()";
        $ok = touch($path); //altera data e hora do arquivo, da pra usar como gambiarra pra criar o arquivo
        if (!$ok)
            die('Não foi possível criar o arquivo. abreArquivo()<br>');

        criaVinteLinhas($path);
    }else {
        echo "Arquivo existe. abreArquivo()<hr>";
    }
    $handler = fopen($path, 'r+');
    if (!$handler)
        die('Não foi possível abrir o arquivo. abreArquivo()<br>');


    return $handler;
}

function getLinhaModulo($modulo, $limite) {
    /*
     * Necessario pois o modulo pode resultar em 0, que 
     * neste caso deveria ser a ultima linha do arquivo.
     */
    $linha = ($modulo == 0) ? $limite : $modulo;
    return $linha;
}

$path = "teste.txt";
$arquivoAutor = abreArquivo($path);

$arr = array("Lucas", "Henrique", "Cristiano");

/*
  include "henrique.php";
  $u = Utils();
  $arr = $u->getPosts();
  foreach ($arr as $a) {
    $autores[] = $a['post']['from']['name'];
  }
 */
$maxLinhasArquivo = 20;
foreach ($arr as $elemento) {
    echo "$elemento<br>";

    $tam = 0;
    $numLinha = 0;
    $string = "";
    for ($i = 0; $i < strlen($elemento); $i++) {
        $tam += ord($elemento[$i]);
    }
    $modulo = ($tam % $maxLinhasArquivo);

    echo "Tamanho: $tam<br>";
    echo "Modulo: $modulo<br>";
    echo "Linha do arquivo: " . getLinhaModulo($modulo, $maxLinhasArquivo) . "<hr>";

    if ($arquivoAutor) {
        while (true) {
            $linha = fgets($arquivoAutor);
            $numLinha++;
            if ($linha == null)
                break;

            if ($numLinha == getLinhaModulo($modulo, $maxLinhasArquivo)) {
                //$string .= str_replace("\r", "", str_replace("\n", "", $linha)) . ", " . $elemento . "\r\n"; //assim?
                $string .= str_replace(PHP_EOL, "", $linha) . ", " . $elemento . PHP_EOL; //assim?
                $debug = str_replace(PHP_EOL, "", $linha) . ", " . $elemento . PHP_EOL; //assim?;
            } else {
                $string .= $linha;
                $debug = $linha;
            }
            echo "Linha " . $numLinha . ": " . $debug;
            echo "<br>";
        }
    }
    // move o ponteiro para o inicio pois o ftruncate() nao fara isso
    rewind($arquivoAutor);
    // truca o arquivo apagando tudo dentro deleÏ
    ftruncate($arquivoAutor, 0);

    // reescreve o conteudo dentro do arquivo
    escreve($path, $string);
}

/*
  $json = learquivo();
  $array = json_decode($json, true);
  $end = calculaEnd();
  $array[$end];

  $end = calculaEnd();
  $array[$end];
  $json = json_encode($$array);
  grava($json)
 */

echo 'Arquivo atualizado com sucesso';

fclose($arquivoAutor);



/*
 * Para busca
 * $autor
 * $linha = getLinhaModulo($valorASCII);
 * while($linha > 0)
 *  $lido = fgets($arq);
 * 
 * $array = explode($lido, ",");
 * unset($array[0]);
 * foreach ($arr as $key => $value){
 *  explode($value, "|"); // autor|linha, autor|linha, autor|linha
 *  //le arquivo post_autor.txt na linha "linha"
 *  if($value == $autor)
 *      return $key;
 * }
 * 
 */
?>