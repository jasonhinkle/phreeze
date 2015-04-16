<?php include_once '_header-ptbr.php' ?>

<h3 id="top">Introdução ao Phreeze</h3>

<h4 id="what">O que é Phreeze?</h4>

<p>Em termos simples Phreeze é um framework para construir aplicações em PHP. Um framework é, basicamente, um kit de ferramentas de helper classes em conjunto com uma  estrutura de aplicação consistente.</p>

<p>Phreeze é composto de três componentes. Uma típica aplicação Phreeze usará todas as partes do framework, no entanto elas podem ser usadas de forma independente. Os três componentes são:</p>

<ul>
	<li>Um <a href="#mvc">MVC</a> (Model-view-controller) Framework</li>
	<li>UM <a href="#orm">ORM</a> (Object-Relational Mapping) via classes PHP</li>
	<li><a href="#builder">Phreeze Builder</a> - um recurso para a geração de aplicações Phreeze</li>
</ul>

<p>A maneira recomendável de começar com o Phreeze é usar o Phreeze Builder para gerar uma aplicação. A aplicação que é gerada é basicamente um editor de banco de dados que lhe permite ver, pesquisar e modificar os dados no seu banco de dados MySQL. Esta aplicação é utilizável e pode ser suficiente para certos tipos de atividades administrativas internas. No entanto, para um site disponível publicamente você deve usar esta aplicação como um ponto de partida para o seu produto final.</p>

<h4 id="mvc">The MVC Framework</h4>

<p>
<img src="images/mvc.png" class="pull-right" />
O Phreeze MVC Framework implementa o padrão de design Model-View-Controller que é comumente utilizado em aplicações web. Você pode ler mais a respeito em <a href="http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller">MVC design pattern na Wikipedia</a>.
O MVC permite que você organize a aplicação em três partes, deta forma você obtém uma separação de conceitos - o que significa que cada parte do seu código atende a uma funcionalidade específica e pode ser operada de maneira independente das demais.</p>

<p>O "Model" representa seus dados. No caso do Phreeze o Model é uma abstração um-para-um de suas tabelas de banco de dados em classes PHP. Para uma interação básica com o banco de dados você não precisa escrever qualquer código SQL, ao invés disso, você pode fazer consultas usando objetos e métodos das suas classes PHP. A camada Model não tem qualquer preocupação como os dados serão exibidos, ou seja com o visual. Ela se preocupa apenas em manter um diálogo entre o banco de dados e a sua aplicação. Classes model não são obrigadas a estarem vinculadas a um banco de dados, elas podem ser usadas para abstrair qualquer informação. No entanto, na aplicação báscia gerada no Phreeze todos os Modelos estão atrelados a uma tabela do banco de dados.</p>

<p>A camada  "View" é composta por classes que configuram a parte visual da aplicação. 
No caso de aplicações web, classes view irão gerar coisas como  HTML ou JSON.A camada view não se preocupa de onde vem os dados, ela só espera que os dados sejam fornecidos na forma de Modelos. Para dar um exemplo prático, você pode ter múltiplos views para a mesma página em uma aplicação web. Um view pode ser otimizado para browsers e outro para dispositivos móveis. Sua aplicação terá o mesmo código back-end para ambos, mas os múltiplos views oferecerão diferentes visuais.</p>

<p>O "Controller" é uma classe que liga o Model e o  View.
O Controller recebe o input do usuário, lê e escreve dados se necessário usando o MOdel e então determina qual View fará o output. Controllers fazem a maior parte das decisões de uma aplicação.</p>

<p>Existem outros design patterns em uso em websites, mas o MVC é bastante popular e particularmente adequado para aplicações web. Estes três componentes trabalhando juntos provém um app flexível que pode crescer em complexidade, enquanto mantém o código organizado.</p>

<h4 id="orm">ORM</h4>

<p><img src="images/orm.png" class="pull-right" />
Phreeze ORM são classes que são utilizadas pela camada Model e fazem a comunicação entre suas classes e o banco de dados. ORM é uma sigla para "Object-Relational Mapping" que basicamente significa mapear um Objeto para um banco de dados relacional.  Veja mais <a href="http://en.wikipedia.org/wiki/Object-relational_mapping">informações sobre ORMs na Wikipedia</a>.</p>

<p>O que o ORM faz é permitir que você trabalhe com classes e objetos em sua aplicação, e alguma camada mais abaixo no código saiba como escrever os statements SQL necessários. Num mundo ideal você pode pensar nesta camada como uma caixa preta que você não precisa entender como funciona. Mas, eventualmente, quando você precisar fazer alguma consulta mais complexa, poderá mexer no ORM para obter o que deseja.</p>

<p>Mapear um banco de dados para classes é bastante fácil se você não tem relacionamentos entre as tabelas. Qualquer banco de dados não trivial conterá foreign keys and constraints. Mapear estes tipos de bancos de dados é mais complicado.  
Se você é um desenvolvedor que utiliza consultas complicadas em suas aplicações, pode ser desafiador usar um  ORM porque ele cria uma nova camada entre você e o seu  schema o que pode não oferecer o meio mais eficiente de acessar seus dados. Isto é geralmente conhecido como <a href="http://en.wikipedia.org/wiki/Object-relational_impedance_mismatch">object-relational impedence mismatch</a>.
Diferentes ORMs usam diferentes estratégias para lidar com este problema.  
Alguns uma linguagem query abstrata que vocÊ precisa aprender. Alguns simplesmente não trabalham os relacionamentos de maneira adequada e resultam em uma performance pobre. O  Phreeze busca lidar com o código básico, mas permite que você sobrescreva coisas com o seu próprio código SQL quando necessário.</p>

<h4 id="builder">Phreeze Builder</h4>

<p>O componente final é o Phreeze Builder. Ele não é, tecnicamente, parte do Framework, porque o builder não é utilizado pelas aplicações Phreeze.  O builder é um utilitário que analisa o banco de dados e gera automaticamente uma aplicação básica que está pronta para usar e/ou customizar.</p>

<p>O builder não é pre-requisito para usar Phreeze Framework.  Você pode escrever o seu próprio código a partir do zero utilizando as bibliotecas Phreeze.  No entanto, 
como qualquer aplicação PHP há muita configuração envolvida. Isto inclui configurar o path, adicionar bibliotecas, instancionar as classes do framework, etc. O builder facilita o trabalho gerando todo esse código tedioso, bem como os controllers, models e views genéricos de cada tabela.</p>

<p><a href="builder-ptbr.php">Mais sobre Phreeze Builder...</a></p>

<?php include_once '_footer-ptbr.php' ?>