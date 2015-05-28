<?php include 'include/header.php' ?>
    <div id="content" class="one_edge_sh">
        <h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?>
        <?php if ( $results['category'] ) { ?>
              <?php echo "(" . htmlspecialchars( $results['category']->description ) . ")" ?>
        <?php } ?>
        <?php foreach ($results['articles'] as $Article) { ?>
        <?php if($Article -> status != "Draft") { ?>
        </h1>
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
                <span class="category">by <em><?php echo User::getById($Article -> userId) -> userName ?></em></a></span>
            <?php } ?>
           </h2>
            <div class="contentBody">
                <p><?php echo htmlspecialchars($Article->summary)?></p>
            </div>

       <?php } ?>
       <?php } ?>
    </div>

<?php include 'include/footer.php' ?>