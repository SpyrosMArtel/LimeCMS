<?php include 'templates/admin/include/header.php' ?>
<!--<script src="http://malsup.github.com/jquery.form.js"></script> -->
  <div id="gallery">
   <ul>
    <li><a href="#browse">Browse</a></li>
    <li><a href="#edit">Edit image</a></li>
   </ul>
   <div id="browse">
    <div class="upload-container">
     <form enctype="multipart/form-data" action="" method="POST">
       <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
        Upload a file: <input name='image' type='file' />
       <input class="gallery-input" type="submit" name="saveChanges" value="Submit" />
     </form>
     <div>
         <div id='gallery-filename'></div>
         <div id='gallery-progressbar'></div>
     </div>
     <div id='gallery-images'></div>
    </div>
   </div>
   <div id="edit">EDIT IMAGE</div>
  </div>
<?php include 'templates/admin/include/footer.php' ?>