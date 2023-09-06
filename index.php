<?php
require_once('classes/crud.php');
require_once('conexao/conexao.php');

//é responsável por configurar a conexão com o banco de dados e criar uma instância da classe "Crud" para que a aplicação possa realizar operações de banco de dados, como criar, ler, atualizar e excluir registros de carros.

$database = new Database();
$db = $database->getConnection();
$crud = new Crud($db);


//O código verifica se a variável $_GET['action'] está definida na URL

if(isset($_GET['action'])){
    switch($_GET['action']){ //a estrutura switch-case para determinar a ação
        case 'create':
            $crud->create($_POST); //os dados enviados via $_POST (geralmente um formulário enviado) para criar um novo registro no banco de dados. 
            $rows = $crud->read(); //para buscar registros do banco de dados e atualiza a variável $rows com esses resultados.
            break;
        case 'read':
            $rows = $crud->read();
            break;
        case 'update': 
            if(isset($_POST['id'])){ 
                $crud->update($_POST); // para atualizar um registro existente no banco de dados.
            }
            $rows=$crud->read(); ////Após a atualização, ele chama o método read para buscar todos os registros atualizados no banco de dados e armazená-los na variável $rows.
            break;
            
        case 'delete':
            $crud->delete($_GET['id']); // delete ele recebe o valor de $_GET['id'] (geralmente um identificador de registro) e utiliza esse valor para excluir um registro da tabela no banco de dados.

            $rows = $crud->read(); //para buscar todos os registros atualizados no banco de dados apó ser excluído
            break;

        default:
        $rows = $crud->read();//armazenar os dados atualizados
        break;
        

    }
}else{ //permite que a página exiba os registros quando nenhum comando específico é dado na URL.
    $rows = $crud->read();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Presença</title>
    <style>
        body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
        background-image: linear-gradient(to right,indigo,violet);
       }
       form{
        background-color: rgb(233, 224, 255);
        max-width: 400px;
        margin: 100px auto;
        padding: 40px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgb(216, 200, 255);

        }
         label{
            display: flex;
            margin-top:10px;
         }
         input[type=text]{
            width:100%;
            padding: 12px 20px;
            margin: 8px 0;
            display:inline-block;
            border: 1px solid #ccc;
            border-radius:4px;
            box-sizing:border-box;
         }
         input[type=submit]{
            background-color: rgb(120, 103, 164);
            color:white;
            padding:12px 20px;
            border:none;
            border-radius:4px;
            cursor:pointer;
            float:right;
         }
         input[type=submit]:hover{
            background-color:rgb(216, 200, 255);
         }
         table{
            border-collapse:collapse;
            width:100%;
            font-family:Arial, sans-serif;
            font-size:14px;
            color:#333;
            background-color: white;
         }
         th, td{
            text-align:left;
            padding:8px;
            border: 1px solid #ddd;
         }
        th{
           background-color:rgb(216, 200, 255);
           font-weight:bold; 
        }
        a{
            display:inline-block;
            padding:4px 8px;
            background-color: rgb(216, 200, 255);
            color:#fff;
            text-decoration:none;
            border-radius:4px;
        }
        a:hover{
            background-color:rgb(216, 200, 255);
        }

        a.delete{
            background-color: rgb(216, 200, 255);
        }
        a.delete:hover{
            background-color:red;
        }

        h1 {
            font-size: 24px;
            color: #323232;
            text-align: center;
            margin: 40px;
            
        }
    </style>

    <h1> Presença </h1>

</head>
</head>

<body>


<?php  

    if(isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])){
        $id = $_GET['id'];
        $result = $crud->readOne($id);

        if(!$result){
            echo "Registro não encontrado.";
            exit();
        }
        $nome = $result['nome'];
        $numero = $result['numero'];
        $turma = $result['turma'];
        $data = $result['data'];
        $professor = $result['professor'];
       
    
?>
    <form action="?action=update" method="POST">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <label for="nome">Nome</label>
        <input type="text" name="nome" value="<?php echo $nome ?>">

        <label for="numero">Número</label>
        <input type="text" name="numero" value="<?php echo $numero ?>">

        <label for="turma">Turma</label>
        <input type="text" name="turma" value="<?php echo $turma ?>">

        <label for="data">Data</label>
        <input type="number" name="data" value="<?php echo $data ?>">

        <label for="professor">Professor</label>
        <input type="text" name="professor" value="<?php echo $professor ?>">

        <input type="submit" value="Atualizar" name="enviar"  onclick="return confirm('Certeza que deseja atualizar?')">
    </form>

    <?php }else{?>


    <form action="?action=create" method="POST">
        <label for="">Nome</label>
        <input type="text" name="nome">

        <label for="">Numero</label>
        <input type="number" name="numero">

        <label for="">Turma</label>
        <input type="text" name="turma">

        <label for="">Data</label>
        <input type="date" name="data">

        <label for="">Professor</label>
        <input type="text" name="professor">


        <input type="submit" value="Cadastrar" name="enviar">
    </form>
    <?php }?>


    <table>
        <tr>
            <td>Id</td>
            <td>Nome</td>
            <td>Numero</td>
            <td>Turma</td>
            <td>Data</td>
            <td>Professor</td>
            <td>Ações</td>
        </tr>
        <?php
  if($rows->rowCount() == 0){
    echo "<tr>";
    echo "<td colspan='7'>Nenhum dado encontrado</td>";
    echo "</tr>";
  } else {
    while($row = $rows->fetch(PDO::FETCH_ASSOC)){
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['nome'] . "</td>";
      echo "<td>" . $row['numero'] . "</td>";
      echo "<td>" . $row['turma'] . "</td>";
      echo "<td>" . $row['data'] . "</td>";
      echo "<td>" . $row['professor'] . "</td>";
      echo "<td>";
      echo "<a href='?action=update&id=" . $row['id'] . "'>Atualizar</a>";
      echo "<a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que quer apagar esse registro?\")' class='delete'>Deletar</a>";
      echo "</td>";
      echo "</tr>";
    }
  }
?>
    </table>
</body>
</html>