<?php
include 'protect.php';
require 'connect.php';

if (isset($_REQUEST["customer_id"])){
    //save sales
    $customer_id = $_REQUEST["customer_id"];
    $user_id = $_SESSION["id"];
    $book_ids = $_SESSION["products"];
    $date_sold = date("Y-m-d");
    //save
    foreach ($book_ids as $pid){
        $query = "INSERT INTO `sales`(`id`,`user_id`,`book_id`, `date_sold`, `customer_id`) 
                           VALUES (null ,$user_id,$pid,$customer_id,$date_sold)";
        mysqli_query($con, $query) or die(mysqli_error($con));
    }
//clear cart
    //unset($_SESSION["products"]);
    $_SESSION["books"]=[];
}
if (isset($_GET["id"])){
    $_SESSION["books"]= array_diff($_SESSION["books"], [$_GET["id"]]);
}
if (count($_SESSION["books"]) ==0){
    header("location:sell.php");
}
$ids = array_unique($_SESSION["books"]);
$data = implode(",", $ids);
$sql = "SELECT * FROM books WHERE id IN($data)";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));// executing the query
$rows = mysqli_fetch_all($result, 1);//assoc array

//fetch all customers
$sql2 = "SELECT * FROM customers";
$result2 = mysqli_query($con, $sql2) or die(mysqli_error($con));// executing the query
$customers = mysqli_fetch_all($result2, 1);//assoc array

mysqli_close($con);//close the connection
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>
<body>

<?php include 'nav.php' ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">

            <form action="checkout.php" method="post" class="form-inline mt-2 mb-2">
               <div class="form-group">
                   <select name="customer_id" class="form-control">
                       <?php foreach ($customers as $person): ?>
                           <option value="<?=$person["id"]?>"> <?=$person["names"]?> </option>

                       <?php endforeach; ?>
                   </select>
               </div>
                <button class="btn btn-info btn-sm ml-2">Complete Transactiion</button>
            </form>

            <table class="table table-striped table-bordered">

                <thead>
                <tr>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Description</th>
                    <th>Poster</th>
                    <th>Remove</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($rows as $book): ?>
                    <tr>
                        <td> <?= $book["title"] ?> </td>
                        <td> <?= $book["genre"] ?> </td>
                        <td> <?= $book["description"] ?> </td>
                        <td><img src="<?= $book['poster'] ?>" width="60" height="60" alt=""></td>
                        <td><a class="btn btn-danger btn-sm" href="checkout.php?id=<?= $book["id"] ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>

</body>
</html>

