ltw-proj1
=========
Vasco Gonçalves
-----------------
Maria João Araújo
-----------------

***O projecto pode ser visto [aqui][1].***

***O PHPLiteAdmin (painel de administração da base de dados) pode ser acedido por [aqui][2], com a palavra passe*** *proj1.*

**(ATENÇÃO: chaves estrangeiras só funcionam no páginas.fe.up.pt, o gnomo usa uma versão antiga de SQLite que não suporta foreign keys)**

**Datas de Entrega:**

 + 18-11-2012 (Intermédia)
 + 02-12-2012 (Final)

**Para a primeira entrega é necessário fazer:**

 + Especificação e desenvolvimento de uma base de dados que permita armazenar notícias e três níveis de permissões de utilizadores (leitor, apenas com permissões de read; editor com permissões de read e write; administrador com capacidade para (des)promover utilizadores já registados a editores e gerir as ligações com outros servidores)
 + Criação de um serviço web em PHP que permita fazer pesquisa de notícias na base de dados usando um protocolo conhecido:
 + /api/news.php
     + Tipo: GET
     + Parâmetros:
         + start_date: Data de início do intervalo a partir do qual as notícias deverão ser obtidas, no formato ISO8601 ("YYYY-MMDDTHH:MM:SS").
         + end_date: Data de final do intervalo a partir do qual as notícias deverão ser obtidas, no formato ISO8601 ("YYYY-MMDDTHH:MM:SS").
         + tags: Lista de palavras (separada por espaços) que deverão ser usadas para filtrar as notícias a obter
     + Retorno:
         + Objecto JSON com:
             + um array chamado “data” que contém as notícias que obedecem aos critérios especificados
                 + Cada elemento do array “data” deverá conter pelo
menos os seguintes campos:
                     + id: Identificador da notícia
                     + title: Título da notícia
                     + date: Data da notícia, no formato ISO8601 ("YYYY-MM-DDTHH:MM:SS")
                     + text: Corpo da notícia
                     + posted_by: Nome do editor que escreveu a notícia
                     + url: URL da notícia/fonte
                     + tags: Array de tags associadas à notícia
                 + um campo chamado “server_name” correspondente ao nome/número do grupo de trabalho
                 + um campo “result” com o resultado da operação (“success” ou “error”).
                 + Caso a operação tenha falhado, o objecto deve conter ainda um campo com o nome “reason” que indica a razão porque falhou e um campo “code” que indique o código do erro.
         + Exemplos:
             + {result: “success”, server_name: “Grupo01”, data: [{id: 1, title: “LTW was considered awesome by everyone”, date: “2012-09 10T13:14:00”, text: “Lorem ipsum”, posted_by: “Journalist name”, url: “http://paginas.fe.up.pt/~ltw/ news2012/ltw-is-awesome”, tags: [“ltw”, “awesome”, “2012”]}]}
             + {result: “error”, reason: “Start date can’t be greater than end date”, code: 2}
 + Visualização das notícias e interface da página em HTML e CSS
 + Listagem das últimas notícias inseridas
 + Criação de um formulário para inserção local de notícias

**Funcionalidades por tipo de utilizador:**

 + **Visitante**
  + Ver lista de notícias recentes
  + Pesquisar notícias (ajax)
  + Ver pormenores de uma notícia incluindo comentários
  + Ver o perfil de um utilizador
  + Registar-se no site
  + Fazer Login
 + **Utilizador** (tudo o que faz um visitante mais...)
  + Introduzir comentários (ajax)
  + Editar/apagar os próprios comentários (ajax)
  + Editar o perfil pessoal
  + Marcar/desmarcar uma notícia como favorita (ajax)
  + Listar as suas notícias favoritas
  + Fazer logout
 + **Editor** (tudo o que faz um utilizador mais...)
  + Introduzir uma nova notícia
  + Editar/apagar as próprias notícias
  + Apagar comentários na própria notícia (ajax)
 + **Administrador** (tudo o que faz um editor mais...)
  + Promover/despromover utilizadores a editores
  + Editar/apagar qualquer notícia
  + Editar/apagar utilizadores
  + Gerir a lista de servidores remotos
  + Despoletar uma pesquisa em servidores remotos
  + Seleccionar e adicionar notícias a importar (ajax)


  [1]: http://paginas.fe.up.pt/~ei10054/ltw/proj1/
  [2]: http://paginas.fe.up.pt/~ei10054/ltw/proj1/db/