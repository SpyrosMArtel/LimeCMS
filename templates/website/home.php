<?php include 'include/header.php' ?>
	<div id="content" class="one_edge_sh">
		<?php foreach ($results['articles'] as $Article) { ?>
        <?php if($Article -> status != "Draft") {?>
		   <h2>
			<div id="calendar">
				<div id="dateMonth">
					<span>&middot; <?php echo date('F', $Article->publicationDate)?> &middot;</span>
				</div>
				<div id="dateDay">
					<span><?php echo date('j', $Article->publicationDate)?></span>
				</div>
			</div>

	     	<a class="artLink" href=".?action=viewArticle&amp;articleId=<?php echo $Article->id?>">
	     		<?php echo htmlspecialchars( $Article->title )?>
	     	</a>

            <?php if ( $Article->categoryId ) { ?>
                <span class="category">by <em><?php echo User::getById($Article -> userId) -> userName ?></em>, in <a href=".?action=archive&amp;categoryId=<?php echo $Article->categoryId?>"><?php echo htmlspecialchars( $results['categories'][$Article -> categoryId] -> name )?></a></span>
            <?php } ?>
		   </h2>
			<div class="contentBody">
				<p><?php echo htmlspecialchars($Article->summary)?></p>
	    	</div>

	   <?php } ?>
	   <?php } ?>
    </div>

<?php include 'include/footer.php' ?>