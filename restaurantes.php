<?php

require_once './config/includes.inc.php';

head();

$DELETE = '';

if ($_GET) {

    if ($_GET['id']) {
        $DELETE = deleteRestaurante($_GET['id']);
    }
}

if ($_POST) {

    if ($_POST['dsrestaurante']) {
        insertRestaurantes($_POST['dsrestaurante']);
    } else {
        echo "<div class='alert alert-danger'>ATENÇÃO: Informe o Nome do Restaurante!</div>";
    }
}

$GRID = gridRestaurantes();

?>

<form name="Restaurantes" action="restaurantes.php" method="post">
    <div class="form-group">
        <h3>Cadastro de Restaurantes</h3>
    </div>
    <div class="container" id='container' name='container' style="display:none;">

        <div class="form-group">
            <label for="">Razão Social do Restaurante</label>
            <input type="text" class="form-control" name="dsrestaurante" id="dsrestaurante" aria-describedby="helpId" placeholder="" style="width: 600px;">
            <small id="helpId" class="form-text text-muted">Digite aqui o nome do novo restaurante</small>
        </div>

    </div>

    <button type="button" id="btn-div" class="btn btn-info" onclick="mostraDiv();">Formulário</button>
    
    <button type="submit" class="btn btn-primary">Cadastrar</button>

    <p></p>
    <?php echo $DELETE; ?>
    <p></p>

    <div class="form-group">
        <h5>Restaurantes Cadastrados </h5>
    </div>
    <p></p>

    <?php echo $GRID; ?>
</form>

<script type='text/javascript'>
    // confirm record deletion
    function delete_restaurante(id) {

        var answer = confirm('Tem certeza que deseja apagar o restaurante ?');
        if (answer) {
            // if user clicked ok,
            // pass the id to delete.php and execute the delete query
            window.location = 'restaurantes.php?id=' + id;
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