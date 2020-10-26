<?php
  require_once "pdo.php";
  session_start();

  if(isset($_POST['addstock'])) {
    $st = $pdo->prepare("SELECT stocks FROM book WHERE book_id=:n");
    $st->execute(array(':n' => $_SESSION['bsearch']));
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $st1 = $pdo->prepare("UPDATE book SET stocks= :s WHERE book_id=:n");
    $st1->execute(array(
      ':s' => $_POST['addstock']+ $row['stocks'],
      ':n' => $_SESSION['bsearch']));
    $_SESSION['message'] = 'Stocks has been added sucessfully';
    header('location: index2.php');
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
      <div class='mid'>
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
          <p>Enter No of stocks to be added: <input type='text' name='addstock' value=""></p>
          <p><input type='submit' value='ADD STOCKS'></p>
        </form>
      </div>
  </body>
</html>
