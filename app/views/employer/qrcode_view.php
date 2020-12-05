<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title; ?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  <style type="text/css">
    .formwraper p {
      font-size: 13px;
    }
  </style>
</head>

<body>
  <?php $this->load->view('common/after_body_open'); ?>
  <div class="siteWraper">
    <!--Header-->
    <?php $this->load->view('common/header'); ?>
    <!--/Header-->
    <div class="container detailinfo">
      <div class="row">
        <div class="col-md-3">
          <div class="dashiconwrp">
            <?php $this->load->view('employer/common/employer_menu'); ?>
          </div>
        </div>

        <div class="col-md-9">
          <?php echo $this->session->flashdata('msg'); ?>
          <?php
          $job_title = word_limiter(strip_tags(str_replace('-', ' ', $row_posted_job->job_title)), 7);
          ?>
          <!--Job Application-->
          <div class="formwraper">
            <div class="titlehead">
              <div class="row">
                <div class="col-md-12"><b>SCAN QR</b> for <?php echo humanize($job_title); ?></div>
              </div>
            </div>

            <!--Job Description-->
            <div class="row ">
              <div class="col-md-3 col-md-offset-3">
                <p id="qrcode"></p>
              </div>
            </div>
          </div>
        </div>
        <!--/Job Detail-->
      </div>
    </div>
    <?php $this->load->view('common/bottom_ads'); ?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('employer/common/employers_popup_forms'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <script src="<?php echo base_url('public/js/validate_employer.js'); ?>" type="text/javascript"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/130527/qrcode.js"></script>
    <script>
      $(window).load(function() {
        generateQR('text');
      });

      function generateQR(string) {

        $('#qrcode').empty();
        // Set Size to Match User Input
        $('#qrcode').css({
          'width': 350,
          'height': 350
        })

        // Generate and Output QR Code
        $('#qrcode').qrcode({
          width: 350,
          height: 350,
          text: string
        });
      }

      // $('.generate-qr-code').on('click', function() {
      //   $(this).attr("data-qr-value");
      //   // Clear Previous QR Code
      // });
    </script>
</body>

</html>