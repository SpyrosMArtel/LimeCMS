<?php include 'templates/admin/include/header.php' ?>
    <table class="console">
    <tr>
    <?php
      if (User::getById($_SESSION['userid']) -> access === "Administrator") {
         echo "
        <a href=\"/admin.php?action=newUser\"><img src=\"/templates/admin/images/userplus32.png\"/><span>Add User</span></a></td>
            ";
        }
    ?>
    </tr>
    </table>
    <?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

        <p>
           <table class="footable">
             <thead>
               <tr>
                <th data-hide="phone,tablet">First Name</th>
                <th data-hide="phone,tablet">Last Name</th>
                <th>Username</th>
                <th data-hide="phone,tablet" data-type="numeric" >Registered</th>
                <th width="10%" data-hide="phone,tablet" data-sort-ignore="true" >Actions</th>
               </tr>
             </thead>
           <tbody>
             <?php foreach ( $results['users'] as $user ) { ?>
 
              <tr>
               <td><?php echo $user -> firstName?></td>
               <td><?php echo $user -> lastName?></td>
               <td><?php echo $user -> userName?></td>
               <td data-value=<?php echo ($user -> registered);?>><?php echo date('j M Y', $user -> registered)?></td>
               <td>
                 <a style="float:left" href="?action=editUser&amp;userId=<?php echo $user -> userId?>">Edit</a>
                 <a style="float:left" href="?action=deleteUser&amp;userId=<?php echo $user -> userId?>">Delete</a>
               </td>
              </tr>
 
             <?php } ?>
           </tbody>
        </table>
      <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
<?php include 'templates/admin/include/footer.php' ?>