<?php include 'templates/admin/include/header.php' ?>
    <?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>
    <table class="footable">
      <thead>
         <tr>
         <th>Name</th>
         <th data-hide="phone,tablet">Description</th>
         <th width="10%" data-hide="phone,tablet" data-sort-ignore="true">Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ( $results['categories'] as $category ) { ?>
        <tr>
          <td><?php echo $category->name?></td>
          <td><?php echo htmlspecialchars($category->description)?></td>
          <td>
            <a style="float:left" href="?action=editCategory&amp;categoryId=<?php echo $category->cat_id?>">Edit</a>
            <a style="float:left" href="?action=deleteCategory&amp;categoryId=<?php echo $category->cat_id?>">Delete</a>
          </td>
        </tr>
       <?php } ?>
      </tbody>
    </table>
    <p><?php echo $results['totalRows']?> category<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
<?php include 'templates/admin/include/footer.php' ?>