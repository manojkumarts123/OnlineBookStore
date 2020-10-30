<?php
  require_once "pdo.php";
  session_start();
  //unset($_SESSION['cid']);
  if(isset($_POST['book_id'])){
    if(!isset($_SESSION['cid'])) {
      $st = $pdo->prepare("INSERT INTO cart (customer_id) values (:cid)");
      $st->execute(array(':cid' => $_SESSION["user"]));
      $_SESSION['cid'] = $pdo->lastInsertId();
    }
    $st=$pdo->prepare("SELECT * FROM cart_list where cart_no=:cid and book_id=:bid");
    $st->execute(array(':cid' => $_SESSION['cid'], ':bid' => $_SESSION['bsearch']));
    if(!$row = $st->fetch(PDO::FETCH_ASSOC)){
      echo("inside fetch");
      $st = $pdo->prepare("INSERT INTO cart_list(cart_no, book_id,no_of_quantity) values(:cid, :bid, :q)");
      $st->execute(array(
        ':cid' => $_SESSION['cid'],
        ':bid' => $_SESSION['bsearch'],
        ':q' => $_POST['quantity'],
      ));

      $_SESSION['message'] = "Successlly book added to the cart";
      header('location: cart.php');
      return;
    }
    else{
      $_SESSION['message'] = "Book Already Added";
      header('location: cart.php');
      return;
    }
  }

?>

<html>
  <head>
    <title>Home</title>

    <link rel="stylesheet" href="indexstyle.css?<?php echo time(); ?>">
  </head>
  <body>

    <!---navigation---->


      <ul class="nav__list">
        <li class="nav__l_item " ><a href = 'index1.php' class="nav__link active">HOME</a></li>
        <li class="nav__l_item"><a href = 'about.php' class="nav__link">ABOUT</a></li>
        <li class="nav__l_item"><a href = 'contact.php' class="nav__link">CONTACT</a></li>
        <li class="nav__r_item"><a href = 'profile.php' class="nav__link">PROFILE</a></li>
        <li class="nav__r_item"><a href = 'cart.php' class="nav__link">CART</a></li>
      </ul>


<!---mid-section--->

    <div class='mid'>

      <!---Topic list-->

      <div class='mid__left'>

        <?php
        $st = $pdo->query("SELECT category FROM book");
        $type = array();
        $index=0; $flag=0;
        //finding sorted order of type
        while($row = $st->fetch(PDO::FETCH_ASSOC)){
          //echo "inside while";

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

        //printing
        echo ("<ul class='rnav'>");
        echo ("<h3>Categories</h3>");
        for($i=0; $i < sizeof($type); $i++) {
          echo ("<li class='rnav_item'><form method='post'>
                <input type='hidden' name='bname' value='".$type[$i]."'>
                <input type='submit' class='rnav_link' value='".$type[$i]."'>
                </form></li>");
        }
        echo "</ul>";

        ?>

      </div>

      <!---search bar--->
      <div class='mid__right'>
        <div class='mid__right_top'>
          <form method="post">
            <span padding = 10px >Search</span><input class='searchbox' type="text" name="search" value="" placeholder="Search">
            <input type="submit" class='search_submit' value="Submit">
          </form>
        </div>


    <!---book view---->
        <div class='mid__right_bottom'>
          <?php
          if(isset($_SESSION["message"])){
            echo("<p style='color:green;'>".$_SESSION["message"]."</p>");
            unset($_SESSION["message"]);
          }
          if(isset($_POST["search"])){
              $st =$pdo -> prepare("SELECT * FROM book WHERE name LIKE concat('%',:n,'%')");
              $st->execute(array(':n' => $_POST["search"]));
              $column = 0;
              $_SESSION['search'] = 'no';
              while ($row = $st->fetch(PDO::FETCH_ASSOC)){
                if($column == 0){
                  echo ("<table >");
                  echo ("<tr><th>Name</th>");
                  echo ("<th>Author</th>");
                  echo ("<th>Year</th>");
                  echo ("<th>Type</th>");
                  echo ("<th>Action</th></tr>");
                  $column =1;
                }
                $_SESSION['search'] = "yes";
                echo ("<tr><td>");
                echo(htmlentities($row['name']));
                echo("</td><td>");
                echo(htmlentities($row['author']));
                echo("</td><td>");
                echo(htmlentities($row['year']));
                echo("</td><td>");
                echo(htmlentities($row['category']));
                echo("</td><td>");
                echo("<form method='post'>
                      <input type='hidden' name='bsearch' value='".htmlentities($row['book_id'])."'>
                      <input type='submit' class='bv__link' value='View'></form>");
                echo("</td></tr>\n");
              }
             echo "</table>";
             if($_SESSION['search'] !== "yes"){
               echo "No search result";
             }
             unset($_SESSION['search']);

            }

          elseif(isset($_POST["bname"])){

              $st = $pdo->prepare("SELECT * FROM book WHERE category= :n");
              $st->execute(array(':n' => $_POST['bname']));

              echo ("<table>");
              echo ("<tr><th>Name</th>");
              echo ("<th>Author</th>");
              echo ("<th>Year</th>");
              echo ("<th>Type</th>");
              echo ("<th>Action</th></tr>");
              while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                echo ("<tr><td>");
                echo(htmlentities($row['name']));
                echo("</td><td>");
                echo(htmlentities($row['author']));
                echo("</td><td>");
                echo(htmlentities($row['year']));
                echo("</td><td>");
                echo(htmlentities($row['category']));
                echo("</td><td>");
                echo("<form method='post'>
                      <input type='hidden' name='bsearch' value='".htmlentities($row['book_id'])."'>
                      <input type='submit' class='bv__link' value='View'></form>");
                echo("</td></tr>\n");
              }
             echo "</table>";

          }

          elseif(isset($_POST["bsearch"])) {
            $st = $pdo -> prepare("SELECT * FROM book where book_id = :n");
            $st->execute(array(':n' => $_POST['bsearch']));
            ?>
            <div class='bsv'>
              <?php
                $row =$st->fetch(PDO::FETCH_ASSOC);
              ?>

                <div class='bsv__img'>
                  <img src= <?php echo($row['image']); ?> alt= <?php echo($row['name'])?> class ='bsearch_image'>
                </div>
                  <section class='bsv__details'>
                  <?php

                  echo("<p>".$row['description']."</p>");
                  echo("<div class='row'><div class='col-25'>");
                  echo("Book</div><div class='col-75'>:".$row['name']."</div></div>");
                  echo("<div class='row'><div class='col-25'>");
                  echo("Author</div><div class='col-75'>:".$row['author']."</div></div>");
                  echo("<div class='row'><div class='col-25'>");
                  echo("Year</div><div class='col-75'>:".$row['year']."</div></div>");
                  echo("<div class='row'><div class='col-25'>");
                  echo("Publisher</div><div class='col-75'>:".$row['publisher']."</div></div>");
                  echo("<div class='row'><div class='col-25'>");
                  echo("Category</div><div class='col-75'>:".$row['category']."</div></div>");
                  echo("<div class='row'><div class='col-25'>");
                  echo("Price</div><div class='col-75'>:".$row['price']."</div></div>");
                  echo("<form method='post'>
                  <input type='hidden' class=book_id name='book_id' value='".$row['book_id']."'>
                  <div class='row'><div class='col-25'><br>No of Quantity</div><div class='col-75'>:<input class='quantity_btn' type='number' name='quantity' value=1>
                  <input type='submit' class='button' value='Add to the Cart' onClick='setsessionid()'>
                  </div></div></form>");

                  $_SESSION['bsearch'] = $row['book_id'];
                ?>
              </section>
            </div>
            <div class='bsv__review'>
              <p>Reveiw</p>
            </div>
            <?php

          }

          else{
            $st = $pdo -> prepare("SELECT * FROM book");
            $st->execute();

            echo ("<table >");
            echo ("<tr><th>Name</th>");
            echo ("<th>Author</th>");
            echo ("<th>Year</th>");
            echo ("<th>Category</th>");
            echo ("<th>Action</th></tr>");

            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
              echo ("<tr><td>");
              echo(htmlentities($row['name']));
              echo("</td><td>");
              echo(htmlentities($row['author']));
              echo("</td><td>");
              echo(htmlentities($row['year']));
              echo("</td><td>");
              echo(htmlentities($row['category']));
              echo("</td><td>");
              echo("<form method='post'>
                    <input type='hidden' name='bsearch' value='".htmlentities($row['book_id'])."'>
                    <input type='submit' class='bv__link' value='View'></form>");
              echo("</td></tr>\n");
            }
           echo "</table>";
          }

        ?>
        </div>
      </div>
    </div>

    <!---footer--->



    </body>
    <script type='text/javascript'>
      $("a").click(function () {
          $("a").removeClass('active');
          $(this).addClass('active');
      });

      function setsessionid(){
        <?php $_SESSION['id'] = 3; ?>
      }
    </script>
</html>
