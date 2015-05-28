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
         <th>Title</th>
         <th data-hide="phone,tablet">Summary</th>
         <th data-hide="phone,tablet" data-type="numeric" >Publication Date</th>
         <th width="10%" data-hide="phone,tablet" data-sort-ignore="true" >Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ( $results['articles'] as $article ) { ?>
        <tr>
          <td><?php echo $article->title?></td>
          <td><?php echo htmlspecialchars($article->summary)?></td>
          <td data-value=<?php echo ($article->publicationDate);?>><?php echo date('j M Y', $article->publicationDate)?></td>
          <td>
            <a style="float:left" href="?action=editArticle&amp;articleId=<?php echo $article->id?>">Edit</a>
            <a style="float:left" href="?action=deleteArticle&amp;articleId=<?php echo $article->id?>">Delete</a>
          </td>
        </tr>
       <?php } ?>
      </tbody>
    </table>
    <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
<?php include 'templates/admin/include/footer.php' ?>