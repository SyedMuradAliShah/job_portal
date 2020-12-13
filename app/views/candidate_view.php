<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title; ?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  <link href="<?php echo base_url('public/css/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
  <style>
    .checked {
      color: orange;
    }


    /****** Style Star Rating Widget *****/

    .rating {
      border: none;
      float: right;
    }

    .rating>input {
      display: none;
    }

    .rating>label:before {
      margin: 5px;
      font-size: 1.25em;
      font-family: FontAwesome;
      display: inline-block;
      content: "\f005";
    }

    .rating>.half:before {
      content: "\f089";
      position: absolute;
    }

    .rating>label {
      color: #ddd;
      float: right;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .rating>input:checked~label,
    /* show gold star when clicked */
    .rating:not(:checked)>label:hover,
    /* hover current star */
    .rating:not(:checked)>label:hover~label {
      color: #FFD700;
    }

    /* hover previous stars in list */

    .rating>input:checked+label:hover,
    /* hover current star when changing rating */
    .rating>input:checked~label:hover,
    .rating>label:hover~input:checked~label,
    /* lighten current selection */
    .rating>input:checked~label:hover~label {
      color: #FFED85;
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
        <div class="col-md-10">
          <div id="msg"></div>
          <div class="row">
            <!--Company Info-->
            <div class="col-md-12">

              <div class="userinfoWrp">
                <div class="col-md-2 uploadPhoto">

                  <img src="<?php echo base_url('public/uploads/candidate/' . $photo); ?>" />

                </div>
                <div class="col-md-6">
                  <h1 class="username"><?php echo $row->first_name . ' ' . $row->last_name; ?></h1>
                  <div class="comtxt"><?php echo $latest_job_title; ?></div>
                  <div class="comtxt-blue"><?php echo $latest_job_company_name; ?></div>
                </div>
                <div class="col-md-4">
                  <div class="usercel"><?php echo $row->city; ?>, <?php echo $row->country; ?></div>
                  <?php if ($this->session->userdata('is_employer') == TRUE) : ?><a href="javascript:;" id="sendcandidatemsg" style="margin-top: 10px;" class="btn btn-success btn-sm"><span>Send Message</span></a>
                  <?php endif; ?>
                </div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="clear"></div>
          </div>

          <!--My CV-->
          <?php if ($rating) : ?>
            <div class="innerbox2">
              <div class="titlebar">
                <div class="row">
                  <div class="col-md-7"><b>User Rating</b></div>
                  <div class="col-md-5 text-right"><?php if ($rating->num_rows()) echo '<a href="javascript:;" id="updaterating" style="margin-top: 10px;color: white;" class="btn btn-success btn-sm"><span>Update Rating</span></a>';
                                                    else  echo '<a href="javascript:;" id="addrating" style="margin-top: 10px;color: white;" class="btn btn-success btn-sm"><span>Add Rating</span></a>'; ?></div>
                </div>
              </div>

              <!--Job Description-->
              <div class="companydescription">
                <div class="row">
                  <div class="col-md-12">
                    <?php if ($rating->num_rows()) : $rate = $rating->row(); ?>
                      <p style="margin-bottom: 14px;margin-left: 13px;border-bottom: 1px dashed#8e8d8d;"><?= $rate->message ?></p>
                      <div class="col-md-3">
                        Skills
                      </div>
                      <div class="col-md-3">
                        <?php if ($rate->skill_rating == 0) : ?>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->skill_rating == 1) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->skill_rating == 2) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->skill_rating == 3) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->skill_rating == 4) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->skill_rating == 5) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                        <?php endif ?>
                      </div>
                      <div class="col-md-3">
                        Technical
                      </div>
                      <div class="col-md-3">
                        <?php if ($rate->technical_rating == 0) : ?>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->technical_rating == 1) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->technical_rating == 2) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->technical_rating == 3) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->technical_rating == 4) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->technical_rating == 5) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                        <?php endif ?>
                      </div>
                      <div class="col-md-3">
                        Communication
                      </div>
                      <div class="col-md-3">
                        <?php if ($rate->communication_rating == 0) : ?>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->communication_rating == 1) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->communication_rating == 2) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->communication_rating == 3) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->communication_rating == 4) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->communication_rating == 5) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                        <?php endif ?>
                      </div>
                      <div class="col-md-3">
                        Personality
                      </div>
                      <div class="col-md-3">
                        <?php if ($rate->personality_rating == 0) : ?>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->personality_rating == 1) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->personality_rating == 2) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star "></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->personality_rating == 3) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->personality_rating == 4) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star"></span>
                        <?php endif ?>
                        <?php if ($rate->personality_rating == 5) : ?>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                          <span class="fa fa-star checked"></span>
                        <?php endif ?>
                      </div>
                    <?php else : ?>
                      No yet rated
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!--My CV-->
          <?php if ($result_resume) : ?>
            <div class="innerbox2">
              <div class="titlebar">
                <div class="row">
                  <div class="col-md-7"><b>My CV</b></div>
                  <div class="col-md-5 text-right"></div>
                </div>
              </div>

              <!--Job Description-->
              <div class="experiance">
                <ul class="myjobList">

                  <li class="row">
                    <?php
                    // print_r($userCV);
                    if ($userCV) :

                      $encrypt_id = $this->custom_encryption->encrypt_data($row->ID);
                    ?>
                      <div class="col-md-4 ">
                        <i class="fa fa-file-o">&nbsp;</i>
                        <a href="<?= base_url('candidate/get_cv/' . $encrypt_id); ?>" target="_blank">Online CV <small>(View CV Online)</small></a>
                      </div>
                      <div class="col-md-2"><?php echo date_formats($userCV->added_at, "d M, Y"); ?></div>
                    <?php endif; ?>
                    <?php if ($result_resume) :
                      foreach ($result_resume as $row_resume) :
                        $file_name = ($row_resume->is_uploaded_resume) ? $row_resume->file_name : '';
                        $file_array = explode('.', $file_name);
                        $file_array = array_reverse($file_array);
                        $icon_name = get_extension_name($file_array[0]);
                    ?>
                        <div class="col-md-4">
                          <i class="fa fa-file-<?php echo $icon_name; ?>-o">&nbsp;</i>
                          <?php if ($row_resume->is_uploaded_resume) : ?>
                            <a href="<?php echo base_url('resume/download/' . $row_resume->file_name); ?>"><?= $row_resume->file_name ?> <small>(Download to your computer)</small></a>
                          <?php else : ?>
                            <a href="#">My CV</a>
                          <?php endif; ?>
                        </div>
                        <div class="col-md-2"><?php echo date_formats($row_resume->dated, "d M, Y"); ?></div>
                      <?php endforeach;
                    else : ?>
                      <?php if (!$userCV) : ?>
                        No resume uploaded yet!
                      <?php endif; ?>
                    <?php endif; ?>
                  </li>
                </ul>
              </div>
            </div>
          <?php endif; ?>

          <!--Job Detail-->
          <?php if ($row_additional->summary) : ?>
            <div class="innerbox2">
              <div class="titlebar">
                <div class="row">
                  <div class="col-md-9"><b>Professional Summary</b></div>
                  <div class="col-md-3 text-right"></div>
                </div>
              </div>

              <!--Job Description-->
              <div class="companydescription">
                <div class="row">
                  <div class="col-md-12">
                    <p><?php echo ($row_additional->summary) ? character_limiter($row_additional->summary, 500) : ''; ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <!--Experiance-->
          <?php if ($result_experience) : ?>
            <div class="innerbox2">
              <div class="titlebar">
                <div class="row">
                  <div class="col-md-9"><b>Experience</b></div>
                  <div class="col-md-3 text-right"></div>
                </div>
              </div>

              <!--Job Description-->
              <div class="experiance">
                <?php
                if ($result_experience) :
                  foreach ($result_experience as $row_experience) :
                    $date_to = ($row_experience->end_date) ? date_formats($row_experience->end_date, 'M Y') : 'Present';
                ?>
                    <div class="row expbox">
                      <div class="col-md-12">
                        <h4><?php echo $row_experience->job_title; ?></h4>
                        <ul class="useradon">
                          <li class="company"><?php echo $row_experience->company_name; ?></li>
                          <?php if ($row_experience->city || $row_experience->country) : ?>
                            <li><?php echo ($row_experience->city) ? $row_experience->city . ', ' : ''; ?>, <?php echo $row_experience->country; ?></li>
                          <?php endif; ?>
                          <li><?php echo date_formats($row_experience->start_date, 'M Y'); ?> to <?php echo $date_to; ?></li>
                        </ul>
                        <div class="action"> </div>
                      </div>
                    </div>
                <?php endforeach;
                endif; ?>
                <div class="clear"></div>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($result_qualification) : ?>
            <!--Education-->
            <div class="innerbox2">
              <div class="titlebar">
                <div class="row">
                  <div class="col-md-9"><b>Education</b></div>
                  <div class="col-md-3 text-right"></div>
                </div>
              </div>

              <!--Job Description-->
              <div class="experiance">
                <?php
                if ($result_qualification) :
                  foreach ($result_qualification as $row_qualification) :
                ?>
                    <div class="row expbox">
                      <div class="col-md-12">
                        <h4><?php echo $row_qualification->institude; ?> <?php echo ($row_qualification->city) ? ', ' . $row_qualification->city : ''; ?></h4>
                        <ul class="useradon">
                          <li><?php echo $row_qualification->degree_title; ?>, <?php echo $row_qualification->completion_year; ?></li>
                          <li><?php echo $row_qualification->major; ?></li>
                        </ul>
                        <div class="action"></div>
                      </div>
                    </div>
                <?php endforeach;
                endif; ?>
                <div class="clear"></div>
              </div>
            </div>
          <?php endif; ?>

        </div>
        <!--/Job Detail-->

        <?php $this->load->view('common/right_ads'); ?>
      </div>
    </div>
    <div class="modal fade" id="send_msg">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Send a Message to <?php echo $row->first_name; ?></h4>
          </div>
          <div class="modal-body">
            <div id="emsg"></div>
            <div class="box-body">
              <div class="form-group">
                <label>Message</label>
                <textarea id="message" name="message" class="form-control" rows="12" placeholder=""><?php echo set_value('message'); ?></textarea>
                <?php echo form_error('message'); ?> </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="jsid" name="jsid" value="<?php echo $this->uri->segment(2); ?>" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" name="msg_submit" id="msg_submit" class="btn btn-primary">Send</button>
          </div>
        </div>
      </div>
    </div>
    <?php if ($rating) : ?>
      <?php if ($rating->num_rows()) : $rate = $rating->row(); ?>
        <div class="modal fade" id="update_rating">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Rating for <?php echo $row->first_name; ?></h4>
              </div>
              <div class="modal-body">
                <div id="emsgrating"></div>
                <div class="box-body">
                  <div class="form-group">
                    <label>Message</label>
                    <textarea id="messagerating" name="messagerating" class="form-control" placeholder="" rows="3"><?= strip_tags($rate->message) ?></textarea>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Skills</label>
                      <fieldset class="rating">
                        <input type="radio" id="skillstar5" name="skillrating" value="5" <?= ($rate->skill_rating == 5) ? "checked" : "" ?> /><label class="full" for="skillstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="skillstar4" name="skillrating" value="4" <?= ($rate->skill_rating == 4) ? "checked" : "" ?> /><label class="full" for="skillstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="skillstar3" name="skillrating" value="3" <?= ($rate->skill_rating == 3) ? "checked" : "" ?> /><label class="full" for="skillstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="skillstar2" name="skillrating" value="2" <?= ($rate->skill_rating == 2) ? "checked" : "" ?> /><label class="full" for="skillstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="skillstar1" name="skillrating" value="1" <?= ($rate->skill_rating == 1) ? "checked" : "" ?> /><label class="full" for="skillstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Technical</label>
                      <fieldset class="rating">
                        <input type="radio" id="techstar5" name="techrating" value="5" <?= ($rate->technical_rating == 5) ? "checked" : "" ?> /><label class="full" for="techstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="techstar4" name="techrating" value="4" <?= ($rate->technical_rating == 4) ? "checked" : "" ?> /><label class="full" for="techstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="techstar3" name="techrating" value="3" <?= ($rate->technical_rating == 3) ? "checked" : "" ?> /><label class="full" for="techstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="techstar2" name="techrating" value="2" <?= ($rate->technical_rating == 2) ? "checked" : "" ?> /><label class="full" for="techstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="techstar1" name="techrating" value="1" <?= ($rate->technical_rating == 1) ? "checked" : "" ?> /><label class="full" for="techstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Communication</label>
                      <fieldset class="rating">
                        <input type="radio" id="commstar5" name="commrating" value="5" <?= ($rate->communication_rating == 5) ? "checked" : "" ?> /><label class="full" for="commstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="commstar4" name="commrating" value="4" <?= ($rate->communication_rating == 4) ? "checked" : "" ?> /><label class="full" for="commstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="commstar3" name="commrating" value="3" <?= ($rate->communication_rating == 3) ? "checked" : "" ?> /><label class="full" for="commstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="commstar2" name="commrating" value="2" <?= ($rate->communication_rating == 2) ? "checked" : "" ?> /><label class="full" for="commstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="commstar1" name="commrating" value="1" <?= ($rate->communication_rating == 1) ? "checked" : "" ?> /><label class="full" for="commstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Personality</label>
                      <fieldset class="rating">
                        <input type="radio" id="personstar5" name="personrating" value="5" <?= ($rate->personality_rating == 5) ? "checked" : "" ?> /><label class="full" for="personstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="personstar4" name="personrating" value="4" <?= ($rate->personality_rating == 4) ? "checked" : "" ?> /><label class="full" for="personstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="personstar3" name="personrating" value="3" <?= ($rate->personality_rating == 3) ? "checked" : "" ?> /><label class="full" for="personstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="personstar2" name="personrating" value="2" <?= ($rate->personality_rating == 2) ? "checked" : "" ?> /><label class="full" for="personstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="personstar1" name="personrating" value="1" <?= ($rate->personality_rating == 1) ? "checked" : "" ?> /><label class="full" for="personstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" id="jsid" name="jsid" value="<?php echo $this->uri->segment(2); ?>" />
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" name="ratingUpdate" id="ratingUpdate" class="btn btn-primary">Update Rating</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php else : ?>

        <div class="modal fade" id="add_rating">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Rating for <?php echo $row->first_name; ?></h4>
              </div>
              <div class="modal-body">
                <div id="emsgrating"></div>
                <div class="box-body">
                  <div class="form-group">
                    <label>Message</label>
                    <textarea id="messagerating" name="messagerating" class="form-control" placeholder="" rows="3"></textarea>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Skills</label>
                      <fieldset class="rating">
                        <input type="radio" id="skillstar5" name="skillrating" value="5" /><label class="full" for="skillstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="skillstar4" name="skillrating" value="4" /><label class="full" for="skillstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="skillstar3" name="skillrating" value="3" /><label class="full" for="skillstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="skillstar2" name="skillrating" value="2" /><label class="full" for="skillstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="skillstar1" name="skillrating" value="1" /><label class="full" for="skillstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Technical</label>
                      <fieldset class="rating">
                        <input type="radio" id="techstar5" name="techrating" value="5" /><label class="full" for="techstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="techstar4" name="techrating" value="4" /><label class="full" for="techstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="techstar3" name="techrating" value="3" /><label class="full" for="techstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="techstar2" name="techrating" value="2" /><label class="full" for="techstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="techstar1" name="techrating" value="1" /><label class="full" for="techstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Communication</label>
                      <fieldset class="rating">
                        <input type="radio" id="commstar5" name="commrating" value="5" /><label class="full" for="commstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="commstar4" name="commrating" value="4" /><label class="full" for="commstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="commstar3" name="commrating" value="3" /><label class="full" for="commstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="commstar2" name="commrating" value="2" /><label class="full" for="commstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="commstar1" name="commrating" value="1" /><label class="full" for="commstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Personality</label>
                      <fieldset class="rating">
                        <input type="radio" id="personstar5" name="personrating" value="5" /><label class="full" for="personstar5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="personstar4" name="personrating" value="4" /><label class="full" for="personstar4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="personstar3" name="personrating" value="3" /><label class="full" for="personstar3" title="Normal - 3 stars"></label>
                        <input type="radio" id="personstar2" name="personrating" value="2" /><label class="full" for="personstar2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="personstar1" name="personrating" value="1" /><label class="full" for="personstar1" title="Very Bad - 1 star"></label>
                      </fieldset>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" name="ratingAdd" id="ratingAdd" class="btn btn-primary">Add Rating</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif ?>
    <?php endif ?>
    <?php $this->load->view('common/bottom_ads'); ?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <script type="text/javascript">
      $("#sendcandidatemsg").click(function() {
        $('#send_msg').modal('show');
      });

      $("#updaterating").click(function() {
        $('#update_rating').modal('show');
      });

      $("#addrating").click(function() {
        $('#add_rating').modal('show');
      });
    </script>
</body>

</html>