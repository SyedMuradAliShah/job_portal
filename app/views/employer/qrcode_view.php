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

    .div-blur {
      -webkit-filter: blur(9px);
      -moz-filter: blur(9px);
      -o-filter: blur(9px);
      -ms-filter: blur(9px);
      filter: blur(9px);
      width: 100px;
      height: 100px;
      background-color: #ccc;
    }

    .div-text-overly {
      position: absolute;
      top: 40%;
      z-index: 999;
      color: #fff;
      font-size: 30px;
      right: -18%;
      font-weight: bolder;
    }

    #qrcode {
      padding-top: 20px;
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
              <div class="col-md-3 col-md-offset-3" style="width: 350px;height: 400px;">
                <p id="qrcode"></p>
                <p class="div-text-overly" style="display:none">Loading CV</p>
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
    <script src="<?php echo base_url('public/js/qrcode.js'); ?>"></script>
    <!-- <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/130527/qrcode.js"></script> -->
    <script>
      $(window).load(function() {
        settingBarCodeValue();
        setInterval(function() {
          settingBarCodeValue();
        }, 2000);
        setInterval(function() {
          get_qrdata();
        }, 1000);
      });

      function get_qrdata() {
        $.get("<?= base_url('api/qrcode/get_qrdata/' . $this->uri->segment(3)) ?>", function(data, status) {
          // console.log("Data: " + data + "\nStatus: " + status);
          if (data.error == false) {
            $(".div-text-overly").show();
            $("qrcode").addClass("div-blur");
            // console.log(data);
            if (data.response.redirect) {
              setInterval(function() {
                window.location.replace(data.response.redirect);
              }, 400);
            }
          }
        });
      }

      function settingBarCodeValue() {
        var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        generateQR('<?= $this->custom_encryption->encrypt_data($this->uri->segment(3)) ?>-' + time);
      }

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
    </script>
</body>

</html>