<?php

require_once 'config/database.php';


function comboRestaurantes(){

    // select all data
    $query = "SELECT id, nome FROM restaurante ORDER BY nome";
    $stmt =  $GLOBALS['con']->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    #echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create New Product</a>";

    //check if more than 0 record found
    if ($num > 0) {

        $combo = '
        <div class="form-group">
            <label for="id_restaurante"></label>
            <select class="custom-select" name="id_restaurante" id="id_restaurante" style="width:400px;" required="required">
                <option selected></option>';
 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            // creating new table row per record
            $combo .= '<option value="'.$id.'">'.$nome.'</option>';

        }

        $combo .= '
            </select>
        </div>';
    }
    // if no records found
    else {
        $combo = "<div class='alert alert-danger'>Nenhum Restaurante Cadastrado.</div>";
    }

    return $combo;    
}

function deletePratos($id_restaurante,$id_prato)
{
    try {

        // delete query
        $query = "DELETE FROM prato WHERE id_restaurante=:id_restaurante and id_prato=:id_prato ";
        $stmt  = $GLOBALS['con']->prepare($query);
        
        $stmt->bindParam(':id_restaurante'  , $id_restaurante);
        $stmt->bindParam(':id_prato'        , $id_prato);

        if ($stmt->execute()) {
            $resposta = "<div class='alert alert-success'>Prato descadrastado com sucesso!</div>"; #header('Location: pratos.php?action=deleted');
        } else {
            $resposta = "<div class='alert alert-danger'>ATENÇÂO: Não foi possível apagar o registro!</div>";; #die('Não foi possível apagar o registro.');
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    return $resposta;
}

function deleteRestaurante($id){
    try {

        // delete query
        $query = "DELETE FROM restaurante WHERE id = ?";
        $stmt  = $GLOBALS['con']->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            $resposta = "<div class='alert alert-success'>Restaurante descadrastado com sucesso!</div>";#header('Location: restaurantes.php?action=deleted');
        } else {
            $resposta = "<div class='alert alert-danger'>ATENÇÂO: Não foi possível apagar o registro!</div>";;#die('Não foi possível apagar o registro.');
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    return $resposta;
}

function insertPrato($id_restaurante,$nome,$descricao,$preco)
{
    try {

        // insert query
        $query = "INSERT INTO prato SET id_restaurante=:id_restaurante, nome=:nome, descricao=:descricao, preco=:preco";

        // prepare query for execution
        $stmt = $GLOBALS['con']->prepare($query);

        // posted values
        $id_restaurante = htmlspecialchars(strip_tags($id_restaurante));
        $nome           = htmlspecialchars(strip_tags($nome));
        $descricao      = htmlspecialchars(strip_tags($descricao));
        $preco          = htmlspecialchars(strip_tags($preco));

        // bind the parameters
        $stmt->bindParam(':id_restaurante'  , $id_restaurante);
        $stmt->bindParam(':nome'            , $nome);
        $stmt->bindParam(':descricao'       , $descricao);
        $stmt->bindParam(':preco'           , $preco);

         // Execute the query
        if ($stmt->execute()) {
            $resposta = "<div class='alert alert-success'>SUCESSO: Novo prato cadastrado.</div>";
        } else {
            $resposta = "<div class='alert alert-danger'>ATENÇÂO: Não foi possível cadastrar o novo prato!</div>";
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    return $resposta;
}

function insertRestaurantes($razao_social){
    try {

        // insert query
        $query = "INSERT INTO restaurante SET nome=:nome";

        // prepare query for execution
        $stmt = $GLOBALS['con']->prepare($query);

        // posted values
        $nome = htmlspecialchars( strip_tags( $razao_social ) );
  
        // bind the parameters
        $stmt->bindParam(':nome', $nome);
 
        // Execute the query
        if ($stmt->execute()) {
            $resposta = "<div class='alert alert-success'>SUCESSO: Novo restaurante cadastrado.</div>";
        } else {
            $resposta = "<div class='alert alert-danger'>ATENÇÂO: Não foi possível cadastrar o novo restaurante!</div>";
        }
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    return $resposta;

}

function gridPratos()
{

    // select all data
    $query = "SELECT p.id_prato, p.id_restaurante, r.nome as restaurante, p.nome as prato, p.descricao, p.preco 
                FROM prato p, restaurante r 
               WHERE p.id_restaurante = r.id
            ORDER BY id_restaurante,id_prato";
    $stmt =  $GLOBALS['con']->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    #echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create New Product</a>";

    //check if more than 0 record found
    if ($num > 0) {

        $grid = '
                <div class="container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Restaurante</th>
                                <th>ID</th>
                                <th>Prato</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                            </tr>
                        </thead>
                        <tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            // creating new table row per record
            $grid .= '
                <tr>
                    <td scope="row">'   . $restaurante . '</td>
                    <td>' . $id_prato   . '</td>
                    <td>' . $prato      . '</td>
                    <td>' . $descricao  . '</td>
                    <td>' . $preco      . '</td>';

            // we will use this links on next part of this post
            $grid .= "<td><a href='#' onclick='delete_prato(" . $id_restaurante . ",". $id_prato .");'  class='btn btn-danger'>Apagar</a></td>";
            $grid .= "</tr>";
        }

        $grid .=    '</tbody>
                </table>
            </div>';
    }
    // if no records found
    else {
        $grid = "<div class='alert alert-danger'>Nenhum Prato Cadastrado.</div>";
    }

    return $grid;
}

function gridRestaurantes(){

    // select all data
    $query = "SELECT id, nome FROM restaurante ORDER BY nome";
    $stmt =  $GLOBALS['con']->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    #echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create New Product</a>";

    //check if more than 0 record found
    if ($num > 0) {

        $grid = '
                <div class="container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            // creating new table row per record
            $grid .= '
                <tr>
                    <td scope="row">'.$id.'</td>
                    <td>'.$nome.'</td>';

            // we will use this links on next part of this post
            $grid .= "<td><a href='#' onclick='delete_restaurante(".$id. ");'  class='btn btn-danger'>Apagar</a></td>";
            $grid .= "</tr>";
        }

        $grid .=    '</tbody>
                </table>
            </div>';

    }
    // if no records found
    else {
        $grid = "<div class='alert alert-danger'>Nenhum Restaurante Cadastrado.</div>";
    }

    return $grid;

}


function head()
{
    $title    = 'Kiss Food';
    $subtitle = 'Projeto Hack@Cloud Oracle - Localfrio';

    echo
    '<!DOCTYPE HTML>
            <html>

            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <title>Kiss Food</title>

                <!-- Latest compiled and minified Bootstrap CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

            </head>

            <body>

                <!-- container -->
                <div class="container">
                    <p></p>
                    <div class="page-header" style="background-color:red;">
                        </br>
                        <h1 style="color:white;">&nbsp;' . $title. '</h1>
                  
                        <div> 
                            <h6 style="color:#808080;color:white;">&nbsp;&nbsp;&nbsp;' . $subtitle . '</h6>
                        </div>
                        </br>
                    </div>
                    <p></p>
                    <p></p>
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.php">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="restaurantes.php">Restaurantes</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="pratos.php">Pratos</a>
                        </li>                        
                    </ul>


                </div> <!-- end .container -->
                <p></p>
                <div class="container">';
}

function footer()
{
    echo
    '
            </div>

            <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
            <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

            <!-- Latest compiled and minified Bootstrap JavaScript -->
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        </body>

        </html>   
    ';
}


?>