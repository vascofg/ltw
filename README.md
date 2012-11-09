ltw-proj1
=========

***O projecto pode ser visto [aqui][1].***

***O PHPLiteAdmin (painel de administra��o da base de dados) pode ser acedido por [aqui][2], com a palavra passe*** *proj1.*

**Datas de Entrega:**

 + 18-11-2012 (Interm�dia)
 + 02-12-2012 (Final)

**Para a primeira entrega � necess�rio fazer:**

 + Especifica��o e desenvolvimento de uma base de dados que permita armazenar not�cias e tr�s n�veis de permiss�es de utilizadores (leitor, apenas com permiss�es de read; editor com permiss�es de read e write; administrador com capacidade para (des)promover utilizadores j� registados a editores e gerir as liga��es com outros servidores)
 + Cria��o de um servi�o web em PHP que permita fazer pesquisa de not�cias na base de dados usando um protocolo conhecido:
 + /api/news.php
     + Tipo: GET
     + Par�metros:
         + start_date: Data de in�cio do intervalo a partir do qual as not�cias dever�o ser obtidas, no formato ISO8601 ("YYYY-MMDDTHH:MM:SS").
         + end_date: Data de final do intervalo a partir do qual as not�cias dever�o ser obtidas, no formato ISO8601 ("YYYY-MMDDTHH:MM:SS").
         + tags: Lista de palavras (separada por espa�os) que dever�o ser usadas para filtrar as not�cias a obter
     + Retorno:
         + Objecto JSON com:
             + um array chamado �data� que cont�m as not�cias que obedecem aos crit�rios especificados
                 + Cada elemento do array �data� dever� conter pelo
menos os seguintes campos:
                     + id: Identificador da not�cia
                     + title: T�tulo da not�cia
                     + date: Data da not�cia, no formato ISO8601 ("YYYY-MM-DDTHH:MM:SS")
                     + text: Corpo da not�cia
                     + posted_by: Nome do editor que escreveu a not�cia
                     + url: URL da not�cia/fonte
                     + tags: Array de tags associadas � not�cia
                 + um campo chamado �server_name� correspondente ao nome/n�mero do grupo de trabalho
                 + um campo �result� com o resultado da opera��o (�success� ou �error�).
                 + Caso a opera��o tenha falhado, o objecto deve conter ainda um campo com o nome �reason� que indica a raz�o porque falhou e um campo �code� que indique o c�digo do erro.
         + Exemplos:
             + {result: �success�, server_name: �Grupo01�, data: [{id: 1, title: �LTW was considered awesome by everyone�, date: �2012-09 10T13:14:00�, text: �Lorem ipsum�, posted_by: �Journalist name�, url: �http://paginas.fe.up.pt/~ltw/ news2012/ltw-is-awesome�, tags: [�ltw�, �awesome�, �2012�]}]}
             + {result: �error�, reason: �Start date can�t be greater than end date�, code: 2}
 + Visualiza��o das not�cias e interface da p�gina em HTML e CSS
 + Listagem das �ltimas not�cias inseridas
 + Cria��o de um formul�rio para inser��o local de not�cias

**Funcionalidades por tipo de utilizador:**

 + **Visitante**
  + Ver lista de not�cias recentes
  + Pesquisar not�cias (ajax)
  + Ver pormenores de uma not�cia incluindo coment�rios
  + Ver o perfil de um utilizador
  + Registar-se no site
  + Fazer Login
 + **Utilizador** (tudo o que faz um visitante mais...)
  + Introduzir coment�rios (ajax)
  + Editar/apagar os pr�prios coment�rios (ajax)
  + Editar o perfil pessoal
  + Marcar/desmarcar uma not�cia como favorita (ajax)
  + Listar as suas not�cias favoritas
  + Fazer logout
 + **Editor** (tudo o que faz um utilizador mais...)
  + Introduzir uma nova not�cia
  + Editar/apagar as pr�prias not�cias
  + Apagar coment�rios na pr�pria not�cia (ajax)
 + **Administrador** (tudo o que faz um editor mais...)
  + Promover/despromover utilizadores a editores
  + Editar/apagar qualquer not�cia
  + Editar/apagar utilizadores
  + Gerir a lista de servidores remotos
  + Despoletar uma pesquisa em servidores remotos
  + Seleccionar e adicionar not�cias a importar (ajax)


  [1]: http://paginas.fe.up.pt/~ei10054/ltw/proj1/
  [2]: http://paginas.fe.up.pt/~ei10054/ltw/proj1/db/