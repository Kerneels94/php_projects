<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles.css" />
    <title>Document</title>
</head>

<body>
    <!-- Form -->

    <form action="" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" />

        <label for="surname">Surname</label>
        <input type="text" name="surname" id='surname' />

        <label for="birthday">Birthday</label>
        <input type="date" name="birthday" />

        <label for="id">Id number</label>
        <input type="number" name="id" id="id" />

        <div class="buttons">
            <button type="submit">Submit</button>
            <button type="submit">Cancel</button>
        </div>
    </form>
</body>

</html>

<?php

use MongoDB\Operation\DeleteOne;

function validateData($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    require "vendor/autoload.php";
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->Users->Results;

    // Get form values
    $firstName = validateData($_POST['name']);
    $surName = validateData($_POST['surname']);
    $id = $_POST['id'];
    $birthday = $_POST['birthday'];

    // Insert data in DB
    $collection->InsertOne([
        'name' => $firstName,
        'surname' => $surName,
        'id' => $id,
        'birthday' => $birthday,
    ]);

    // Check if the id is a integer
    is_int($id) ? printf('Id is not a interger') : printf('Id is a integer');
    echo '<br>';
    // Check id number length
    // *Extra if the id number is not 13 numbers long don't add the entry
    strlen($id) !== 13 ? printf('Invalid ID Number Length') && $collection->deleteOne([
        'name' => $firstName,
        'surname' => $surName,
        'id' => $id,
        'birthday' => $birthday,
    ]) : printf("Valid Id number");

    // Check duplicate ID numbers
    $idToCheck = $collection->findOne(['id' => $id]);
    echo '<br>';
    $idToCheck['id'] === $id ? printf("Duplicate id") && http_response_code(401) : printf("Not a duplicate ID");

    // Validate date
    $dateFormat = strtotime($birthday);
    $newDate = date("d/m/Y", $dateFormat);
}
