<?php
  require_once "pdo.php";
  session_start();

  if(isset($_POST['name']) && isset($_POST['author']) && isset($_POST['year']) && isset($_POST['type'])){
    $st = $pdo->prepare("UPDATE book SET name=:n, author=:a, year=:y, type=:t where book_id = :b");
    $st->execute(array(
      ':n' => $_POST['name'],
      ':a' => $_POST['author'],
      ':y' => $_POST['year'],
      ':t' => $_POST['type'],
      ':b' => $_SESSION['bsearch'],
    ));
    $_SESSION['message'] = "Successfully Edited";
    header('location: index2.php');
  }
  else{
    echo("<p style='color:red'>Please enter all details");
  }
?>

<html>
  <head>
    <title>OBS:EDIT</title>

    <link rel="stylesheet" href="indexstyle.css?<?php echo time(); ?>">
    <script src="script.js"></script>
  </head>
  <body>

    <!---navigation---->
      <ul class="nav__list">
        <li class="nav__l_item " ><a href = 'index2.php' class="nav__link active">HOME</a></li>
        <li class="nav__l_item"><a href = 'about.php' class="nav__link">ABOUT</a></li>
        <li class="nav__l_item"><a href = 'contact.php' class="nav__link">CONTACT</a></li>
        <li class="nav__r_item"><a href = 'profile.php' class="nav__link">PROFILE</a></li>
        <li class="nav__r_item"><a href = '#sales.php' class="nav__link">SALES</a></li>
      </ul>
      <div class='container container__add'>
        <?php
          if(isset($_SESSION["bsearch"])){
            $st = $pdo->prepare("SELECT * FROM book WHERE book_id=:n");
            $st->execute(array(':n' => $_SESSION['bsearch']));
            $row = $st->fetch(PDO::FETCH_ASSOC);
        ?>
        <form method="post">
          <div class='row'>
            <div class='col-25'><label class='input__label'>Name</label></div>
            <div class='col-75'><label><input class='input__text' type="text" name="name" value="<?php echo($row['name']) ?>"></label></div>
          </div>
          <div class='row'>
            <div class='col-25'><label class='input__label'>Author</label></div>
            <div class='col-75'><label><input class='input__text' type="text" name="author" value="<?php echo($row['author']) ?>"></label></div>
          </div>
          <div class='row'>
            <div class='col-25'><label class='input__label'>Year</label></div>
            <div class='col-75'><label><input class='input__text' type="text" name="year" value="<?php echo($row['year']) ?>"></label></div>
          </div>

            <?php
              if(isset($_SESSION["bname"])){
                echo("<lable><input type='hidden' name='type' value='".$_SESSION['bname']."'></label><br>");
                unset($_SESSION["bname"]);
              }
              else{
                echo("<div class='row'><div class='col-25'>Category : </div>");
                $st = $pdo->query("SELECT category FROM book");
                $type = array();
                $index=0; $flag=0;
                //finding sorted order of type
                while($row = $st->fetch(PDO::FETCH_ASSOC)){
                  for($i = 0; $i < sizeof($type); $i++){
                    if($row['category'] == $type[$i]){
                      $flag = 1;
                      break;
                    }
                  }

                  if($flag == 0){
                    $type[$index] = $row['category'];
                    $index++;
                  }
                  $flag=0;
                }
                sort($type);
                echo ("<div class='col-75'><label onClick='types()'><select name='category' onClick='types()'><option value='".$type[0]."' onClick='types()'>".$type[0]."</option>");
                //printing
                for($i=1; $i < sizeof($type); $i++) {
                  echo ("<option value='".$type[$i]."' onClick='types()'>".$type[$i]."</option>");
                }
                echo ("<option value='".$row['category']."'>Others</option></select></label><label onClick='othertype()'>
                  Others : <input id='other_text' type='text' name='other'/></label>
                  </div></div>");
              }
            ?>
            <div class='row'>
              <div class='col-25'><label class='input__label'>Publisher</label></div>
              <div class='col-75'><label><input class='input__text' type="text" name="publisher" value="<?php echo($row['publisher']) ?>"></label></div>
            </div>
            <div class='row'>
              <div class='col-25'><label class='input__label'>No of stocks</label></div>
              <div class='col-75'><label><input class='input__text' type="text" name="stock" value="<?php echo($row['stock']) ?>"></label></div>
            </div>
            <div class='row'>
              <div class='col-25'><label class='input__label'>Description</label></div>
              <div class='col-75'><label><textarea class='input__text' type="text" rows='4' name="description" value="<?php echo($row['description']) ?>"></textarea></label></div>
            </div>
            <div class='row'>
              <div class='col-25'><label class='input__label'>Image URL</label></div>
              <div class='col-75'><label><input class='input__text' type="text" name="image" value="<?php echo($row['image']) ?>"></label></div>
            </div>
            <input class='input__submit' type="submit" value="Save Changes">
        </form>


    </div>
  </body>
</html>
