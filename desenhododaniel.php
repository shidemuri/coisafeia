<?php
    $total = 0;

    $contas = ( isset($_POST["tabela"]) ) ? unserialize($_POST["tabela"]) : [];

    if (isset($_POST["nome"]) && $_POST["nome"] != "") {
        $nome = $_POST["nome"];
        $valor = (isset($_POST["valor"]) && $_POST["valor"] != "") ? (float)$_POST["valor"] : 0;
        $contas[] = ["nome" =>$_POST["nome"], "valor" =>$valor];
    };
    if(isset($_POST["apagar"]) && $_POST["apagar"] != "" && $_POST["apagar"] < count($contas) && $_POST["apagar"] >= 0) {
        //o unset só deixa um vazio onde vc apagou, o splice apaga e arruma a lista pra tirar aquele vazio
        array_splice($contas,$_POST["apagar"],1);
    };
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Contas</title>
</head>
<body>
    <h1 style="text-align: center; margin-top: 20px; font-weight: bold;">Controle de Contas Mensais</h1>

    <div class="container mt-5">
        <form action="cadastro.php" method="POST">
            <input type="hidden" name="tabela" value="<?=htmlspecialchars(serialize($contas))?>"/>
            Tipo de Conta: <br>
            <input type="text" class="form-control" name="nome" placeholder="Digite o nome da conta"><br>
            Valor da Conta: <br>
            <input type="number" class="form-control" step="0.010" name="valor" placeholder="Digite o valor da conta"><br>
            <input class="btn btn-success" type="submit" value="Enviar">
            <input class="btn btn-danger" type="reset" value="Limpar">
        </form>
        <form action="cadastro.php" method="post" id="apagarform">
            <input type="hidden" name="tabela" value="<?=htmlspecialchars(serialize($contas))?>"/>
            <input type="hidden" name="apagar" id="apagarinput">
        </form>
        <script>
            const form = document.querySelector("#apagarform");
            const idx = document.querySelector("#apagarinput");
            function apagar(i){
                idx.value = i;
                form.submit();
            };
        </script>
        <br>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nº</th>
                    <th scope="col">Tipo de Conta</th>
                    <th scope="col">Valor Formatado</th>
                    <th scope="col">Apagar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contas as $index => $conta) { 
                    $total = $total + $conta["valor"];
                ?>
                <tr>
                    <th scope="row"><?php echo $index + 1; ?></th>
                    <td><?=$conta["nome"]?></td>
                    <td><?='R$ ' . number_format($conta["valor"], 2, ',', '.')?></td>
                    <td><button class="btn btn-danger" onclick="apagar(<?=$index?>)">Apagar</button></td>
                </tr>
                <?php } ?>
                <tr>
                    <th colspan="3">Total: <?='R$ ' . number_format($total, 2, ',', '.')?></th>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
