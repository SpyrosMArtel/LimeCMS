<?php include 'include/header.php' ?>

	<div id="content" class="one_edge_sh">

		   <h2>
			<div id="calendar">
				<div id="dateMonth">
					<span>&middot; <?php echo date('F', $results['article']->publicationDate)?> &middot;</span>
				</div>
				<div id="dateDay">
					<span><?php echo date('j', $results['article']->publicationDate)?></span>
				</div>
			</div>
			<?php echo htmlspecialchars($results['article']->title) ?>
            <?php if ( $results['category'] ) { ?>
                <span class="category">by <em><?php echo User::getById($results['article'] -> userId) -> userName ?></em> , in <a href="./?action=archive&amp;categoryId=<?php echo $results['category'] -> cat_id ?>"><?php echo htmlspecialchars( $results['category'] -> name ) ?></a></span>
            <?php } ?>
		   </h2>
		<div class="contentBody">
			<?php echo $results['article']->content ?>
		</div>

		<p class="return"><a href="./"> &larr; Return to homepage</a></p>

	  </div>

<?php include 'include/footer.php' ?>