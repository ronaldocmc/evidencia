# Sistema Evidência

O projeto da Companhia Prudentina de Desenvolvimento - [PRUDENCO](http://www.prudenco.com.br/) em parceria com a FCT Unesp de Presidente Prudente e alunos do curso de Ciência da Computação, aplica um pouco do conceito de Cidades Inteligentes para sistematizar e automatizar o processos de coletas de diversos tipos de lixo na cidade. Por meio da administração, fiscalização e operação sustentada pelo Sistema Evidência a Companhia objetiva melhorar a qualidade do seu serviço oferecido.

Em resumo, o Sistema Evidência deve permitir o gerenciamento dos serviços de coleta, oferecendo funcionalidades como mapeamento de casos, gestão de ordens de serviço, designação de responsabilidades, fiscalização da execução por meio de fotos, visualização de dados departamentais e operacionais, entre outras funcionalidades.

O Sistema Evidência possuirá dois módulos: Web e Mobile, de modo que, usuários administrativos gerenciem os processos por meio de computadores e notebooks, enquanto usuários operacionais atuem com o módulo mobile nas ruas da cidade de Presidente Prudente. O grande diferencial do sistema será a geolocalização e a captura de imagens para comprovar o início e finalização dos serviços, garantindo a integridade da execução e tornando mais prático o acompanhamento por meio dos fiscais, além de ser um bom indíce de qualidade do serviço prestado.

Futuramente, a Companhia em parceria com a Universidade, utilizará os dados levantados e contabilizados para o sistema, afins de estudos estatísticos, propondo tornar o sistema mais escalável afim do crescimento e expansão do sistema na área de Cidades Inteligentes.

## Pré-requisitos

Estes componentes são necessários para a instalação do ambiente de produção e desenvolvimento, certifique-se que os mesmos estão corretamente instalados.

- Apache 2
- PHP 7.2+
- Composer

### Instalação de pré-requisitos no Windows

Para a instalação do Apache e PHP, utilize o instalador do XAMPP, disponível [aqui](https://www.apachefriends.org/pt_br/index.html). As dúvidas comuns sobre o processo de instalação podem ser encontradas no [FAQ](https://www.apachefriends.org/faq_windows.html)

As instruções de instalação do Composer para windows podem ser econtradas [aqui](https://getcomposer.org/doc/00-intro.md#installation-windows).

### Instalação de pré-requisitos no Linux

Para o processo de instalação do Apache e PHP consulte esse [tutorial da DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04#step-1-%E2%80%94-installing-apache-and-updating-the-firewall).

Para o processo de instalação do Composer, recomendamos este outro [artigo da DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-18-04).

Lembramos que essas instruções são para o sistema `Ubuntu 18.04 LTS`, porém instruções similares podem ser encontradas em quaisquer distribuições Linux

## Passos iniciais

Siga os passos abaixo para obter uma versão de desenvolvimento do sistema rodando localmente:

### Windows

As instruções abaixo supõe que o usuário possui alguma proeficiência em utilizar o prompt de comando do windows ou similares

1. Certifique-se de ir para a pasta `htdocs` do xampp
```
> cd C:\xampp\htdocs\
```

2. Realize a clonagem do projeto

```
> git clone https://github.com/GustGimenez/evidencia.git
```

3. Entre na pasta do projeto

```bash
> cd evidencia/
```

4. Instale as dependencias utilizando o Composer:

```bash
> composer install
```

5. Após a finalização, renomeie a pasta do `evidencia` para `evidencia_v2`:

```
> cd ..
> move evidencia evidencia_v2
```

6. Utilizando o seu navegador, vá para o endereço http://localhost/evidencia_v2 e realize o login com as credenciais providas.

### Linux

Essas instruções assumem que o usuário tenha uma familiaridade mínima com o terminal, caso não se sinta habilitado, solicite a ajuda de alguém com o conhecimento necessário.

0. Vá até a pasta `html` do apache e certifique-se que possui privilégios de escrita:

```bash
$ cd /var/www/html/
$ stat -c %a . # checa as permissoes da pasta e as retorna em formato octal
```

Se o retorno dos comandos acima for `2775`, não é necessária nenhuma alteração.

> Se a sua permissão for diferente, execute os seguintes comandos para solucionar o problema:

```bash
$ sudo chown -R $USER:$USER /var/www/html/
```

1. Verifique que o PHP e o Composer estão corretamente instalados:

```bash
$ php --version
PHP 7.2.15-0ubuntu0.18.04.2 (cli) ... # resto da saída

$ composer --version
Composer version 1.8.4
```

> Caso algum desses comandos falhar, ou algum dos dois softwares não e

2. Realize a clonagem(download) do projeto:

```bash
$ git clone https://github.com/GustGimenez/evidencia.git
```

3. Entre na pasta do projeto

```bash
$ cd evidencia/
```

4. Instale as dependencias utilizando o Composer:

```bash
$ composer install
```

5. Após a finalização, renomeie a pasta do `evidencia` para `evidencia_v2`:

```bash
$ cd ..
$ mv evidencia evidencia_v2
```

6. Utilizando o seu navegador, vá para o endereço http://localhost/evidencia_v2 e realize o login com as credenciais providas.

## Executando os testes

> WIP

## Instruções para o _deploy_

> WIP

## Construído com

- [PHP](https://www.php.net/) - Linguagem de propósito geral especializada para desenvolvimento web.
- [PHP Composer](https://getcomposer.org/) - Gerenciador de dependências para PHP.

## Contribuição

Nesse projeto foi adotado o [Gitflow](https://nvie.com/posts/a-successful-git-branching-model/), então as _Pull-Requests_ e contriduições deverão ser feitas conforme as guidelines desse modelo de desenvolvimento. Convidamos os que desejam contribuir a ler o nosso [Código de Conduta](CODE_OF_CONDUCT.md)

## Versionamento

Nós utilizamos [SemVer](https://semver.org/lang/pt-BR/) para versionamento. Para as versões disponíveis, [veja a tags de release do repositório](https://github.com/GustGimenez/evidencia/tags).

## Desenvolvedores

- [Matheus Palmeira Gonçalves dos Santos](https://www.linkedin.com/in/matheuspalmeir/) - [@matheuspalmeir](https://github.com/matheuspalmeir)

- [Gustavo Gimenez de Deus](https://www.linkedin.com/in/gustavo-gimenez-662424163/) - [@GustGimenez](https://github.com/GustGimenez)

- [Pietro Barcarollo Schiavinato](https://www.linkedin.com/in/pietro-barcarollo-schiavinato-b52b3b136/) - [@pietrobs](https://github.com/pietrobs)

- [Darlan Murilo Nakamura de Araújo](https://www.facebook.com/darlannakamura) - [@darlannakamura](https://github.com/deadpyxel)

- [Robson Cruz](https://www.linkedin.com/in/robson-cruz-922931157/) - [@deadpyxel](https://github.com/deadpyxel)

## Professor Orientador

- [Ronaldo Celso Messias Correia](http://lattes.cnpq.br/2420360066008780)

## Agradecimentos

- [Colorlib](https://colorlib.com/) - Template para o frontend
