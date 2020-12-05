<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <?php $this->load->view('admin/common/meta_tags'); ?>
  <?php $this->load->view('admin/common/before_head_close'); ?>
  <style type="text/css">
    .awesome_style {
      font-size: 100px;
    }
  </style>
</head>

<body class="skin-blue">
  <?php $this->load->view('admin/common/after_body_open'); ?>
  <?php $this->load->view('admin/common/header'); ?>
  <div class="wrapper row-offcanvas row-offcanvas-left">
    <?php $this->load->view('admin/common/left_side'); ?>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1> Dashboard</h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <table width="100%" border="0" align="left">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="19%" align="center"><a href="<?php echo base_url('admin/employers'); ?>"><i class="fa awesome_style fa-briefcase"></i><br>
                Employers</a></td>
            <td width="19%" align="center"><a href="<?php echo base_url('admin/job_seekers'); ?>"><i class="fa awesome_style awesome_style fa-user"></i><br>
                Jobseeker</a></td>
            <td width="19%" align="center"><a href="<?php echo base_url('admin/posted_jobs'); ?>"><i class="fa awesome_style fa-upload"></i> <br>
                Posted Jobs</a></td>
            <td width="19%" align="center"><a href="<?php echo base_url('admin/posted_jobs'); ?>"><i class="fa fa-clipboard awesome_style"></i><br>
                Featured Jobs</a></td>
            <td width="19%" align="center"><a href="<?php echo base_url('admin/pages'); ?>"><i class="fa awesome_style fa-file-text"></i><br>
                Content Management</a></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><a href="<?php echo base_url('admin/industries'); ?>"><i class="fa fa-desktop awesome_style"></i><br>
                Job Industries</a></td>
            <td align="center"><a href="<?php echo base_url('admin/institute'); ?>"><i class="fa awesome_style fa-university"></i><br>
                Institute</a></td>
            <td align="center"><a href="<?php echo base_url('admin/salary'); ?>"><i class="fa awesome_style fa-money"></i> <br>
                Salary</a></td>
            <td align="center"><a href="<?php echo base_url('admin/qualification'); ?>"><i class="fa  awesome_style fa-graduation-cap">&nbsp;</i><br>
                Qualification</a></td>
            <td align="center"><a href="<?php echo base_url('admin/skills'); ?>"><i class="fa awesome_style fa-tags"></i><br>
                Manage Skills</a></td>
          </tr>
        </table>
      </section>
      <!-- /.content -->
    </aside>
    <!-- /.right-side -->
    <?php $this->load->view('admin/common/footer'); ?>