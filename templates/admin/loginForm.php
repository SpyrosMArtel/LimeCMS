<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
   <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
   <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
   <style type="text/css">
	@import url("/templates/admin/theme/main.css");
</style>
    <title><?php echo htmlspecialchars($results['pageTitle']) ?></title>
  </head>
  <body>
 <div class="loginContainer">
  <div class="login">
   <h1>Login</h1>
    <form action="admin.php?action=login" method="post">
     <input type="hidden" name="login" value="true" />
	  <?php if ( isset( $results['errorMessage'] ) ) { ?>
      	<div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	  <?php } ?>
     <p>
      <input class="loginInput" type="text" name="username" placeholder="Your admin username" required autofocus maxlength="20" />
     </p>
     <p>
      <input class="loginInput" type="password" name="password" placeholder="Your admin password" required maxlength="20" />
     </p>

     <p class="submit">
      <input class="submit-button" type="submit" name="login" value="Login" />
     </p>
    </form>
  </div>
  <p class="return"><a href="/">Back to homepage</a></p>
 </div>
    </body>
</html>