<?php include 'templates/admin/include/header.php' ?>
 <div id="uef-container">
  <article class="uef-form width_full">
   <header><h3>Edit user details</h3></header>
    <div class="uef_content">
     <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
      <input type="hidden" name="userId" value="<?php echo $results['user'] -> userId ?>"/>
      <input type="hidden" name="registered" value="<?php echo ($results['user'] -> registered) ?>"/>

      <?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
      <?php } ?>
      <fieldset class="uef-fieldset">
       <label class="uef-label" for="username">Username</label>
       <input class="uef-input" name="username" type="text" id="username" value="<?php echo ($results['user'] -> userName) ?>"/>

       <label class="uef-label" for="firstname">First Name</label>
       <input class="uef-input" name="firstname" type="text" id="firstname" value="<?php echo ($results['user'] -> firstName) ?>"/>

       <label class="uef-label" for="lastname">Last Name</label>
       <input class="uef-input" name="lastname" type="text" id="lastname" value="<?php echo ($results['user'] -> lastName) ?>"/>

       <label class="uef-label" for="changePass">Change Password?<input type="checkbox" name="changePass" id="changePass" value="Yes" /></label>
      </fieldset>

      <fieldset class="uef-fieldset other">
       <label class="uef-label" for="oldPassword">Type Current Password</label>
       <input class="uef-input" name="oldPassword" type="password" id="oldPassword">

       <label class="uef-label" for="newPassword">Type New Password</label>
       <input class="uef-input" name="passcode" type="password" id="newPassword">
      </fieldset>

      <fieldset class="uef-fieldset" style="width:46%; float:left; margin-right: 3%;">
       <label class="uef-label">Role</label>
       <select class="uef-selrole" style="width:90%;" name="role">
        <option value="User"<?php echo ($results['user'] -> access === "User") ? " selected" : "" ?>>User</option>
        <option value"Administrator"<?php echo ($results['user'] -> access === "Administrator") ? " selected" : "" ?>>Administrator</option>
        </select>
      </fieldset>

      <fieldset class="uef-fieldset" style="width:46%; float:left; margin-right: 3%;">
       <label class="uef-label">User Since:</label>
       <label class="uef-label"><em><?php echo ($results['user'] -> userSince() != 0 ? $results['user'] -> userSince() : "") ?></em></label>
      </fieldset>
      <div class="clear"></div>
    </div>

    <footer class="uef-footer">
     <div class="uef-subcon">
      <input class="uef-inputsubart" type="submit" name="saveChanges" value="Save">
      <input class="uef-inputsubcan" type="submit" formnovalidate name="cancel" value="Cancel" />
     </div>
    </footer>
     </form>
  </article>
 </div>
<?php include 'templates/admin/include/footer.php' ?>