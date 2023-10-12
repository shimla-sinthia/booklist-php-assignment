<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<?php
    
    $mainJson = file_get_contents("books.json");
    $books = json_decode($mainJson, true);
    $operator = $_GET['op'];
    if($operator == "Search")
    {
        $title  = $_GET['bookTitle'];
        $author = $_GET['bookAuthor'];
        $isbn = $_GET['isbnNumber'];
        $flag = false;
        for($i = 0; $i < count($books); $i++){
            if((($books[$i]["bookTitle"] == $title && $books[$i]["bookAuthor"] == $author) || $books[$i]["isbnNumber"] == $isbn) && !$flag){
                echo "Available";
                $flag = true;
            }
        }
        if(!$flag){
            echo "Not available";
        }
    }
    else if($operator == "Save")
    {
        $array = array(
            'bookTitle' => $_GET['bookTitle'],
            'bookAuthor'=> $_GET['bookAuthor'],
            'isAvailable'=> $_GET['isAvailable'],
            'numberOfPages'=> $_GET['numberOfPages'],
            'isbnNumber'=> $_GET['isbnNumber']
        );
        array_push($books, $array);
        $data_to_save = $books;
        if(!file_put_contents("books.json", json_encode($data_to_save, JSON_PRETTY_PRINT), LOCK_EX))
        {
            echo "ERROR to save";
        }
        else
        {
            echo "Successfully saved";
        }
    }
    else if($operator == "Delete")
    {
        $id = $_GET['isbnNumber'];
        $flag = false;
        for($i = 0; $i < count($books); $i++)
        {
            if($books[$i]["isbnNumber"] == $id && !$flag)
            {
                unset($books[$i]);
                $flag = true;
            }
        }
        $data_to_delete = $books;
        if(!file_put_contents("books.json", json_encode($data_to_delete, JSON_PRETTY_PRINT), LOCK_EX))
        {
            echo "ERROR to delete";
        }
        else if($flag == false)
        {
            echo "Not found";
        }
        else
        {
            echo "Successfully deleted";
        }
    }
    else if($operator == "Read")
    {
        ?>
        <table class="table table-bordered table-hover">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Available</th>
            <th>Pages</th>
            <th>ISBN</th>
        </tr>
        <?php
        if(count($books) != 0)
        {
            foreach($books as $info)
            {
                if($info['isAvailable'] == 1)
                    $info['isAvailable'] = "true";
                else
                    $info['isAvailable'] = "false";
               ?>
                <tr>
                <td><?php echo $info['bookTitle'] ?></td>
                <td><?php echo $info['bookAuthor'] ?></td>
                <td><?php echo $info['isAvailable'] ?></td>
                <td><?php echo $info['numberOfPages'] ?></td>
                <td><?php echo $info['isbnNumber'] ?></td>
                </tr>
                <?php
            }
        }
    }
?>
</html>
