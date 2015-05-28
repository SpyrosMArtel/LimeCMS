<?php include 'templates/admin/include/header.php' ?>
   <div class="af-container width-full center corners-all">
    <div class="af-header"><h3><?php echo htmlspecialchars($results['formName']) ?></h3></div>
     <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
      <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>"/>
      <input type="hidden" name="userId" value="<?php echo $_SESSION['userid'] ?>"/>

      <fieldset class="af-fieldset corners-all">
       <label class="af-label" for="title">Article Title</label>
       <input class="af-input width-full corners-all" type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->title )?>" />
      </fieldset>

      <fieldset class="af-fieldset corners-all">
       <label class="af-label" for="summary">Article Summary:</label>
       <textarea class="af-textarea width-full corners-all" rows="3" style="resize: vertical;" name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary )?></textarea>
      </fieldset>

      <fieldset class="af-fieldset corners-all">
       <label class="af-label" for="content">Content</label>
       <textarea id="htmlmarkitup" class="width-full" rows="8" style="resize: vertical;" name="content" id="articleContent" placeholder="The HTML content of the article" required maxlength="100000"><?php echo htmlspecialchars( $results['article']->content )?></textarea>
      </fieldset>

      <fieldset class="af-fieldset corners-all">
       <div class="left">
       <label class="af-label" for="categoryId">Category</label>
       <select class="af-select" style="margin-right:30px;width:200px;" name="categoryId">
        <option value="0"<?php echo !$results['article'] -> categoryId ? " selected" : ""?>>(none)</option>
        <?php foreach ( $results['categories'] as $category ) { ?>
<option value="<?php echo $category -> cat_id ?>"<?php echo ( $category -> cat_id == $results['article'] -> categoryId ) ? " selected" : "" ?>><?php echo htmlspecialchars( $category -> name )?></option>
        <?php } ?>
       </select>
       </div>
       <div class="right">
        <label class="af-label" for="publicationDate">Publication Date</label>
        <input class="af-input width-full corners-all" type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->publicationDate ? date( "Y-m-d", $results['article']->publicationDate ) : "" ?>"/>
        <label class="af-label">Status</label>
        <select class="af-select" style="width:200px;" name="status">
         <option value="Draft"<?php echo ($results['article'] -> status === "Draft") ? " selected" : "" ?>>Draft</option>
         <option value="Published"<?php echo ($results['article'] -> status === "Published") ? " selected" : "" ?>>Published</option>
        </select>
       </div>
      </fieldset>

      <fieldset class="af-submit corners-all">
       <div class="right">
        <input class="af-input" type="submit" name="saveChanges" value="Submit" />
        <input type="submit" formnovalidate name="cancel" value="Cancel" />
       </div>
      </fieldset>
   </form>
 </div>
<?php include 'templates/admin/include/footer.php' ?>