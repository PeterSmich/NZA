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
  $_SESSION['redirect_url'] = "newblog.php";
  if( $_SESSION['valid'] == true){
  }else{
    $_SESSION['nickname'] = 'Anonymus';
    header("Location: login.php");
  }
  $page_type = "upload";
  // Load the driver
  require_once("dist/rdb/rdb.php");
  // Connect to localhost
  try{
	$conn = r\connect('localhost');
	$rdb_connect = true;
  }catch (Exception $e){
	  $rdb_connect = false;
  }
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
		<?php
			if(isset($_POST['delete'])){
				$msg = "Nem sikerült törölni:";
				$type_b = 'callout-damger';
				$type_p = 'fa-warning';
				$result = r\db('nz_database')->table('blogs')->get($_SESSION['id'])->delete()->run($conn);
				if($result['deleted'] == 1){
					unset($_POST['search']);
					unset($_SESSION['id']);
					$msg = "Sikeresen törölve :)";
					$type_b = 'callout-success';
					$type_p = 'fa-check';
				}
			}
			if (isset($_POST['submit'])){
				$msg = "";
				$type_b = 'callout-warning';
				$type_p = 'fa-warning';
				if($rdb_connect && $msg == ""){		
					try{
						$result = r\db('nz_database')->table('blogs')->get($_SESSION['id'])->update(array(
								'content' => $_POST['blogeditor'],
								'images' => explode(',',$_POST['imgs']),
								'img' => $_POST['img'],
								'language'=> $_POST['lang'],
								'sort_content' => substr($_POST['blogeditor'], 0, 45).' ...' ,
								'subtitle' => $_POST['subtitle'],
								'timestamp' => r\now(),
								'title' => $_POST['title']
							))->run($conn);
						if($result['replaced'] == 1){
							unset($_POST['search']);
							unset($_SESSION['id']);
							$msg = "Sikeresen frissítve :)";
							$type_b = 'callout-success';
							$type_p = 'fa-check';
						}else{
							$msg = implode("</br>",$result);
							$type_b = 'callout-danger';
							$type_p = 'fa-ban';
						}
					}catch (Exception $e) {
						$msg = "Hiba történt az adatbázis frissítése során :(".$e.'<br>';
						$type_b = 'callout-danger';
						$type_p = 'fa-ban';
					}					
				}else{
					if($msg ==""){
						$msg = $msg.'Nem tudtunk kapcsolódni az adatbázishoz :(';
					}else{
						$msg = $msg.'</br>Nem tudtunk kapcsolódni az adatbázishoz :(';
					}
					$type_b = 'callout-danger';
					$type_p = 'fa-ban';
				}
			}
		?>
		<?php
		if(isset($_POST['search'])){
			unset($_SESSION['id']);
			$result = r\db('nz_database')->table('blogs')->filter(array('title'=>$_POST['title_filter']))->run($conn);
			$num = r\db('nz_database')->table('blogs')->filter(array('title'=>$_POST['title_filter']))->count()->run($conn);
			if($num == 1){
				
				foreach($result as $r){
					$res = $r;
				}
				$_SESSION['id'] = $res['id'];
		echo'
		<form role = "form" action = "'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method = "post">
			<div class="row">
				<div class="col-xl-12 col-xs-12">
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title">Alap adatok</h3>
							<!-- tools box -->
							<div class="pull-right box-tools">
								<button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							</div>
							<!-- /. tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body pad">
							<div class="col-lg-6 col-xs-12">
								<div class="form-group">
								  <label>Cím</label>
								  <input type="text" class="form-control" name="title" id="title" placeholder="" value="'.$res['title'].'">
								</div>
							</div>
							<div class="col-lg-6 col-xs-12">
								<div class="form-group">
								  <label>Alcím</label>
								  <input type="text" class="form-control" name="subtitle" placeholder=""  value="'.$res['subtitle'].'">
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="form-group">
								  <label>Borítókép</label>
								  <input type="text" class="form-control" name="img" id="img" placeholder="" value="'.$res['img'].'">
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="form-group">
								  <label>Galéria képei</label>
								  <input type="text" class="form-control" name="imgs" placeholder="!vesszővel,elválasztva!" value="'.implode(',',$res['images']).'">
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="form-group">
									<label>Nyelv</label>
									<select class="form-control select2" style="width: 100%;" name="lang">
										<option '; if($res['language'] == 'hun'){ echo 'selected="selected"';} echo'>hun</option>
										<option '; if($res['language'] == 'en'){ echo 'selected="selected"';} echo'>en</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12 col-xs-12">
					<div class="box box-info">
						<div class="box-header">
							<h3 class="box-title">Bejegyzés</h3>
							<!-- tools box -->
							<div class="pull-right box-tools">
								<button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							</div>
							<!-- /. tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body pad" >
							<textarea id="blogeditor" name="blogeditor" rows="10" cols="80">'.$res['content'].'</textarea>
						</div>
					</div>
					<!-- /.box -->
				</div>
			</div>
			<div class="row">
				<div  class="col-lg-6 col-xs-6">
					<button type="submit" name = "submit" class="btn btn-success btn-block btn-flat" onclick="return validation()">Frissítés</button>
				</div>
				<div  class="col-lg-6 col-xs-6">
					<button type="button" class="btn btn-danger  btn-block btn-flat" data-toggle="modal" data-target="#modal-danger">
						Törlés
					</button>
				</div>
			</div>
		<div class="modal modal-danger fade" id="modal-danger">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Törlés</h4>
              </div>
              <div class="modal-body">
                <p>Biztos törölni szeretnéd a(z) "'.$res['title'].'" bejegyzést?&hellip;</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Mégsem</button>
                <button type="submit" name="delete" class="btn btn-outline">Igen</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
			<div class="modal modal-warning fade" id="modal-warning">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Figyelem</h4>
				  </div>
				  <div class="modal-body">
					<p id="modal-body">&hellip;</p>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Rendben</button>
				  </div>
				</div>
				<!-- /.modal-content -->
			  </div>
			  <!-- /.modal-dialog -->
			</div>';
			if(isset($_POST['submit'])){
				unset($_POST['submit']);
			echo
			'<div class="col-xl-12 col-xs-12">
				<div class="callout '; echo $type_b; echo '">
					<h4><i class="icon fa '; echo $type_p; echo '"></i> Frissítés:</h4>
					<p>'; echo $msg; echo '</p>
				</div>
			</div>';
			}
			if(isset($_POST['delete'])){
				unset($_POST['delete']);
			echo
			'<div class="col-xl-12 col-xs-12">
				<div class="callout '; echo $type_b; echo '">
					<h4><i class="icon fa '; echo $type_p; echo '"></i> Törlés:</h4>
					<p>'; echo $msg; echo '</p>
				</div>
			</div>';
			}
			echo'
		</form>';
			}else{
				unset($_POST['search']);
			echo'
		<form role = "form" action = "'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method = "post">
			<div class="col-lg-12 col-xs-12">
				<div class="form-group">
				  <label>A bejegyzés címe:</label>
				  <input type="text" class="form-control" name="title_filter" id="title_filter" placeholder="">
				</div>
			</div>
			<div class="col-lg-12 col-xs-12">
				<button type="submit" name = "search" class="btn btn-primary btn-block btn-flat" onclick="return validation()">Keresés</button>
			</div>
			<div class="col-xl-12 col-xs-12">
				<div class="callout callout-warning">
					<h4><i class="icon fa fa-warning"></i> Keresés:</h4>
					<p>Nem található a keresett bejegyzés</p>
				</div>
			</div>
		</form>';
			}
		}else{
			echo'
		<form role = "form" action = "'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method = "post">
			<div class="col-lg-12 col-xs-12">
				<div class="form-group">
				  <label>A bejegyzés címe:</label>
				  <input type="text" class="form-control" name="title_filter" id="title_filter" placeholder="">
				</div>
			</div>
			<div class="col-lg-12 col-xs-12">
				<button type="submit" name = "search" class="btn btn-primary btn-block btn-flat" onclick="return validation()">Keresés</button>
			</div>';
			if(isset($_POST['submit'])){
				unset($_POST['submit']);
			echo
			'<div class="col-xl-12 col-xs-12">
				<div class="callout '; echo $type_b; echo '">
					<h4><i class="icon fa '; echo $type_p; echo '"></i> Frissítés:</h4>
					<p>'; echo $msg; echo '</p>
				</div>
			</div>';
			}
			if(isset($_POST['delete'])){
				unset($_POST['delete']);
			echo
			'<div class="col-xl-12 col-xs-12">
				<div class="callout '; echo $type_b; echo '">
					<h4><i class="icon fa '; echo $type_p; echo '"></i> Törlés:</h4>
					<p>'; echo $msg; echo '</p>
				</div>
			</div>';
			}
			echo'
		</form>';
		}
		?>
	</section>
	<script>
		function validation(){
			var msg = "";
			if(!document.getElementById('title').value){
				msg = "A Címről ne feledkezz meg!";
			}
			if(!document.getElementById('img').value){
				if(msg){
					msg += "</br>A borítóképről se ne feletkezz meg!";
				}else{
					msg = "A borítóképről ne feletkezz meg!";
				}
			}
			if(msg){
				$('#modal-body').html(msg);
				$("#modal-warning").modal("show");
				return false;
			}
			return true;
		}
	</script>
  </div>
  <!-- /.content-wrapper -->

 <?php include("footer.php");?>