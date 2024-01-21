<?php include_once('includes/header.php') ?>
<?php include_once('includes/classes/VideoDetailsFormProvider.php') ?>

    <div class="column">
        <?php
           
          $upload = new VideoDetailsFormProvider($con);
          echo $upload->createUploadForm();
           
        ?>
    </div>

    <script>
        $("form").submit(function() {
            $("#loadingModal").modal('show');
        })
    </script>

<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyword="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Please wait. This take for a while...
        <img src="assets/images/icons//loading-spinner.gif" alt="">
      </div>
    </div>
  </div>
</div>
        
<?php include_once('includes/footer.php') ?>