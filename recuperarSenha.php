<?php
    require 'common/PHPMailer.php';
    require 'common/SMTP.php';
    require 'common/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    if (isset($_POST['mailUsuario'])) {
        $mailUsuario = $_POST['mailUsuario'];
    
        include 'conectaBanco.php';

        $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario=:mailUsuario LIMIT 1";

        $sqlUsuarioST = $conexao->prepare($sqlUsuario);

        $sqlUsuarioST->bindValue(':mailUsuario', $mailUsuario);

        $sqlUsuarioST->execute();

        $quantidadeUsuarios = $sqlUsuarioST->rowCount();

        if ($quantidadeUsuarios == 1) {
            $resultadoUsuario = $sqlUsuarioST->fetchALL();

            list($codigoUsuario, $nomeUsuario) = $resultadoUsuario[0];

            $nomeCompletoUsuario = explode(' ', $nomeUsuario);
            $nomeUsuario = $nomeCompletoUsuario[0];

            include "common/gerarSenha.php";
            $novaSenha = gerarSenha(8);
            $novaSenhaMD5 = md5($novaSenha);

            $sqlAlterarSenha = "UPDATE usuarios SET senhaUsuario=:novaSenhaMD5 WHERE codigoUsuario=:codigoUsuario";

            $sqlAlterarSenhaST = $conexao->prepare($sqlAlterarSenha);

            $sqlAlterarSenhaST->bindValue(':novaSenhaMD5', $novaSenhaMD5);
            $sqlAlterarSenhaST->bindValue(':codigoUsuario', $codigoUsuario);

            if ($sqlAlterarSenhaST->execute()) {
                include 'common/constantes.php';

                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 465;
                $mail->Username = GUSER;
                $mail->Password = GPWD;

                $mensagem = "Olá $nomeUsuario!<br/><br/>
                            Recebemos sua solicitação de alteração de senha do sistema Agenda de Contatos.<br/><br/>
                            Sua nova senha é: <span style=\"font-weight: bold; color: #FF0000\">$novaSenha</span><br/><br/>
                            Para sua segurança, altere sua senha no primeiro acesso ao sistema. <br/><br/>
                            Atenciosamente,<br/>
                            Equipe de desenvolvimento.";

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->SetFrom(GUSER, GNAME);
                $mail->AddAddress($mailUsuario);
                $mail->Subject = 'Recuperação de senha';
                $mail->Body = $mensagem;
<<<<<<< HEAD
                
=======
>>>>>>> ce0ff0d4ee90593888656c0731d647d3277f0dba

                if ($mail->send()) {
                    header("Location: index.php?codMsg=008");
                } else { //erro ao enviar senha
                    header("Location: index.php?codMsg=007");
                }
            } else { //erro ao gerar nova senha
                header("Location: index.php?codMsg=006");
            }
        } else { //usuário não cadastrado
            header("Location: index.php?codMsg=005");
        }
    } else { //e-mail do usuário não informado
        header("Location: index.php?codMsg=004");
    }
?>