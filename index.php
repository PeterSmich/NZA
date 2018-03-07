<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<?php
  session_start();
  if(!isset($_SESSION['valid'])){
    $_SESSION['valid'] = false;
  }
  $_SESSION['redirect_url'] = "index.php";
  if( $_SESSION['valid'] == true){
  }else{
    $_SESSION['nickname'] = 'Anonymus';
    header("Location: login.php");
  }
  $page_type = "index";
 ?>
<?php include("header.php");?>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<?php include("navigation.php");?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) - ->
    <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>-->

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
		<h1>Bejegyzések kezelése</h1>
		<div class="row">
			<div class="col-lg-6 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-green">
				<div class="inner">
				  <h3>Hozzáadás</h3>

				  <p></p>
				</div>
				<div class="icon">
				  <i class="fa fa-newspaper-o"></i>
				</div>
				<a href="newblog.php" class="small-box-footer">
				  Tovább <i class="fa fa-arrow-circle-right"></i>
				</a>
			  </div>
			</div>
			
			<div class="col-lg-6 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-yellow">
				<div class="inner">
				  <h3>Módosítás</h3>

				  <p></p>
				</div>
				<div class="icon">
				  <i class="fa fa-pencil-square-o"></i>
				</div>
				<a href="editblog.php" class="small-box-footer">
				  Tovább <i class="fa fa-arrow-circle-right"></i>
				</a>
			  </div>
			</div>
		</div>
		<h1>Képek kezelése</h1>
		<div class="row">
			<div class="col-lg-4 col-xs-4">
			  <!-- small box -->
			  <div class="small-box bg-green">
				<div class="inner">
				  <h3>Feltöltés</h3>
				  <p></p>
				</div>
				<div class="icon">
				  <i class="fa fa-upload"></i>
				</div>
				<a href="uploadimg.php" class="small-box-footer">
				  Tovább <i class="fa fa-arrow-circle-right"></i>
				</a>
			  </div>
			</div>
			<div class="col-lg-4 col-xs-4">
			  <!-- small box -->
			  <div class="small-box bg-yellow">
				<div class="inner">
				  <h3>Módosítás</h3>
				  <p></p>
				</div>
				<div class="icon">
				  <i class="fa  fa-edit "></i>
				</div>
				<a href="#" class="small-box-footer">
				  Tovább <i class="fa fa-arrow-circle-right"></i>
				</a>
			  </div>
			</div>
			<div class="col-lg-4 col-xs-4">
			  <!-- small box -->
			  <div class="small-box bg-aqua">
				<div class="inner">
				  <h3>Cimkék</h3>
				  <p></p>
				</div>
				<div class="icon">
				  <i class="fa fa-tags"></i>
				</div>
				<a href="#" class="small-box-footer">
				  Tovább <i class="fa fa-arrow-circle-right"></i>
				</a>
			  </div>
			</div>
		</div>
		<h1>Statisztika kezelése</h1>
		<div class="row">
			<div class="col-lg-12 col-xs-12">
			  <!-- small box -->
			  <div class="small-box bg-navy">
				<div class="inner">
				  <h3>Statisztika</h3>

				  <p></p>
				</div>
				<div class="icon">
				  <i class="fa fa-line-chart"></i>
				</div>
				<a href="#" class="small-box-footer">
				  Tovább <i class="fa fa-arrow-circle-right"></i>
				</a>
			  </div>
			</div>
		</div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include("footer.php");?>