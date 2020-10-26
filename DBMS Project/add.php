<?php
  require_once "pdo.php";
  session_start();

  if(isset($_POST['name']) && isset($_POST['author']) && isset($_POST['year']) && isset($_POST['type'])){
    if($_POST['name'] == '' || $_POST['author'] == '' || $_POST['year']=='' || $_POST['type']==''){
      $_SESSION['error'] = "Please enter all the details";
      header('location: add.php');
      return;
    }
    elseif ($_POST['type']=='Others' && $_POST['other']==''){
      $_SESSION['error'] = "Please enter Category";
    }
    else{
      if($_POST['type'] == 'Others'){
        $_POST['type'] = $_POST['other'];
      }
      $st = $pdo->prepare("INSERT INTO book(name, author, year, type) VALUES(:n, :a, :y, :t)");
      $st->execute(array(
        ':n' => $_POST['name'],
        ':a' => $_POST['author'],
        ':y' => $_POST['year'],
        ':t' => $_POST['type'],
      ));
      $_SESSION['message'] = "Successfully book added";
      header('location: index2.php');
    }
  }
  /*else{
    echo("<p style='color:red'>Please enter all details</p>");
  }*/
?>

<html>
  <head>
    <title>OBS:ADD</title>

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

      <!---mid section--->

      <div class='container container__add'>
        <?php
          if(isset($_SESSION["error"])){
            echo("<p style='color:red;'>".$_SESSION["error"]."</p>");
            unset($_SESSION["error"]);
          }
        ?>

        <form method="post">
          <div class='row'>
            <div class='col-25'><label class='input__label'>Name</label></div>
            <div class='col-75'><label><input class='input__text' type="text" name="name" value=""></label></div>
          </div>
          <div class='row'>
            <div class='col-25'><label class='input__label'>Author</label></div>
            <div class='col-75'><label><input class='input__text' type="text" name="author" value=""></label></div>
          </div>
          <div class='row'>
            <div class='col-25'><label class='input__label'>Year</label></div>
            <div class='col-75'><label><input class='input__text' type="text" name="year" value=""></label></div>
          </div>

            <?php
              if(isset($_SESSION["bname"])){
                echo("<lable><input type='hidden' name='type' value='".$_SESSION['bname']."'></label><br>");
                unset($_SESSION["bname"]);
              }
              else{
                echo("<div class='row'><div class='col-25'>Type : </div>");
                $st = $pdo->query("SELECT type FROM book");
                $type = array();
                $index=0; $flag=0;
                //finding sorted order of type
                while($row = $st->fetch(PDO::FETCH_ASSOC)){
                  for($i = 0; $i < sizeof($type); $i++){
                    if($row['type'] == $type[$i]){
                      $flag = 1;
                      break;
                    }
                  }

                  if($flag == 0){
                    $type[$index] = $row['type'];
                    $index++;
                  }
                  $flag=0;
                }
                sort($type);
                echo ("<div class='col-75'><label><pre>    <input type='radio' name='type' value='".$type[0]."' onClick='types()'>".$type[0]."</pre></label></div></div>");
                //printing
                for($i=1; $i < sizeof($type); $i++) {
                  echo ("<div class='row'><div class='col-75'><label><pre>    <input type='radio' name='type' value='".$type[$i]."' onClick='types()'>".$type[$i]."</pre></label></div></div>");
                }
                echo ("<div class='row'>
                  <div class='col-75'><label onClick='othertype()'>
                  <pre>    <input id='other_type' type='radio' name='type' value='Others'/>Others : <input id='other_text' type='text' name='other'/></pre></label>
                  </div></div>");

              }
            ?>
            <input class='input__submit' type="submit" value="ADD">
        </form>
      </div>
  </body>
</html>
