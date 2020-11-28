<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title; ?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  <style>
    .inbox_people {
      background: #f8f8f8 none repeat scroll 0 0;
      float: left;
      overflow: hidden;
      width: 40%;
      border-right: 1px solid #c4c4c4;
    }

    .inbox_msg {
      border: 1px solid #c4c4c4;
      clear: both;
      overflow: hidden;
    }

    .top_spac {
      margin: 20px 0 0;
    }


    .recent_heading {
      float: left;
      width: 40%;
    }

    .srch_bar {
      display: inline-block;
      text-align: right;
      width: 60%;
      padding:
    }

    .headind_srch {
      padding: 10px 29px 10px 20px;
      overflow: hidden;
      border-bottom: 1px solid #c4c4c4;
    }

    .recent_heading h4 {
      color: #05728f;
      font-size: 21px;
      margin: auto;
    }

    .srch_bar input {
      border: 1px solid #cdcdcd;
      border-width: 0 0 1px 0;
      width: 80%;
      padding: 2px 0 4px 6px;
      background: none;
    }

    .srch_bar .input-group-addon button {
      background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
      border: medium none;
      padding: 0;
      color: #707070;
      font-size: 18px;
    }

    .srch_bar .input-group-addon {
      margin: 0 0 0 -27px;
    }

    .chat_ib h5 {
      font-size: 15px;
      color: #464646;
      margin: 0 0 8px 0;
    }

    .chat_ib h5 span {
      font-size: 13px;
      float: right;
    }

    .chat_ib p {
      font-size: 14px;
      color: #989898;
      margin: auto
    }

    .chat_img {
      float: left;
      width: 11%;
    }

    .chat_ib {
      float: left;
      padding: 0 0 0 15px;
      width: 88%;
    }

    .chat_people {
      overflow: hidden;
      clear: both;
    }

    .chat_list {
      border-bottom: 1px solid #c4c4c4;
      margin: 0;
      padding: 18px 16px 10px;
    }

    .chat_list:hover {
      border-bottom: 1px solid #202020;
      background: white;
      margin: 0;
      padding: 18px 16px 10px;
      cursor: pointer;
    }

    .inbox_chat {
      height: 550px;
      overflow-y: scroll;
    }

    .active_chat {
      background: #ebebeb;
    }

    .incoming_msg_img {
      display: inline-block;
      width: 6%;
    }

    .received_msg {
      display: inline-block;
      padding: 0 0 0 10px;
      vertical-align: top;
      width: 92%;
    }

    .received_withd_msg p {
      background: #ebebeb none repeat scroll 0 0;
      border-radius: 3px;
      color: #646464;
      font-size: 14px;
      margin: 0;
      padding: 5px 10px 5px 12px;
      width: 100%;
    }

    .time_date {
      color: #747474;
      display: block;
      font-size: 12px;
      margin: 8px 0 0;
    }

    .received_withd_msg {
      width: 57%;
    }

    .mesgs {
      float: left;
      padding: 30px 15px 0 25px;
      width: 60%;
    }

    .sent_msg p {
      background: #05728f none repeat scroll 0 0;
      border-radius: 3px;
      font-size: 14px;
      margin: 0;
      color: #fff;
      padding: 5px 10px 5px 12px;
      width: 100%;
    }

    .outgoing_msg {
      overflow: hidden;
      margin: 26px 0 26px;
    }

    .sent_msg {
      float: right;
      width: 46%;
    }

    .input_msg_write input {
      background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
      border: medium none;
      color: #4c4c4c;
      font-size: 15px;
      min-height: 48px;
      width: 100%;
    }

    .type_msg {
      border-top: 1px solid #c4c4c4;
      position: relative;
    }

    .msg_send_btn {
      background: #05728f none repeat scroll 0 0;
      border: medium none;
      border-radius: 50%;
      color: #fff;
      cursor: pointer;
      font-size: 17px;
      height: 33px;
      position: absolute;
      right: 0;
      top: 11px;
      width: 33px;
    }

    .messaging {
      padding: 0 0 50px 0;
    }

    .msg_history {
      height: 516px;
      overflow-y: auto;
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
          <!--Account info-->
          <div class="formwraper">
            <div class="titlehead"></div>
            <div class="formint">

              <div class="messaging">
                <div class="inbox_msg">
                  <div class="inbox_people">
                    <div class="headind_srch">
                      <div class="recent_heading">
                        <h4>Recent</h4>
                      </div>
                    </div>
                    <div class="inbox_chat scroll">
                      <?php if (@$one2one_users) : ?>
                        <?php foreach ($one2one_users as $chat_user) :  ?>
                          <div class="chat_list <?= ($chat_user['jobseeker_id'] == $this->uri->segment(3)) ? 'active_chat' : '' ?>" onclick="location.href = '<?= base_url('employer/chat/') . $chat_user['jobseeker_id'] ?>';">
                            <div class="chat_people">
                              <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="<?= $chat_user['name'] ?>"> </div>
                              <div class="chat_ib">
                                <h5><?= $chat_user['name'] ?> <span class="chat_date"><?= date("M d", strtotime($chat_user['date'])) ?></span></h5>
                                <p><?= $chat_user['last_message'] ?></p>
                              </div>
                            </div>
                          </div>
                        <?php endforeach ?>
                      <?php else : ?>
                        <div class="chat_list">
                          <div class="chat_people">
                            <div class="chat_ib" style="text-align: center;">
                              <p>No user.</p>
                            </div>
                          </div>
                        </div>
                      <?php endif ?>
                    </div>
                  </div>
                  <div class="mesgs">
                    <div class="msg_history" style="<?= (@$one2one_chats) ? '' : 'text-align: center;' ?>">
                      <?php $i = 0;
                      if (@$one2one_chats) : ?>
                        <?php foreach ($one2one_chats as $one2one_chat) :  ?>
                          <?php if ($one2one_chat['sent_from'] == $this->uri->segment(1)) : ?>
                            <div class="outgoing_msg">
                              <div class="sent_msg">
                                <p><?= $one2one_chat['message'] ?></p>
                                <span class="time_date"><?= date("h:i A | M d", strtotime($one2one_chat['sent_on'])) ?></span>
                              </div>
                            </div>
                          <?php else : ?>
                            <div class="incoming_msg">
                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="<?= $one2one_chat['jobseeker_name'] ?>"> </div>
                              <div class="received_msg">
                                <div class="received_withd_msg">
                                  <p><?= $one2one_chat['message'] ?></p>
                                  <span class="time_date"><?= date("h:i A | M d", strtotime($one2one_chat['sent_on'])) ?></span>
                                </div>
                              </div>
                            </div>
                          <?php endif ?>
                        <?php $i++;
                        endforeach ?>
                      <?php else : ?>
                        <p>No chat.</p>
                      <?php endif ?>
                    </div>
                    <div class="type_msg">
                      <div class="input_msg_write">
                        <input type="text" id="message" class="write_msg" placeholder="Type a message" autocomplete="off" />
                        <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                      </div>
                      <input type="hidden" value="<?= @$i ?>" id="last_chat_id" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('common/bottom_ads'); ?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <script>
      $(".msg_send_btn").click(function() {
        var message = $('#message').val();
        $.post("<?= base_url($this->uri->segment(1) . '/' . $this->uri->segment(2) . '/one2one_send/' . $this->uri->segment(3)) ?>", {
            message: message
          },
          function(data, status) {
            // alert("Data: " + data + "\nStatus: " + status);
            $('#message').val('');
            get_chat();
            if (data['error'] == true) {
              alert(data['response']['message']);
            }
          });
      });
      $(".refresh_chat").click(function() {
        get_chat();
      });
      setInterval(function() {
        get_chat();
      }, 1000);
      $(".msg_history").scrollTop($(".msg_history")[0].scrollHeight);

      function get_chat() {
        var last_chat_id = $('#last_chat_id').val();
        $.get("<?= base_url($this->uri->segment(1) . '/' . $this->uri->segment(2) . '/get_chat/' . $this->uri->segment(3)) ?>/" + last_chat_id, function(data, status) {
          if (data['error'] == false) {
            $.each(data['response'], function(key, value) {
              // alert(key + ": " + value['message']);
              if (value['sent_from'] == 'employer') {
                $(".msg_history").append(
                  '<div class="outgoing_msg">' +
                  '<div class="sent_msg">' +
                  '<p>' + value['message'] + '</p>' +
                  '<span class="time_date">' + value['sent_on'] + '</span>' +
                  '</div>' +
                  '</div>'
                );
              } else {
                $(".msg_history").append(
                  '<div class="incoming_msg">' +
                  '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png"> </div>' +
                  '<div class="received_msg">' +
                  '<div class="received_withd_msg">' +
                  '<p>' + value['message'] + '</p>' +
                  '<span class="time_date">' + value['sent_on'] + '</span>' +
                  '</div>' +
                  '</div>' +
                  '</div>'
                );
                // $(".msg_history").append(value['message']);
              }
            });
            $(".msg_history").scrollTop($(".msg_history")[0].scrollHeight);
            $('#last_chat_id').val(parseInt(last_chat_id) + parseInt(data['response'].length));
          }
        });
      }
    </script>
</body>

</html>