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
<h1>CADASTRAR</h1>

<form method="post" action="">
    <label>Email ou Numero ou CPF</label>
    <input name="emailnumero" size="25" type="text" autocomplete="off" required>
    <br><br>

    <label>Senha</label>
    <input name="senha" size="10" type="password" autocomplete="off" required>
    <br><br>	

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>

<form method="post" action="">
    <button type="submit" name="l">Login</button>
</form>

</body>
</html>

<?php
include('../connect/conexao.php');

if (isset($_POST['cadastrar'])):

    $entrada = trim($_POST['emailnumero']);  
    $senha = trim($_POST['senha']);

    $erros = [];

    // Validação da senha
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $senha)) {
        $erros[] = "A senha deve ter no mínimo 8 caracteres, conter uma letra maiúscula, uma minúscula e um símbolo.";
    }

    // Função CPF
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

    $is_cpf = validaCPF($entrada);
    $is_email = filter_var($entrada, FILTER_VALIDATE_EMAIL);
    $is_telefone = preg_match('/^[0-9]{10,11}$/', $entrada);

    // Validação da entrada
    if (!$is_email && !$is_telefone && !$is_cpf) {
        $erros[] = "Digite um email, telefone ou CPF válido!";
    }

    // Se houver erros, mostra todos
    if (!empty($erros)) {
        foreach ($erros as $erro) {
            echo $erro . "<br>";
        }
        exit;
    }

    // Criptografia
    if ($is_cpf) {
        $entrada_final = hash('sha256', $entrada);
    } else {
        $entrada_final = $entrada;
    }

    $senha_final = password_hash($senha, PASSWORD_DEFAULT);

    // Inserção
    $sql = mysqli_query($conexao, 
        "INSERT INTO `teste`(`emailnumero`, `senha`) 
         VALUES ('$entrada_final','$senha_final')"
    );

    if ($sql) {
        echo 'Cadastro realizado com sucesso! <br>
        <img src="https://media.tenor.com/WlJsOVX2lysAAAAi/cat-tongue-cat.gif">';
    } else {
        echo 'Erro ao cadastrar: ' . mysqli_error($conexao);
    }

endif;

if (isset($_POST['l'])):
    header("Location: login.php");
endif;
?>