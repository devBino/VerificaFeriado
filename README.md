<center><h1>Classe que usa Api para verificar feriados em uma cidade</h1></center>

<h2>1 - Baixe o projeto: </h2>

<b>Por Composer:</b><br>
<p>
    
    composer require verifica/feriado:dev-master
</p>

<br>

<b>Por GitHub:</b><br>
<p>
    Se está usando Linux abra um terminal e rode o comando abaixo: <br> 
    git clone https://github.com/devBino/VerificaFeriado.git
</p>

<br>

<p>
    Caso esteja usando Windows, simplesmente faça download do zip e depois descompacte os arquivos.
</p>

<br>

<h2>2 - Rode o composer na raiz do projeto</h2>

<p>
    <b>composer install</b> (se você iniciando um novo projeto)<br>
    <b>composer update</b> (se o seu projeto já existe e você apenas quer apenas adicionar esse pacote)
</p>

<br>

<h2>3 - Adquira seu token em: <a href='http://www.calendario.com.br/api_feriados_municipais_estaduais_nacionais.php' target='_blank'>http://www.calendario.com.br/api_feriados_municipais_estaduais_nacionais.php</h2>

<br>

<h2>4 - Especificando uma Cidade para a classe</h2>

```
    <?php
    ini_set('default_charset','utf-8');

    require __DIR__ . "/vendor/autoload.php";

    use Feriado\VerificaFeriado;

    /**
     * O construtor da classe recebe respectivamente os parametros
     * ESTADO, CIDADE, ANO, JSON(RETORNAR JSON TRUE OU FALSE)
    */
    $verificar = new VerificaFeriado("SP","SAO_PAULO",2019,true);
    $verificar->setToken('seu token da api');
    $listaFeriados = $verificar->buscaFeriados();

    if( count($listaFeriados) ){
        foreach($listaFeriados as $num => $val){
            //aqui você pode fazer alguma coisa com cada feriado da lista
            //nesse exemplo estamos printando a data e o nome do feriado
            echo $val->date . " - " . $val->name ."<br>";
        }
    }else{
        echo "A Api não retornou nenhum dado...";
    }
    ?>
```

<br>

<p> O resultado será parecido com isso:</p>

<br>

<p>
    01/01/2019 - Ano Novo <br>
    25/01/2019 - Aniversário da Cidade <br>
    04/03/2019 - Carnaval <br>
    05/03/2019 - Carnaval <br>
    06/03/2019 - Carnaval <br>
    01/04/2019 - Dia da Mentira <br>
    19/04/2019 - Sexta-Feira Santa <br>
    19/04/2019 - Sexta-feira Santa <br>
    21/04/2019 - Dia de Tiradentes <br>
    01/05/2019 - Dia do Trabalho <br>
    12/05/2019 - Dia das Mães <br>
    12/06/2019 - Dia dos Namorados <br>
    20/06/2019 - Corpus Christi <br>
    20/06/2019 - Corpus Christi <br>
    09/07/2019 - Revolução Constitucionalista <br>
    11/08/2019 - Dia dos Pais <br>
    07/09/2019 - Independência do Brasil <br>
    12/10/2019 - Nossa Senhora Aparecida <br>
    15/10/2019 - Dia do Professor <br>
    17/10/2019 - Dia do Comércio <br>
    28/10/2019 - Dia do Servidor Público <br>
    02/11/2019 - Dia de Finados <br>
    02/11/2019 - Finados <br>
    15/11/2019 - Proclamação da República <br>
    20/11/2019 - Consciência Negra <br>
    20/11/2019 - Dia da Consciência Negra <br>
    25/12/2019 - Natal <br>
</p>

<br>
<hr>
<h2>5 - Adicionando a classe no Laravel</h2>

<h3>Instalação</h3>

<h4>Use o terminal e acesse a raiz do seu projeto em Laravel e rode os  comandos composer abaixo:</h4>
<p>composer require verifica/feriado:dev-master</p>
<p>composer update</p>

<h3>Token da Api (Se você já tem ignore essa etapa)</h3>

<h4> - Adquira seu token em: <a href='http://www.calendario.com.br/api_feriados_municipais_estaduais_nacionais.php' target='_blank'>http://www.calendario.com.br/api_feriados_municipais_estaduais_nacionais.php</h4>


<h3>Crie uma rota no seu projeto Laravel</h3>

```
<?php
    Route::get('/teste-feriado','Controller@testeFeriado');
?>
```

<h3>Usando a classe no Controller (um controller padrão ou sinta-se a vontade para criar o seu)</h3>

```
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Feriado\VerificaFeriado;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function testeFeriado(){
        
        $verificar = new VerificaFeriado('SP','SAO_PAULO',2019,true);
        
        $verificar->setToken('aqui seu token da api');
        $listaFeriados = $verificar->buscaFeriados();
        
        $data['listaFeriados'] = $listaFeriados;

        return view('feriados')->with($data);
    }
    
}
?>
```

<h3>Crie uma View para exibir os dados</h3>

```
Teste com Classe VerificaFeriado

@if( count($listaFeriados) )
	@foreach( $listaFeriados as $num => $val )
		{{$val->date}} - {{$val->name}}<br>
	@endforeach
@else
	A api não retornou nenhum dado!
@endif
```

<h3>O resultado na view Laravel será parecido com isso:</h3>
<br>
Teste com Classe VerificaFeriado
<br>
<p>
    01/01/2019 - Ano Novo <br>
    25/01/2019 - Aniversário da Cidade <br>
    04/03/2019 - Carnaval <br>
    05/03/2019 - Carnaval <br>
    06/03/2019 - Carnaval <br>
    01/04/2019 - Dia da Mentira <br>
    19/04/2019 - Sexta-Feira Santa <br>
    19/04/2019 - Sexta-feira Santa <br>
    21/04/2019 - Dia de Tiradentes <br>
    01/05/2019 - Dia do Trabalho <br>
    12/05/2019 - Dia das Mães <br>
    12/06/2019 - Dia dos Namorados <br>
    20/06/2019 - Corpus Christi <br>
    20/06/2019 - Corpus Christi <br>
    09/07/2019 - Revolução Constitucionalista <br>
    11/08/2019 - Dia dos Pais <br>
    07/09/2019 - Independência do Brasil <br>
    12/10/2019 - Nossa Senhora Aparecida <br>
    15/10/2019 - Dia do Professor <br>
    17/10/2019 - Dia do Comércio <br>
    28/10/2019 - Dia do Servidor Público <br>
    02/11/2019 - Dia de Finados <br>
    02/11/2019 - Finados <br>
    15/11/2019 - Proclamação da República <br>
    20/11/2019 - Consciência Negra <br>
    20/11/2019 - Dia da Consciência Negra <br>
    25/12/2019 - Natal <br>
</p>
