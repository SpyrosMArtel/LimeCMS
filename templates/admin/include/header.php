<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
   <link href='http://fonts.googleapis.com/css?family=PT+Sans|Roboto' rel='stylesheet' type='text/css'>
   <link href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
   <style type="text/css">@import url("/templates/admin/theme/main.css");</style>
   <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
   <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
   <script type="text/javascript">
    $(function() {
        var pull        = $('#resp-nav');
            menu        = $('div ul');
            menuHeight  = menu.height();

            $(pull).on('click', function(e) {
                e.preventDefault();
                menu.slideToggle();
            });

    $(window).resize(function(){
            var w = $(window).width();
            if(w > 320 && menu.is(':hidden')) {
                menu.removeAttr('style');
            } else {
                menu.addAttr('style');
            }
        });
    });
  </script>
  <script type="text/javascript">
        $(function(){
            $("#gallery").tabs();
        });
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#gallery").tabs({ disabled: [1]});
    });
  </script>
	 <?php
		if ((htmlspecialchars($results['pageTitle']) === 'New Article')
		|| (htmlspecialchars($results['pageTitle']) ==='Edit Article')) {
			echo "
			 <script type=\"text/javascript\" src=\"/templates/admin/plugins/markitup/jquery.markitup.js\"></script>
			 <script type=\"text/javascript\" src=\"/templates/admin/plugins/markitup/sets/html/set.js\"></script>
			 <script type=\"text/javascript\">
			   $(document).ready(function() {
			   $('#htmlmarkitup').markItUp(myHtmlSettings);
			 });
       		</script>";
		}
		if ((htmlspecialchars($results['pageTitle']) === 'All Articles')
        || (htmlspecialchars($results['pageTitle']) === 'User Management'))  {
			echo "
	         <script type=\"text/javascript\" src=\"/templates/admin/plugins/FooTable-2/js/footable.js\"></script>
		     <script src=\"/templates/admin/plugins/FooTable-2/js/footable.sort.js\" type=\"text/javascript\"></script>
             <script type=\"text/javascript\">
			   $(function() {
			      $('.footable').footable();
			   });
        	  </script>
        	";
		}
        if (htmlspecialchars($results['pageTitle']) === 'Edit User') {
            echo "
             <script type=\"text/javascript\">
               $(function () {
                $('fieldset.other').hide();
                $('input[name=\"changePass\"]').click(function () {
                  if (this.checked) {
                     $('fieldset.other').show();
                  } else {
                     $('#oldPassword').val('');
                     $('#newPassword').val('');
                     $('fieldset.other').hide();
                  }
                });
               });
             </script>
             ";
       }
     ?>
    <title><?php echo htmlspecialchars($results['pageTitle']) ?></title>
  </head>
  <body>
    <div id="pageWrapper">
      <div class="navbar-left">
       <img class="logo" src="/templates/admin/images/Limes.png" />
        <ul>
          <li><a href="/admin.php"><img src="/templates/admin/images/home32.png"/>Home</a></li>
          <li <?php echo $results['pageTitle'] === 'New Article' ? 'class="active"' : '' ?>><a href="/admin.php?action=newArticle"><img src="/templates/admin/images/linedpaper32.png"/>New Article</a></li>
          <li><a href="/admin.php?action=listArticles"><img src="/templates/admin/images/paperpencil32.png"/>Articles</a></li>
          <li><a href="/admin.php?action=listCategories"><img src="/templates/admin/images/article32.png"/>Categories</a></li>
          <li><a href="#"><img src=""/>Gallery</a></li>
          <li><a href="/admin.php?action=manageUsers"><img src="/templates/admin/images/users32.png"/>Users</a></li>
          <li><a href="/admin.php?action=logout"><img src="/templates/admin/images/unlock32.png"/>Logout</a></li>
        </ul>
       <div id="nav-mobile">
        <a id="resp-nav" href="#">Menu</a>
       </div>
      </div>
 <div id="content">