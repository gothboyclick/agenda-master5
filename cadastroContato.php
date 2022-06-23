<?php
    session_start();

    $verificaUsuarioLogado = $_SESSION['verificaUsuarioLogado'];

    if (!$verificaUsuarioLogado){
        header("Location: index.php?codMsg=003");
    } else {
        include "conectaBanco.php";
        include "common/formataData.php";
        
        $codigoUsuarioLogado = $_SESSION['codigoUsuarioLogado'];
        $nomeUsuarioLogado = $_SESSION['nomeUsuarioLogado'];
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de contatos</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/messages_pt_BR.js"></script>
    <script src="js/dateITA.js"></script>
    <script src="js/jquery.mask.js"></script>

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

        .custom-file-input~.custom-file-label::after {
            content: "Selecionar";
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/icone.svg" width="30" height="30" alt="Agenda de contatos">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
                <span class="navbar-toggle-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                            id="menuCadastros" aria-haspopup="true" aria-expanded="false">
                            <i class="bi-card-list"></i> Cadastros</a>
                        <div class="dropdown-menu" aria-labelledby="menuCadastros">
                            <a class="dropdown-item" href="cadastroContato.php">
                                <i class="bi-person-fill"></i> Novo contato</a>
                            <a class="dropdown-item" href="listaContatos.php">
                                <i class="bi-list-ul"></i> Lista de contatos</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" id="menuConta"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bi-gear-fill"></i> Minha conta</a>
                        <div class="dropdown-menu" aria-labelledby="menuConta">
                            <a class="dropdown-item" href="alterarDados.php">
                                <i class="bi-pencil-square"></i> Alterar dados</a>
                            <a class="dropdown-item" href="logout.php">
                                <i class="bi-door-open-fill"></i> Sair</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#modalSobreAplicacao">
                            <i class="bi-info-circle"></i> Sobre</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" method="get" action="listaContatos.php">
                    <input class="form-control mr-sm-2" type="search" name="busca" placeholder="Pesquisar">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Pesquisar</button>
                </form>
                <span class="navbar-text ml-4">
                        Olá <b><?= $nomeUsuarioLogado ?></b>, seja bem-vindo(a)!
                    </span>
            </div>
        </div>
    </nav>
    <div class="h-100 row align-items-center pt-5">
        <div class="container">
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm-12">
                    <?php
                            $flagErro = False;
                            $flagSucesso = False;
                            $mostrarMensagem = False;

                            $dadosContato = array('codigoContato', 'nomeContato', 'nascimentoContato', 'sexoContato', 'mailContato', 'fotoContato', 'fotoAtualContato', 'telefone1Contato', 'telefone2Contato', 'telefone3Contato', 'telefone4Contato', 'logradouroContato', 'complementoContato', 'bairroContato', 'estadoContato', 'cidadeContato');

                            foreach($dadosContato as $campo){
                                $$campo = "";
                            }

                        if (isset($_POST['codigoContato'])) { //forme submetido (salvar)
                            $codigoContato = $_POST['codigoContato'];
                            $nomeContato = addslashes($_POST['nomeContato']);
                            $nascimentoContato = $_POST['nascimentoContato'];

                            if (isset($_POST['sexoContato'])) {
                                $sexoContato = $_POST['sexoContato'];
                            } else {
                                $sexoContato = "";
                            }

                            $mailContato = $_POST['mailContato'];
                            $fotoContato = $_FILES['fotoContato'];
                            $fotoAtualContato = $_POST['fotoAtualContato'];
                            $telefone1Contato = $_POST['telefone1Contato'];
                            $telefone2Contato = $_POST['telefone2Contato'];
                            $telefone3Contato = $_POST['telefone3Contato'];
                            $telefone4Contato = $_POST['telefone4Contato'];
                            $logradouroContato = addslashes( $_POST['logradouroContato']);
                            $complementoContato = addslashes( $_POST['complementoContato']);
                            $bairroContato = addslashes( $_POST['bairroContato']);
                            $estadoContato = $_POST['estadoContato'];
                            $cidadeContato  = $_POST['cidadeContato'];


                            $telefonesContato = array($telefone1Contato, $telefone2Contato, $telefone3Contato, $telefone4Contato);

                            $telefonesFiltradosContato = array_filter($telefonesContato);
                            $telefonesValidadosContato = preg_grep('/^\(\d{2}\)\s\d{4,5}\-\d{4}$/', $telefonesContato);

                            if ($telefonesFiltradosContato === $telefonesValidadosContato){
                                $erroTelefones = False;
                            } else {
                                $erroTelefones = True;
                            }

                            if (empty($nomeContato) || empty($sexoContato) || empty($mailContato) || empty($telefone1Contato) || 
                                empty($logradouroContato) || empty($complementoContato) || empty($bairroContato) || empty($cidadeContato) || 
                                empty($estadoContato)) {
                            
                                $flagErro = True;
                                $mensagemAcao = "Preencha todos os campos obrigatórios (*). ";

                            } else if (strlen($nomeContato) < 5) {
                                $flagErro = True;
                                $mensagemAcao = "Informe a quantidade mínima de caracteres para cada campo: Nome (5).";
                            } else if (!empty($nascimentoContato) && !preg_match('/^(0?[1-9]|[1,2][0-9]|[3[0,1])[\/](0?[1-9]|1[0,1,2])[\/]\d{4}$/', $nascimentoContato)) { //validação do nascimento
                                $flagErro = True;
                                $mensagemAcao = "A data de nascimento do contato deve ser no formato DD/MM/AAAA.";                               
                            } else if (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/", $mailContato)){
                                $flagErro = True;
                                $mensagemAcao = "Verifique o e-mail informado.";

                            } else if ($fotoContato['error'] != 4) {
                                if (!in_array($fotoContato['type'], array('image/jpg', 'image/jpeg', 'image/png', )) || $fotoContato['size'] > 2000000) {

                                    $flagErro = True;
                                    $mensagemAcao = "A foto do contato deve ser nos formatos JPG, JPEG ou PNG e ter no máximo 2MB.";

                                } else {
                                   list($larguraFoto, $alturaFoto) = getimagesize($fotoContato['tmp_name']);

                                   if ($larguraFoto > 500 || $alturaFoto > 200) {
                                    $flagErro = True;
                                    $mensagemAcao = "As dimensões da foto devem ser no máximo 500x200 pixels.";
                                   }
                                }

                            } else if ($erroTelefones) {
                                $flagErro = True;
                                $mensagemAcao = "Os campos de telefone devem ser no formato (xx) xxxxx-xxxx. ";
                            
                            }


                            if (!$flagErro) {
                                if (empty($codigoContato)) { // inclusão de contato
                                    $sqlContato = "INSERT INTO contatos (codigoUsuario, nomeContato, nascimentoContato, sexoContato, mailContato, fotoContato, telefone1Contato, telefone2Contato, telefone3Contato, telefone4Contato, logradouroContato, complementoContato, bairroContato, cidadeContato, estadoContato) VALUES (:codigoUsuario, :nomeContato, :nascimentoContato, :sexoContato, :mailContato, :fotoContato, :telefone1Contato, :telefone2Contato, :telefone3Contato, :telefone4Contato, :logradouroContato, :complementoContato, :bairroContato, :cidadeContato, :estadoContato)";

                                    $sqlContatoST = $conexao->prepare($sqlContato);

                                    $sqlContatoST->bindValue(':codigoUsuario', $codigoUsuarioLogado);
                                    $sqlContatoST->bindValue(':nomeContato', $nomeContato);

                                    $nascimentoContato = formataData($nascimentoContato);
                                    $sqlContatoST->bindValue(':nascimentoContato', $nascimentoContato);


                                    $sqlContatoST->bindValue(':sexoContato', $sexoContato);
                                    $sqlContatoST->bindValue(':mailContato', $mailContato);
                                    $sqlContatoST->bindValue(':telefone1Contato', $telefone1Contato);
                                    $sqlContatoST->bindValue(':telefone2Contato', $telefone2Contato);
                                    $sqlContatoST->bindValue(':telefone3Contato', $telefone3Contato);
                                    $sqlContatoST->bindValue(':telefone4Contato', $telefone4Contato);
                                    $sqlContatoST->bindValue(':logradouroContato', $logradouroContato);
                                    $sqlContatoST->bindValue(':complementoContato', $complementoContato);
                                    $sqlContatoST->bindValue(':bairroContato', $bairroContato);
                                    $sqlContatoST->bindValue(':cidadeContato', $cidadeContato);
                                    $sqlContatoST->bindValue('estadoContato:', $estadoContato);

                                    if ($fotoContato['error'] == 0) {
                                        $extensaoFoto = pathinfo($fotoContato['name'], PATHINFO_EXTENSION);
                                        $nomeFoto = "fotos/" . strtotime(date("Y-m-d H:i:s")) . $codigoUsuarioLogado . '.' . $extensaoFoto;

                                        if (copy($fotoContato['tmp_name'], $nomeFoto)) {
                                            $fotoEnviada = True;
                                        } else {
                                            $fotoEnviada = False;
                                        }

                                        $sqlContatoST->bindValue(':fotoContato', $nomeFoto);
                                    } else {
                                        $sqlContatoST->bindValue(':fotoContato', '');
                                        $fotoEnviada = False;
                                    }
                                    if ($sqlContatoST->execute()) {
                                        $flagSucesso = True;
                                        $mensagemAcao = "Novo contato cadastrado com sucesso.";
                                    } else {
                                        $flagErro = True;
                                        $mensagemAcao = "Erro ao cadastrar o novo contato. Código do erro: $sqlContatoST->errorCode( ).";

                                        $nascimentoContato = formataData($nascimentoContato);

                                        if ($fotoEnviada) {
                                            unlink($nomeFoto);
                                        }
                                    }
                                    
                                    
                                    }

                                } else { //edição de contato existente

                            }

                        } else { //carregar dados
                            if(isset($_GET['codigoContato'])) { // abrir contato existente

                            } 
                        }

                        if ($flagErro) {
                            $classeMensagem = "alert-danger";
                            $mostrarMensagem = True;
                        }   else if ($flagSucesso) {
                            $classeMensagem = "alert-success";
                            $mostrarMensagem = True;
                        }

                        if ($mostrarMensagem) {
                            echo "<div class=\"alert $classeMensagem alert-dismissible fade show my-5\" role=\"alert\">
                                    $mensagemAcao
                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                </div>";
                        }
                    ?>
                    <div class="card border-primary my-5">
                        <div class="card-header bg-primary text-white">
                            <h5>Cadastro de contato</h5>
                        </div>
                        <div class="card-body">
                            <form id="cadastroContato" method="post" enctype="multipart/form-data" action="cadastroContato.php">
                                <input type ="hidden" name="codigoContato" value="">
                                <input type ="hidden" name="fotoAtualContato" value="">
                                <h5 class="text-primary">Dados pessoais</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nomeContato">Nome*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-person-circle"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="text" name="nomeContato"
                                                            id="nomeContato" placeholder="Digite o nome" value="<?= $nomeContato ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nascimentoContato">Data de nascimento</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-calendar-date"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="text" name="nascimentoContato"
                                                            id="nascimentoContato" placeholder="DD/MM/AAAA" value="<?= $nascimentoContato ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="sexoContato">Sexo*</label>
                                                    <div class="input-group">
                                                        <div class="form-check form-check-inline">
                                                            <?php
                                                                if ($sexoContato == 'M'){
                                                                    $checkedMasculino = 'checked';
                                                                    $checkedFeminino = '';

                                                                } else if ($sexoContato == 'F'){
                                                                    $checkedMasculino = '';
                                                                    $checkedFeminino = 'checked';
                                                                } else {
                                                                    $checkedMasculino = '';
                                                                    $checkedFeminino = '';
                                                                }
                                                            ?>

                                                            <input class="form-check-input" type="radio"
                                                                name="sexoContato" id="sexoMasculino" value="M" <?= $checkedMasculino ?>>
                                                            <label class="form-check-label"
                                                                for="sexoMasculino">Masculino</label>
                                                            &nbsp;
                                                            <input class="form-check-input" type="radio"
                                                                name="sexoContato" id="sexoFeminino" value="F" <?= $checkedFeminino ?>>
                                                            <label class="form-check-label"
                                                                for="sexoFeminino">Feminino</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="mailContato">E-mail*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-at"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="email" name="mailContato"
                                                            id="mailContato" placeholder="Digite o e-mail" value="<?= $mailContato ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fotoContato">Foto</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-file-earmark-person"></i>
                                                            </div>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input class="custom-file-input" type="file"
                                                                name="fotoContato" id="fotoContato">
                                                            <label class="custom-file-label" for="fotoContato">
                                                                Escolha a foto...
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="text-primary">Telefones</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone1Contato">Telefone*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control mascara-telefone" type="text"
                                                            name="telefone1Contato" id="telefone1Contato"
                                                            placeholder="(xx) xxxxx-xxxx" value="<?= $telefone1Contato ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone2Contato">Telefone</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control mascara-telefone" type="text"
                                                            name="telefone2Contato" id="telefone2Contato"
                                                            placeholder="(xx) xxxxx-xxxx" value="<?= $telefone2Contato ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone3Contato">Telefone</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control mascara-telefone" type="text"
                                                            name="telefone3Contato" id="telefone3Contato"
                                                            placeholder="(xx) xxxxx-xxxx" value="<?= $telefone3Contato ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone4Contato">Telefone</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control mascara-telefone" type="text"
                                                            name="telefone4Contato" id="telefone4Contato"
                                                            placeholder="(xx) xxxxx-xxxx" value="<?= $telefone4Contato ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="text-primary">Endereços</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="logradouroContato">Logradouro*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-cone"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="text" name="logradouroContato"
                                                            id="logradouroContato"
                                                            placeholder="Rua, avenida, travessa e outros" value= "<?= $logradouroContato ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="complementoContato">Complemento*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-pin"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="text"
                                                            name="complementoContato" id="complementoContato"
                                                            placeholder="Número, quadra, lote e outros" value= "<?= $complementoContato ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="estadoContato">Estado*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-grid"></i>
                                                            </div>
                                                        </div>
                                                        <select class="form-control" name="estadoContato"
                                                            id="estadoContato" required>
                                                            <option value="">Escolha o estado</option>
                                                            <?php
                                                                $sqlEstados = "SELECT codigoEstado, nomeEstado FROM estados";
                                                                $resultadosEstados = $conexao->query($sqlEstados)->fetchAll();

                                                                foreach($resultadosEstados as list($codigoEstado, $nomeEstado)){
                                                                    if ($estadoContato == $codigoEstado) {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = '';
                                                                    }


                                                                    echo "<option value=\"$codigoEstado\" $selected>$nomeEstado</option>\n";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="bairroContato">Bairro*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-house"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control" type="text" name="bairroContato"
                                                            id="bairroContato" placeholder="Digite o Bairro" value= "<?= $bairroContato ?>"required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="cidadeContato">Cidade*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-globe"></i>
                                                            </div>
                                                        </div>
                                                        <select class="form-control" name="cidadeContato"
                                                            id="cidadeContato" required>
                                                            <option value="">Escolha a cidade</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm text-right">
                                        <button type="submit" class="btn btn-outline-primary">Salvar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSobreAplicacao" tabindex="-1" role="dialog" aria-labelledby="sobreAplicacao"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sobreAplicacao">Sobre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="img/logo.jpg" alt="">
                    <hr>
                    <p>Agenda de contatos</p>
                    <p>Versão 1.0</p>
                    <p>Todos os direitos reservados &copy; 2022</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
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
            $("#cadastroContato").validate({
                rules: {
                    nomeContato: {
                        minlength: 5
                    },
                    nascimentoContato: {
                        dateITA: true
                    },
                    sexoContato: {
                        required: true
                    }
                }
            });
            $("#nascimentoContato").mask("00/00/0000");

            var SPMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
                spOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.mascara-telefone').mask(SPMaskBehavior, spOptions);

            $("#estadoContato").change(function () {
                $("#cidadeContato").html('<option> Carregando...</option>');
                $("#cidadeContato").load('listaCidades.php?codigoEstado=' + $("#estadoContato").val());
            });

            <?php
                if (!empty($estadoContato) && !empty($cidadeContato)) {
                    echo "$(\"#cidadeContato\").html('<option> Carregando...</option>');
                    $(\"#cidadeContato\").load('listaCidades.php?codigoEstado= " .  $estadoContato . "&codigoCidade" . $cidadeContato. "');";
                }
            ?>
        });
    </script>

</html>