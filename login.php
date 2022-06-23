<?php
    session_start();

    if (isset($_POST['mailUsuario']) && isset($_POST['senhaUsuario'])){
        include 'conectaBanco.php';

        $mailUsuario = $_POST['mailUsuario'];
        $senhaUsuario = $_POST['senhaUsuario'];
        $senhaUsuario = MD5($senhaUsuario);

        $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario=:mailUsuario AND senhaUsuario=:senhaUsuario LIMIT 1";

        $sqlUsuarioST = $conexao->prepare($sqlUsuario);

        $sqlUsuarioST->bindValue(':mailUsuario', $mailUsuario);
        $sqlUsuarioST->bindValue(':senhaUsuario', $senhaUsuario);

        $sqlUsuarioST->execute();

        $quantidadeUsuarios = $sqlUsuarioST->rowCount();

        if ($quantidadeUsuarios == 1) {
            $resultadoUsuario = $sqlUsuarioST->fetchALL();

            list($codigoUsuario, $nomeUsuario) = $resultadoUsuario[0];

            $_SESSION['verificaUsuarioLogado'] = True;
            $SESSION['codigoUsuarioLogado'] = $codigoUsuario;
            $nomeCompletoUsuario = explode(' ', $nomeUsuario);
            $_SESSION['nomeUsuarioLogado'] = $nomeCompletoUsuario[0];

            header("Location: main.php");
        } else {
            header("Location: index.php?codMsg=002");
        }
    } else {
        header("Location: index.php?codMsg=001");
    }
