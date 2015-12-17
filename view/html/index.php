<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon"
		href="<?php echo Router::getBaseUrl();?>asset/ico/favicon.ico">
	<title>DDMG大大买钢支付系统</title>
	<link href="http://libs.useso.com/js/bootstrap/3.1.1/css/bootstrap.min.css"	rel="stylesheet">
	<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-theme.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-switch-3.1.0.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo Router::getBaseUrl();?>css/prettify.css"
		type="text/css" rel="stylesheet" />
	<!-- Custom styles for this template -->
	<link href="<?php echo Router::getBaseUrl();?>css/custom.css" rel="stylesheet">
	<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	<script src="<?php echo Router::getBaseUrl();?>js/jquery-1.11.3.min.js"></script>
</head>
<body>
<input type="hidden" id="token"   value="<?php echo UserController::getToken();?>">
<input type="hidden" id="isLogin" value="<?php echo UserController::isLogin();?>"/>

<div class="modal fade" id="confirm-admin-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">提示</h5>
			</div>
			<div class="modal-body">
			     <h4 id="confirm-admin-body" class="text-center">确认操作！</h4>
			</div>
			<div class="alert alert-danger" id="confirm-admin-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="confirm-admin-btn">确定</button>
			</div>
		</div>
	</div>
</div>

<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="javascript:void(0);">大大买钢-支付系统</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="javascript:;" id="">欢迎: <?php echo UserController::getLoginUser()['nicename']; ?>( <?php echo  UserController::getLoginUser()['account']; ?> )</a></li>
			<li><a href="javascript:void(0);" id="pwdBtn">修改密码</a></li>
			<li><a href="javascript:void(0);" id="logoutBtn">退出</a></li>
		  </ul>
		  <!-- <form class="navbar-form navbar-right">
			<input type="text" class="form-control" placeholder="Search...">
		  </form>
		   -->
		</div>
	  </div>
	</nav>
	<div class="container-fluid">
	  <div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
		  <ul id="menuList" class="nav nav-sidebar">
		    <li <?php if(doit::$controller == 'TradeRecord'){?> class="active" <?php } ?>>
				<a href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">我的大大付款</a>
		    </li>
			<li <?php if(doit::$controller == 'AuthorizationCode'){?> class="active" <?php } ?>>
				<a href="<?php echo Router::getBaseUrl();?>authorizationCode/getIndex">授权码管理</a>
		    </li>
		    <li <?php if(doit::$controller == 'BcsCustomer'){?> class="active" <?php } ?>>
				<a href="<?php echo Router::getBaseUrl();?>bcsCustomer/getInfo">账户信息</a>
		    </li>
		    <li <?php if(doit::$controller == 'BcsMarket'){?> class="active" <?php } ?>>
				<a href="<?php echo Router::getBaseUrl();?>bcsMarket/getInfo">市场信息</a>
		    </li>
		  </ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		  <?php if($page_type == 'authorizationCode'){?>
		        <script src="<?php echo Router::getBaseUrl();?>js/authorizationCode.js"></script>
				<?php echo $authorizationCode_html; ?>
		  <?php } else if($page_type == 'tradeRecord'){?>
		        <script src="<?php echo Router::getBaseUrl();?>js/tradeRecord.js"></script>
				<?php echo $tradeRecord_html; ?>
		  <?php } else if($page_type == 'bcsCustomer'){?>
		        <script src="<?php echo Router::getBaseUrl();?>js/bcsCustomerInfo.js"></script>
			    <?php echo $bcsCustomer_html; ?>
	    <?php } else if($page_type == 'bcsMarket'){?>
                <script src="<?php echo Router::getBaseUrl();?>js/bcsMarketInfo.js"></script>
	            <?php echo $bcsMarket_html; ?>
		  <?php }?>
		</div>
	  </div>
	</div>
	<div id="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 col-md-12" id="realfooter">
					<!--
					<p class="text-center">page footer</p>
					-->
				</div>
			</div>
		</div>
	</div>
<div class="modal fade" id="updatePasswordModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_title" >修改密码</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				 <div class="form-group">
				    <label for="oldPwd" class="col-sm-2 control-label">旧密码</label>
				    <div class="col-sm-8">
				      <input type="password" class="form-control" id="oldPwd" placeholder="旧密码">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="newPwd" class="col-sm-2 control-label">新密码</label>
				    <div class="col-sm-8">
				      <input type="password" class="form-control" id="newPwd" placeholder="新密码">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="newPwd2" class="col-sm-2 control-label">确认密码</label>
				    <div class="col-sm-8">
				      <input type="password" class="form-control" id="newPwd2" placeholder="确认密码">
				    </div>
				  </div>
				  <div class="alert alert-danger" id="updatePasswordErrorMsg"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button"  class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="updatePasswordBtnSave">确定</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="<?php echo Router::getBaseUrl();?>js/ie8-responsive-file-warning.js"></script><![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script src="http://libs.useso.com/js/jquery/2.1.1/jquery.min.js"></script>-->
<script src="http://libs.useso.com/js/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<!-- <script src="http://libs.useso.com/js/bootstrap-switch/3.0.1/js/bootstrap-switch.min.js"></script> -->
<script src="<?php echo Router::getBaseUrl();?>js/bootstrap-switch-3.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/prettify.js"></script>
<script type="text/javascript">var BASE_PATH="<?php echo Router::getBaseUrl();?>";</script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/custom.js"></script>
</body>
</html>

