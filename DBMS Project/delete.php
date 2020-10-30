<?php
  require_once "pdo.php";
  session_start();

  if($_POST['delete'] == 'YES') {
    $st = $pdo->prepare("DELETE FROM book WHERE book_id=:n");
    $st->execute(array(':n' => $_SESSION['bsearch']));
    $_SESSION['message'] = 'Book has been deleted sucessfully';
    header('location: index2.php');
  }
  else{
    echo("<p style='color:red'>Please enter YES to delete</p>");
  }
?>

<html>
  <head>
    <title>OBS:ADD STOCK</title>

    <link rel="stylesheet" href="indexstyle.css?<?php echo time(); ?>">
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
      <?php
        if(isset($_SESSION["bsearch"])) {
          $st = $pdo->prepare("SELECT * FROM book WHERE book_id=:n");
          $st->execute(array(':n' => $_SESSION['bsearch']));
          $row = $st->fetch(PDO::FETCH_ASSOC);
          echo("<p>Name:".$row['name']."</p>
          <p>Author: ".$row['author']."</p>
          <p>year: ".$row['year']."</p>
          <p>type: ".$row['type']."</p>
          <p>Stock: ".$row['stocks']."</p>");
        }
      ?>
      <form method='post'>
        <p>Delete this book</p><p style='font: bold'>CONFIRM BY TYPING YES<input type='text' name='delete' value=""></p>
        <p><input type='submit' value='delete'><a href='index2.php'>Go back</a></p>
      </form>
  </body>
</html>
