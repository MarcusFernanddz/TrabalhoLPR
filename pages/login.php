<html>
    <head>
        <style>
            body {
                background-image: url('https://media1.tenor.com/m/lP9CwPDtCB0AAAAC/dante-dmc.gif');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                height: auto;
                margin: 0;
                color: blue; 
            }
        </style>
    </head>
    <body>
        <h1>LOGIN</h1>

        <form method="post" action="">
            <label>Email ou Numero ou CPF</label>
            <input name="emailnumero" size="25" type="text" autocomplete="off" required>
            <br><br>

            <label>Senha</label>
            <input name="senha" size="10" type="password" autocomplete="off" required>
            <br><br>	

            <button type="submit" name="cadastrar">login</button>
        </form>

        <form method="post" action="">
            <button type="submit" name="l">cadastrar</button>
        </form>

    </body>
</html>

<?php
    include('../connect/conexao.php');

    function validaCPF($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf); 
        if (strlen($cpf) != 11) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    if (isset($_POST['cadastrar'])) {

        $entrada = trim($_POST['emailnumero']);  
        $senha = trim($_POST['senha']);

        
        $is_cpf = validaCPF($entrada);
        $is_email = filter_var($entrada, FILTER_VALIDATE_EMAIL);
        $is_telefone = preg_match('/^[0-9]{10,11}$/', $entrada);

        
        if (!$is_email && !$is_telefone && !$is_cpf) {
            $erro = "Digite um email, telefone ou CPF válido!";
        } else {

            
            if ($is_cpf) {
                $entrada = hash('sha256', $entrada);
            }

            
            $stmt = $conexao->prepare("SELECT * FROM teste WHERE emailnumero = ?");
            $stmt->bind_param("s", $entrada);
            $stmt->execute();
            $result = $stmt->get_result();

            $usuario = $result->fetch_assoc();

            
            if ($usuario && password_verify($senha, $usuario['senha'])) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_email'] = $usuario['emailnumero'];

                header("Location: form.php");
                exit;

            } else {
                $erro = "Email, CPF, telefone ou senha incorretos!";
            }
        }
    }

    if (isset($_POST['l'])):
        header("Location: hugolindo.php");
    endif;
?>
