<!DOCTYPE html>
<html>
<head>
	<title>Paper Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="//libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" />
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script src="./js/ex/echarts.js"></script>
	<style>

		/* http://css-tricks.com/perfect-full-page-background-image/ */
		html {
			background: url(IMG_20190406_160108.jpg) no-repeat center center fixed;
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
			background-color: rgba(255, 255, 255, 0.75);
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
		    transform: translate(-50%, 0);
		}
		.side_area{
			border: 1px solid #eeeeee;
        	background-color: #eeeeee;
        	width: 3%;
        	height: 100%;
        	position: absolute;
        	top: 0%;
		}
	</style>
</head>

<body>

	<div class="container">

		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 panel panel-default ">
				
				<h1>Paper</h1>
				<?php
					$Paper_id=$_GET['paper_id'];
					$link=mysqli_connect('db.lifanz.cn:3306','ee101_user','ee1012019','ee101');
					$Base_info=mysqli_fetch_array(mysqli_query($link,"SElECT * from papers where paperid='$Paper_id'"));
				?>
				<center><table class="table table-hover component tablesorter tablesorter-default tablesortere5cb36a9e7829"><thead><tr><th>Title</th><th>Authors</th><th>Year</th><th>Conference</th></tr></thead>
				<?php
					echo "<tr>";
					
					echo "<td>";
					echo $Base_info['Title'];
					echo "</td>";

					echo "<td>";
					$au_info=mysqli_query($link,"SElECT AuthorName,AuthorID FROM authors where authorid in (SElECT authorid from paper_author_affiliation where PaperId='$Paper_id' order by 'authorsequence')");
					while ($rows=mysqli_fetch_array($au_info)) 
					{
						$paperauthor_name=$rows['AuthorName'];
						$paperauthor_id=$rows['AuthorID'];
						echo "<a href=\"/lab3/website-example/author.php?author_id=$paperauthor_id\">$paperauthor_name; </a>";
					}
					echo "</td>";
					
					echo "<td>";
					echo $Base_info['PaperPublishYear'];
					echo "</td>";

					echo "<td>";
					$conf_id=$Base_info['ConferenceID'];
					$Conference_info=mysqli_fetch_array(mysqli_query($link,"SElECT ConferenceName from conferences where ConferenceID='$conf_id'"));
					$conf_name=$Conference_info['ConferenceName'];
					echo "<a href=\"/lab3/website-example/conference.php?conference_id=$conf_id\">$conf_name </a>";
					echo "</td>";

					echo "</tr>";
				?>
			</table>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 panel panel-default" id="image_div" style="background-color:rgba(255,255,255,0.5);height:600px;">
				<script type="text/javascript">
		    		var myChart = echarts.init(document.getElementById('image_div'));
		    		var option = {
		        		title: {
		            		text: 'ECharts 入门示例'
		        		},
		        		tooltip: {},
		       			legend: {
		            		data:['销量']
		        		},
		        		xAxis: {
		            		data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
		        		},
		        		yAxis: {},
		        		series: [{
		            		name: '销量',
		            		type: 'bar',
		            		data: [5, 20, 36, 10, 10, 20]
		        		}]
		    		};
					// 使用刚指定的配置项和数据显示图表。
		    		myChart.setOption(option);
				</script>
			</div>
		</div>

		

	</div>
</body>
</html>