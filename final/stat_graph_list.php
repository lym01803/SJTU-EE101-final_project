<!doctype html>
<html>
<head>
<title>Affiliation Subpage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" />
	<script src="//libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
	<script src="./js/ex/echarts.js"></script>
	<script src="./js/ex/echarts-wordcloud.min.js"></script>
	<style>

		/* http://css-tricks.com/perfect-full-page-background-image/ */
		html {
			background: url(DJI_0017.jpg) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

		body {
			padding-top: 20px;
			font-size: 16px;
			font-family: sans-serif;
			background: transparent;
			text-align: center;
		}

		h1 {
			font-family: Arial, sans-serif;
			font-weight: 400;
			font-size: 30px;
		}

		/* Override B3 .panel adding a subtly transparent background */
		.panel {
			background-color: rgba(255, 255, 255, 0.95);
		}

		.margin-base-vertical {
			margin: 10px 0;
		}

		.margin-big-vertical {
			margin: 20px 0;
		}
		

		.centered {
		    position: absolute;
		    top: 10%;
		    left: 50%;
		    transform: translate(-50%, 0%);
		}

	</style>
<script>
	var show_items = 20;
</script>
</head>
<body>
	<div class="container">
	<div class="row">
	<div class="col-md-6 col-xs-6 col-sm-6 panel panel-default centered" id="panel1">
		<?php 
		$conf = array(
			"AAAI" => "46A05BB0",
			"IJCAI" => "47C39427",
			"CVPR" => "45083D2F",
			"ECCV" => "43001016",
			"ICCV" => "45701BF3",
			"ICML" => "465F7C62",
			"SIGKDD" => "436976F3",
			"NIPS" => "43319DD4",
			"ACL" => "46DAB993",
			"EMNLP" => "47167ADC",
			"NAACL" => "45F914AD",
			"SIGIR" => "43FD776C",
			"WWW" => "43ABF249"
		);
        $link = mysqli_connect("db.lifanz.cn:3306", 'ee101_user', 'ee1012019', 'ee101');
		$affi = $_GET['affiliationid'];
		$year = $_GET['year'];
		$confname = $_GET['conferencename'];
		$confid = $conf[$confname];
		$result = mysqli_query($link, "SELECT Title FROM (SELECT PaperID FROM paper_author_affiliation where AffiliationID = '$affi') a inner join papers on a.PaperID = papers.PaperID where ConferenceID = '$confid' and PaperPublishYear = '$year' group by Title");
        ?>
		<table class="table table-hover component tablesorter tablesorter-default tablesortere5cb36a9e7829">
			<thead>
				<tr>
					<th>Title</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					while($row = mysqli_fetch_array($result)){
						?>
						<tr><td style="text-align:left;">
						<?php 
						$tt = $row["Title"];
						echo "<a target='_blank' href='./search.php?field=PaperName&value='".$tt.">".$tt."</a>"
						?>
						</td></tr>
						<?php 
					}
				?>
			</tbody>
		</table>
	</div>
	</div>
	</div>
</body>

</html>