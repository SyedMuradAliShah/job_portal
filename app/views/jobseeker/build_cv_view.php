<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title; ?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  <link href="<?php echo base_url('public/css/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
  <style>
    body,
    html {
      height: 100%;
      padding: 0;
      margin: 0;
      /* overflow: hidden */
    }

    #editor {
      width: 100%;
      height: 100%
    }

    .template:hover {
      cursor: pointer;
    }

    #overlay {
      position: fixed;
      display: none;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 2;
      cursor: pointer;
    }

    #text {
      position: absolute;
      top: 50%;
      left: 50%;
      font-size: 50px;
      color: white;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
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

        <!--Job Detail-->
        <div class="innerbox2">
          <div class="titlebar">
            <div class="row">
              <div class="col-md-9"><b>Templates</b></div>
            </div>
          </div>

          <!--Job Description-->
          <div class="companydescription">
            <div class="row">
              <div class="col-md-12">
                <div id="templates">
                  <?php if ($defaultCV) : ?>
                    <?php foreach ($defaultCV->result() as $cv) : ?>
                      <a class="template" onClick="loadTemplate(<?= $cv->cv_id ?>)">
                        <img style="width: 220px;" src="https://api.unlayer.com/v1/editor/<?= $cv->project_id ?>/templates/<?= $cv->cv_id ?>/thumbnail?t=<?= date('dmY000000') ?>">
                      </a>
                    <?php endforeach ?>
                  <?php endif ?>
                </div>
              </div>
            </div>
          </div>
          <?php
          if ($user_cv->num_rows()) {
            $cv = $user_cv->row();
            $default_cv = $cv->json;
            $cv_json = true;
          } else {
            $cv_json = false;
            $default_cv = $defaultCV->row()->cv_id;
          }
          ?>
          <div class="titlebar">
            <div class="row">
              <div class="col-md-9"><b>Editor</b></div>
              <div class="col-md-3 float-right"> <?php if ($cv_json) : ?><button class="btn btn-warning" onClick="loadCV()">Load Saved CV</button> - <?php endif ?><button class="btn btn-primary" onClick="saveCV()">Save CV</button></div>
            </div>
          </div>

          <!--Job Description-->
          <div class="companydescription">
            <div class="row">
              <div class="col-md-12" style="height: 100vh;">
                <div id="overlay">
                  <div id="text">Loading..</div>
                </div>
                <div id="editor"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!--/Job Detail-->
    </div>
  </div>
  <script src="//editor.unlayer.com/embed.js"></script>
  <script>
    var editor = unlayer.createEditor({
      id: 'editor',
      projectId: 6273,
      tools: {
        menu: {
          enabled: false
        },
        button: {
          enabled: false
        },
        form: {
          enabled: false
        },
      },
      // appearance: {
      //   theme: 'light'
      // },
    });


    function saveCV() {
      editor.exportHtml(function(data) {
        var html = data.html; // design html
        // alert(json);
        editor.saveDesign(function(design) {
          console.log('design', design);
          $.ajax({
            url: "<?= base_url('/jobseeker/build_cv/save_cv'); ?>",
            type: 'POST',
            // dataType: 'json',
            // contentType: 'application/json',
            data: {
              json: JSON.stringify(design),
              html: html
            },
            success: function(data) {
              if (data.error == false) {
                alert('Your cv has been saved successfully');
              } else {
                alert('Unable to save your CV');
              }
            },
            error: function(data) {
              location.reload();
            }
          });
          // Save the json, or html here
        })
      });
    }

    <?php if ($cv_json) : ?>
      loadCV();

      function loadCV() {
        var design = <?= $default_cv ?>;
        editor.loadDesign(design);
        // editor.exportHtml(function(data) {
        //   var json = data.design; // design json
        //   var html = data.html; // design html
        //   alert(json);
        //   // Save the json, or html here
        // })
      }
    <?php else : ?>

      loadTemplate(<?= $default_cv ?>);
    <?php endif ?>

    function loadTemplate(id) {
      editor.loadTemplate(id);
    }
    // editor.addEventListener('design:updated', function(updates) {
    // Design is updated by the user

    // editor.exportHtml(function(data) {
    //   var json = data.design; // design json
    //   var html = data.html; // design html
    //   alert(json);
    //   // Save the json, or html here
    // })
    // })
  </script>
  <?php $this->load->view('common/bottom_ads'); ?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <!-- Profile Popups -->
  <!-- Profile Popups -->
  <?php $this->load->view('jobseeker/common/jobseekes_popup_forms'); ?>
  <?php $this->load->view('common/before_body_close'); ?>
  <script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/validate_jobseeker.js'); ?>" type="text/javascript"></script>
</body>

</html>