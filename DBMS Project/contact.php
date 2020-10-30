<?php
  require_once "pdo.php";
  session_start();
?>

<head>
  <title>Contact</title>

  <link rel="stylesheet" href="indexstyle.css">
</head>
<body>

  <!---navigation---->


    <ul class="nav__list">
      <li class="nav__l_item"><a href = 'index1.php' class="nav__link">HOME</a></li>
      <li class="nav__l_item"><a href = 'about.php' class="nav__link">ABOUT</a></li>
      <li class="nav__l_item"><a href = 'contact.php' class="nav__link">CONTACT</a></li>
      <li class="nav__r_item"><a href = 'profile.php' class="nav__link">PROFILE</a></li>
      <li class="nav__r_item"><a href = 'cart.php' class="nav__link">CART</a></li>
    </ul>

  <!---About--->
  <div class='mid mid__section'>
    <h1 class='mid__title'>Contact Online book store</h1>
    <div class="mid__text">
    <p>The website contains login page where users can login to their account. If user don’t have account,
      they can create one. For creating account, one need to register using registration page. After logged in,
      if user is administrator, he/she can view his books, add no of quantities available, modify and delete particular book details.
      If user is customer, he/she can search for book with the help of title and categories. The book appears of
      the screen according to the search. It also recommends books to the use based on the search.
      Customer can view book details but cannot modify or delete it. If he/she want to buy particular book, it can be
      added to cart by clicking add to the cart option. Once customer is done with selecting books he/she wants to buy and added to the cart,
      he/she can procced to the payment. After the payment is done, order is placed and customer can trace their orders.</p>
    </div>
  </div>
</body>
</html>
