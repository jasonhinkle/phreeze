<?php include_once '_header-ptbr.php' ?>

<h3 id="top">Phreeze Builder</h3>

<img src="images/builder-01.png" class="pull-right" />

<p>Phreeze Builder é um utilitário que analisa o banco de dados e automaticamente gera uma aplicação básica pronta para uso e/ou customização.
O Phreeze Builder utiliza templates para gerar diferentes tipos de aplicações.</p>

<h4 id="schema">Recomendações para o Schema de Banco de Dados</h4>

<p>Phreeze pode utilizar praticamente qualquer schema existente. No entanto, se você estiver partindo do zero, pode otimizar seu schema para o Phreeze seguindo as convenções abaixo:</p>

<ul>
<li><span class="label label-important">Obrigatório</span> Todas as tabelas devem ter uma chave primária composta de uma coluna. Tabelas sem chave primária, ou cuja chave é composta por mais de uma coluna, não são suportadas.</li>
<li><span class="label label-warning">Recomendado</span> Todos os relacionamentos entre as tableas devem ser explicitamente definidos com uma constraint de chave estrangeira.  O Phreeze Builder analisa as chaves estrangeiras para determinar como as tabelas estão relacionadas.</li>
<li><span class="label label-warning">Recomendado</span> Todas as tabelas e colunas devem estar em caixa-baixa e usar underscore como delimitador. Por exemplo, `ordem_de_compra` e não `Ordem de Compra`, `ordemDeCompra`, etc.  Isto afetará a capacidade do Builder de criar nomes de classes limpos para o seu modelo.</li>
<li><span class="label label-warning">Recomendado</span> Use nomes no singular para as suas tabelas.  Por exemplo, nomeie a tabela como `cliente`, e não `clientes`.  
Usando nomes nos singular você ajuda o Builder a estimar de maneira apropriada os nomes classes do seu modelo no singular e no plural.</li>
<li><span class="label label-success">Opcional</span> Dê a cada coluna da tabela um prefixo único.  Por exemplo, as colunas da tabela cliente podem ser c_id, c_nome, c_idade, etc.  Isso facilita a construção de reporter classes, mas não é obrigatório.</li>
</ul>

<h4 id="builder">Usando o Phreeze Builder</h4>

<img src="images/builder-02.png" class="pull-right" />

<p>Phreeze Builder está incluído na biblioteca Phreeze e está localizado no subdiretório  /builder/ . Assumindo que você salvou a pasta /phreeze/ na raiz do seu localhost, você pode abrir o Phreeze Builder na seguinte URL:</p>

<p><code>http://localhost/phreeze/builder/</code></p>

<p style="clear: left;">A primeira tela do builder te pedir os dados de conexão do seu banco de dados  MySQL. Fornecidas essas configurações, fará duas coisas: primeiro o builder app conectará e analisará o seu schema e segundo, usará estas informações para criar o arquivo _machine_config.php na aplicação gerada.  Desta maneira, sua aplicação estará pronta rodar, sem a necessidade de configurar nenhum arquivo.</p>

<p>Uma vez que você forneceu as informações do seu banco de dados, você verá o resultado da análise do schema.  Você deve revisar todos os nomes no singular e plural gerados pelo builder e fazer qualquer ajuste necessário.  O builder tenta estimas os nomes apropriados para as suas classes modelo, no entanto, podem ocorrer algumas incorreções devido às nuances da língua.</p>

<p>Abaixo dos nomes das tabelas há um drop-down para selecionar a aplicação que você deseja gerar.  Você pode escolher um de vários template engines de acordo com as suas preferências pessoais, gerar um unit test harness ou gerar somente os raquivos model do seu schema.</p>

<p>Existem opções adicionais abaixo, onde você pode especificar o nome de sua aplicação, a raiz da URL onde sua aplicação será instalada e o caminho relativo para o diretório /phreeze/libs/ .</p>

<p>Quando tiver concluído suas configurações, clique no botão "Generate Application" e você será avisado para fazer o download de um arquivo .zip.  Este arquivo zipado contém todos os arquivos necessários para a sua aplicação.</p>

<h4 id="app">Rodando a Aplicação Gerada</h4>

<p>Dependendo do caminho e da raiz da URL especificadam você precisará extrair o seu arquivo .zip no local apropriado em seu servidor.  Se você selecionou Smarty como seu template engine então precisará configurar as permissões do diretório /templates_c/ para permitir a escrita.  Se você selecionou o Laravel/Blade template engine então você pode precisar alterar as permissões em /storage/views/ para permitir escrita.</p>

<p>Quando a aplicação estiver instalada em seu servidor e as permissões opcionais estiverem atualizadas você poderá rodar sua aplicação.  Abra seu navegador no endereço apropriado como:</p>

<p><code>http://localhost/yourappname/</code></p>

<p>Se tudo seguiu de acordo com o planejado você verá a tela de boas vindas de sua aplicação!</p>

<h4 id="templates">Customizando os Templates do Phreeze Builder</h4>

<p>As aplicações geradas pelo Phreeze Builder são baseadas nos templates Smarty localizadas no diretório phreeze/builder/code/ . Cada aplicação consiste de duas partes: um 'config' e um ou mais templates.
O builder app busca por arquivos *.config no diretório /code/ .  Se você abrir um arquivo config existente verár uma seção [parameters] para o nome da aplicação e descrição, seguida de uma seção [files] para os arquivos de template. A seção files lista os códigos de template que serão usados e sua destinação na aplicação gerada.  Por exemplo:</p>

<pre>
[files]
phreeze.backbone/libs/Controller/Controller.php.tpl	libs/Controller/{$singular}Controller.php	0
phreeze.backbone/index.php.tpl	index.php	1
phreeze.backbone/bootstrap/css/bootstrap-combobox.css	bootstrap/css/bootstrap-combobox.css	2
</pre>

<p>A primeira coluna é o nome do código de arquivo de template.  A segunda colna é a "destinação" para o(s) arquivo(s) resultante(s) na aplicação gerada. O nome do arquivo de destino pode conter alguns placeholders como 
{$singular} ou {$plural} que significa que serão substituídos pelos nomes das tabelas no singular ou plural.
Os nomes podem ser forçados em caixa-baixa usando o modificador Smarty {$singular|lower}.</p>

<p>A terceira coluna será 0, 1 ou 2 se refere ao método para executar o parsing do template:</p>

<ol start="0">
<li>Um template será parsed e gerado para cada tabela do banco de dados</li>
<li>Um template será parsed e gerado apneas uma vez por aplicação (por exemplo index.php)</li>
<li>Um template será copiado, como está, sem parsing (images, script libraries, etc)</li>
</ol>

<p>A melhor maneira para começar csua prórpia aplicação é olhar o código de aplicações já existentes.  Dentro de cada template você pode fazer loop nas tabelas e colunas, bem como acessar meta-informações a respeito do schema do banco de dados.</p>

<?php include_once '_footer-ptbr.php' ?>
