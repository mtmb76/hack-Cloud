<?php

require_once './config/includes.inc.php';

head();

$DELETE = '';

if ($_GET) {

    if ($_GET['id_restaurante'] && $_GET['id_prato']) {
        $DELETE = deletePratos($_GET['id_restaurante'], $_GET['id_prato']);
    }
}

if ($_POST) {

    if ($_POST['id_restaurante'] && $_POST['nome'] && $_POST['descricao'] && $_POST['preco']) {
        insertPrato($_POST['id_restaurante'], $_POST['nome'], $_POST['descricao'], $_POST['preco']);
    } else {
        echo "<div class='alert alert-danger'>ATENÇÃO: Dados insuficientes para o cadastramento!</div>";
    }
}

$GRID               = gridPratos();
$COMBORESTAURANTES  = comboRestaurantes();

?>

<form name="Pratos" action="pratos.php" method="post">
    <div class="form-group">
        <h3>Cadastro de Pratos por Restaurante</h3>
    </div>
    <div class="container" id='container' name='container' style="display:none;">

        <?php echo $COMBORESTAURANTES; ?>

        <div class="form-group">
            <label for="nome">Qual o nome do Prato?</label>
            <input type="text" class="form-control" name="nome" id="nome" aria-describedby="helpId" placeholder="" style="width: 400px;" required="required">
            <small id="helpId" class="form-text text-muted">Digite aqui o nome do novo prato</small>
        </div>
        <div class="form-group">
            <label for="preco">Qual o valor do Prato?</label>
            <input type="number" min="0.00" max="10000.00" step="0.01" class="form-control" name="preco" id="preco" aria-describedby="helpId" placeholder="" style="width: 200px;" required="required">
            <small id="helpId" class="form-text text-muted">Qual o valor do Prato?</small>
        </div>
        <div class="form-group">
            <label for="descricao">Descreva o preparo do seu prato aqui: </label>
            <textarea class="form-control" name="descricao" id="descricao" rows="3" style="width: 400px;" required="required"></textarea>
        </div>

    </div>

    <button type="button" id="btn-div" class="btn btn-info" onclick="mostraDiv();">Formulário</button>

    <button type="submit" class="btn btn-primary">Cadastrar</button>

    <p></p>
    <?php echo $DELETE; ?>
    <p></p>
    <div class="form-group">
        <h5>Lista de Pratos já Cadastrados </h5>
    </div>
    <p></p>
    <?php echo $GRID; ?>

</form>

<script type='text/javascript'>
    // confirm record deletion
    function delete_prato(id_restaurante, id_prato) {

        var answer = confirm('Tem certeza que deseja apagar o prato ?');
        if (answer) {
            // if user clicked ok,
            // pass the id to delete.php and execute the delete query
            window.location = 'pratos.php?id_restaurante=' + id_restaurante + '&id_prato=' + id_prato;
        }
    }

    function mostraDiv() {
        var btn = document.getElementById('btn-div');
        var container = document.getElementById('container');

        if (container.style.display === 'block') {
            container.style.display = 'none';
        } else {
            container.style.display = 'block';
        }
    }
</script>

<?php
footer();
?>