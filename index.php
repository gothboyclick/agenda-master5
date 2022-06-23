<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de contatos</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/messages_pt_BR.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    
    <style>
        html {
            height: 100%;
        }

        body {
            background: url('img/dark-blue-background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
            overflow-x: hidden;
        }

    </style>
</head>

<body>
    <div class="h-100 row align-items-center">
        <div class="container">
            <?php
            if (isset($_GET['codMsg'])){
                $codMsg = $_GET['codMsg'];

                    switch ($codMsg) {
                        case '001':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Informe o usuário e a senha para acessar o sistema.";
                            break;
                        case '002':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Usuário ou senha incorretos.";
                            break;
                        case '003':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Usuário não logado no sistema.";
                            break;
                        case '004':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Informe o e-mail do usuário cadastrado no sistema.";
                            break;
                        case '005':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Usuário não cadastrado no sistema.";
                            break;
                        case '006':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Ocorreu um erro ao gerar a nova senha.";
                            break;
                        case '007':
                            $classeMensagem = "alert-danger";
                            $textoMensagem = "Ocorreu um erro ao enviar a nova senha para o e-mail.";
                            break;
                        case '008':
                            $classeMensagem = 'alert-success';
                            $textoMensagem = "Sua nova senha foi enviada para o e-mail cadastrado.";
                            break;
                        case '009':
                            $classeMensagem = 'alert-success';
                            $textoMensagem = "Sua sessão no sistema foi encerrada com sucesso.";
                    }

                if (!empty($textoMensagem)){
                    echo "<div class=\"alert $classeMensagem alert-dismissible fade show\" role=\"alert\">
                                    $textoMensagem
                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                </div>";
                }           
            }
            ?>
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <img style="width: 100%;" src="img/logo.jpg" alt="Agenda de contatos">
                        </div>
                        <div class="card-body">
                            <form id="login" method="post" action="login.php">
                                <div class="form-group">
                                    <label for="mailUsuario">E-mail</label>
                                    <input type="email" class="form-control" id="mailUsuario" name="mailUsuario"
                                        placeholder="Digite seu e-mail">
                                </div>
                                <div class="form-group">
                                    <label for="senhaUsuario">Senha</label>
                                    <input type="password" class="form-control" id="senhaUsuario" name="senhaUsuario"
                                        placeholder="Digite sua senha">
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <button id="entrarLogin" type="submit" class="btn btn-outline-primary btn-block btn-lg">
                                            Entrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm">
                                        <a class="btn btn-outline-success btn-block" href="novoUsuario.php">Não sou
                                            cadastrado</a>
                                    </div>
                                    <div class="col-sm">
                                        <button id="esqueciSenha" class="btn btn-outline-warning btn-block">Esqueci a
                                            senha
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>
    </div>
</body>
    <script>
        jQuery.validator.setDefaults({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $(document).ready(function () {
            $("#login").validate({
                rules: {
                    mailUsuario: {
                        required: true
                    },
                    senhaUsuario: {
                        required: true
                    }
                }
            });

            $('#esqueciSenha').click(function() {
                $('#senhaUsuario').rules("remove", "required");

                $('#login').attr("action", "recuperarSenha.php");
                $('#login').submit();
            });

            $('#entrarLogin').click(function() {
                $('#senhaUsuario').rules("add", "required");

                $('#login').attr("action", "login.php");
                $('#login').submit();
            });
        });
    </script>

</html>