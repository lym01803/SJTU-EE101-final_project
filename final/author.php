<!doctype html>
<html>
<head>
<title>Author Page</title>
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
			background-color: rgba(255, 255, 255, 0.85);
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
	<?php
		include 'english.php';
		use Wamania\Snowball\english; 
	?>
	<div class="container">
		<div class="row panel" id="bigrow">
		<div class="col-md-12 col-xs-12 col-sm-12" style="height:40px;font-size:25px;margin-bottom:5px;">
			Author Page
		</div>
		<div class="col-md-6 col-xs-6 col-sm-6" style="padding:8px;font-size:18px;" id="left-col">
		<?php	
			$paperlist = array();
			$author_id=$_GET["authorid"];
			$link=mysqli_connect("db.lifanz.cn:3306", 'ee101_user', 'ee1012019', 'ee101');
			$result=mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id'");
			$author_name=mysqli_fetch_array($result)["AuthorName"];
		?>
			<div class="pull-left" style="padding:8px;" id="authorname">
				Author Name : <?php echo ucwords($author_name);?>
			</div><br/><hr/>
			<div class="pull-left" style="padding:8px;" id="affiliationname">
			Affiliation Name : 
			</div><br/><br/>
			<div id="affilist">
			<ul class="list-group">
		<?php
			$result = mysqli_query($link, "SELECT affiliations.AffiliationID, affiliations.AffiliationName, cnt from (select AffiliationID, count(*) as cnt from paper_author_affiliation where AuthorID='$author_id' and AffiliationID is not null group by AffiliationID order by cnt desc) as tmp inner join affiliations on tmp.AffiliationID = affiliations.AffiliationID");
			while($row = mysqli_fetch_array($result)){
		?>
			<li class="list-group-item" align="left">
		<?php
				$tmp = ucwords($row["AffiliationName"]);
				echo "<a target='_blank' href='./affiliation.php?affiliationid=".$row["AffiliationID"]."&affiliationname=".$tmp."'>$tmp</a>";
		?>
				<span class="badge" align="right" data-toggle="tooltip" data-placement="auto" title='Number of papers associated with this affiliation'><?php echo $row["cnt"]; ?></span>
			</li>
		<?php
			}	
		?>
			</ul></div>
			<hr/>
		<div class="pull-left" style="padding:5px;text-align:left;line-height:40px;" id="showarea">
			Paper : <br/>
			<div data-spy="scroll" data-target="#navbar-example" data-offset="0" 
	 					style="height:1000px;overflow:auto; position: relative;">
			<ul class="list-group">
			<?php
				$paper_num = 0;
				$result = mysqli_query($link,"SELECT e.PaperID as PaperID, e.Title as Title, e.PaperPublishYear as Year, e.refcount as cnt, f.ConferenceName as conf from (SELECT c.PaperID , c.Title, c.ConferenceID, c.PaperPublishYear, d.refcount from ((SELECT a.* from (papers a inner join paper_author_affiliation b on a.PaperID = b.PaperID) where b.AuthorID = '$author_id') c inner join paper_count d on c.PaperID = d.PaperID)) e inner join conferences f on e.ConferenceID = f.ConferenceID order by refcount desc");
				//$result = mysqli_query($link, "SELECT PaperID from paper_author_affiliation where AuthorID='$author_id'");
				$result_global = mysqli_fetch_all($result, MYSQLI_BOTH);
				$cnt_paper = 0;
				$paperID = array();
				foreach($result_global as $row){
					$paper_id = $row["PaperID"];
					$paperID[++$cnt_paper] = $paper_id;
					$paper_num += 1;
					//$paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title from papers where PaperID='$paper_id'"));
					$paper_title = $row["Title"];
					$paperlist[$paper_title] = array("ref" => 0);
					echo "<li class='list-group-item' align='left' style='padding:8px;' id='".$paper_id."' value='".$paper_title."'>";
					echo "<span><a target='_blank' data-toggle='tooltip' data-placement='auto' title='No.".$cnt_paper."' href='./search.php?field=PaperName&value=".$paper_title."'>".$paper_title."</a>";
					if($cnt_paper > 20){
						?>
						<script>
							$("#"+"<?php echo $paper_id; ?>").hide();
						</script>
						<?php 
					}
					//$tmp_search = mysqli_query($link, "SELECT count(*) as cnt from paper_reference where ReferenceID = '$paper_id'");
					//$tmp_search = mysqli_fetch_array($tmp_search);
					$ref_num = (int)$row["cnt"];
					$paperlist[$paper_title]["ref"] = $ref_num;
					if($ref_num){
						//echo "<span class='badge pull-right' data-toggle='tooltip' data-placement='auto' data-html='true' title='Numbers of references'>$ref_num</span>";
						echo "<label class='pull-right' style='font-size:12px;' title='number of references'>$ref_num</label>";
					}
					echo "</span></li>";
					//$year = mysqli_fetch_array(mysqli_query($link, "SELECT PaperPublishYear as year, ConferenceID from papers where papers.PaperID = '$paper_id'"));
					$paperlist[$paper_title]["year"] = (int)$row["Year"];
					//$confid = $row["ConferenceID"];
					$paperlist[$paper_title]["conf"] = $row["conf"];
				}
				?>
					<script>
						var paperid_js = <?php echo json_encode($paperID); ?>;
					</script>
				<?php
				if($cnt_paper > 20){
					echo "<button class='btn btn-default' style='width:100%;padding:1px;' id='showmore'>show more</button>";
					?>
					<script>
						$("#showmore").click(function(){
							var lim_mx = <?php echo $cnt_paper;?>;
							for(var p = show_items + 1; p <= lim_mx && p <= show_items + 20; ++p){
								$("#" + paperid_js[p]).show();
							}
							show_items += 20;
							if(show_items >= lim_mx){
								$("#showmore").hide();
							}/*
							var totalheight = $("#authorname").height() + $("#affiliationname").height() + $("#showarea").height() + $("#affilist").height() + 100;
							if($("#left-col").height() < totalheight){
								$("#left-col").height(totalheight);
								//console.log($("#left-col").height());
								//console.log($("#authorname").height() + $("#affiliationname").height() + $("#showarea").height() + $("#affilist").height());
							}*/
						});
					</script>
					<?php 
				}
			?>
			</ul></div>
			<label class="btn" onclick="dealwithlabelclick()" style="padding:2px;font-size:20px;">
				<em>more associated authors</em>
			</label>
			<script>
				function dealwithlabelclick(){
					$("#moreauthors_associate").toggle("fast");
				}
			</script>
			<div id="moreauthors_associate">
				<?php
					echo "<ul class='pull-left'>";
					$result = mysqli_query($link, "SELECT g.AuthorID as authorid, h.AuthorName as authorname FROM (SELECT e.AuthorID, f.Score FROM (SELECT c.AuthorID FROM ((SELECT a.AffiliationID FROM paper_author_affiliation a where a.AuthorID = '$author_id' and NOT a.AffiliationID = 'None' group by AffiliationID) b inner join paper_author_affiliation c on b.AffiliationID = c.AffiliationID) group by AuthorID) e inner join author_score f on e.AuthorID = f.AuthorID) g inner join authors h on g.AuthorID = h.AuthorID where NOT g.AuthorID = '$author_id' order by g.Score desc limit 0, 10");
					while($row = mysqli_fetch_array($result)){
						echo "<li><a href='./author.php?authorid=".$row['authorid']."'>".$row['authorname']."</a></li>";
					}				
				?>
			</div>
		</div>
		</div>
		<div class="col-md-6 col-xs-6 col-sm-6 panel panel-default" style="height:500px;margin:0px;background-color:rgba(255,255,255,0.8);" id="image1">
				<script>
					var msg = <?php echo json_encode($paperlist); ?>;
					//console.log(msg);
					var min_year = 10000;
					var max_year = 0;
					var yeartox = Array();
					var yeardata = Array();
					var conftoy = Array();
					var confdata = Array();
					var size = Array();
					var order = 0;
					var idx = 0;
					var pos = Array();
					var Number__ = Array();
					var mx = 0;
					for(var title in msg){
						var num = parseInt(msg[title]["ref"]);
						if(num > mx){
							mx = num;
						}
					}
					if(mx == 0){
						mx = 1;
					}
					var AAA = 16;
					var BBB = 64 + Math.log(mx)*8;
					for(var title in msg){
						if(parseInt(msg[title]["year"]) > max_year){
							max_year = parseInt(msg[title]["year"]);
						}
						if(parseInt(msg[title]["year"]) < min_year){
							min_year = parseInt(msg[title]["year"]);
						}
						var tmp = msg[title]["conf"];
						if(!conftoy.hasOwnProperty(tmp)){
							conftoy[tmp] = order;
							confdata[order] = tmp;
							order++;
						}
						var num = parseInt(msg[title]["ref"]);
						Number__[idx] = num;
						size[idx++] = BBB - (BBB - AAA) * (1.0 - num/mx);
					}
					for(var i = min_year - 2; i <= max_year + 1; i++){
						yeartox[i] = i - min_year + 2;
						yeardata[i - min_year + 2] = i;
					}
					idx = 0;
					//console.log(msg);
					var Conf__ = Array();
					var Year__ = Array();
					for(var title in msg){
						var year = parseInt(msg[title]["year"]);
						var conf = msg[title]["conf"];
						Conf__[idx] = conf;
						Year__[idx] = year;
						pos[idx++] = [yeartox[year], conftoy[conf]];
					}
					var option = {
						title:{
							text: "Number of references - Year - Conference",
						},
						tooltip:{
							formatter: function(param){
								var id = parseInt(param["dataIndex"]);
								return "Conferences: "+Conf__[id]+"<br/>Year: "+Year__[id]+"<br/>Number: "+Number__[id];
							}
						},
						xAxis: {
							data: yeardata,
						},
						yAxis: {
							boundaryGap: true,
							data: confdata,
							splitLine: {
        						show: true,
        						lineStyle:{
           							color: ['rgba(60,60,60,0.5)'],
           							width: 1,
           							type: 'solid'
      							}
　　						}
						},
						series: [{
							symbolSize: function(param, idx){
								return parseFloat(size[parseInt(idx["dataIndex"])]);
							},
							data: pos,
							type: "scatter",
						}],
					}
					var myChart = echarts.init(document.getElementById('image1'));
					myChart.setOption(option);
				</script>
		</div>
		<div class="col-md-6 col-xs-6 col-sm-6 panel panel-default" style="margin:0px;background-color:rgba(255,255,255,0.9);" id="image2">
		<script>
			var tmpheight;
			var layout = 1;
			var init_depth = 2 - (<?php echo $paper_num ?> > 35);
			$("#image2").height(600 + 100 * init_depth);
			//$("#image2").height(<?php echo $paper_num * 35+400; ?>);
			$.ajax({
					type: "POST",
					async: "false",
					url: "./author_stat1.php",
					dataType: "json",
					data: {
						"author_id": "<?php echo $author_id; ?>",
						"author_name": "<?php echo $author_name; ?>",
					},
					success: function(msg) {
						image2show(msg);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("Error!" + XMLHttpRequest.status + XMLHttpRequest.readyState + textStatus);
					}
				});
			function image2show(data){
			var option1 = {
        		tooltip: {
            		trigger: 'item',
					triggerOn: 'mousemove',
					formatter: function(param){
						//console.log(param);
						var tmpstr = param["data"]["name"];
						var fi = tmpstr.split(".");
						if(fi.length > 1){
							var se = fi[1];
							if(se.split(" ").length == 1){
								return $("#" + paperid_js[parseInt(se)])[0]["children"][0]["children"][0]["innerText"];
							}
						}
						return param["data"]["name"];
					}
				},
				toolbox:{
					feature:{
						myTool1: {
							show: true,
							title: 'click',
							icon: 'path://M432.45,595.444c0,2.177-4.661,6.82-11.305,6.82c-6.475,0-11.306-4.567-11.306-6.82s4.852-6.812,11.306-6.812C427.841,588.632,432.452,593.191,432.45,595.444L432.45,595.444z M421.155,589.876c-3.009,0-5.448,2.495-5.448,5.572s2.439,5.572,5.448,5.572c3.01,0,5.449-2.495,5.449-5.572C426.604,592.371,424.165,589.876,421.155,589.876L421.155,589.876z M421.146,591.891c-1.916,0-3.47,1.589-3.47,3.549c0,1.959,1.554,3.548,3.47,3.548s3.469-1.589,3.469-3.548C424.614,593.479,423.062,591.891,421.146,591.891L421.146,591.891zM421.146,591.891',
							onclick: function(){
								window.open("treegraph.php?authorid="+"<?php echo $author_id; ?>" + "&authorname=" + "<?php echo $author_name; ?>" +"&nums=" + "<?php echo $paper_num; ?>");
							}
						}
					}
				},
				title: {
					text: "Author----------------Paper----------------Co-author",
					subtext: "\"No.\" in 'Paper' column shows the paper's appearance order in the left column of this page\n\"No.\" in 'Co-author' column shows the author sequence",
				},
        		series: [
            		{
                		type: 'tree',
                		data: [data],
                		top: '60px',
                		left: '25%',
                		bottom: '15px',
                		right: '25%',
                		symbolSize: 7,
						layout: 'radial',
						expandAndCollapse: true,
                		animationDuration: 550,
                		animationDurationUpdate: 750,
						initialTreeDepth: init_depth
            		}
        		]
			};
			echarts.init(document.getElementById('image2')).setOption(option1);
		}
		</script>
		</div>
		<div class="col-md-6 col-xs-6 col-sm-6 panel panel-default" style= "height:500px;margin:0px;background-color:rgba(255,255,255,0.9);" id="image3">
		<?php 
				$wordorigin = array();
				$wdcnt = array();
				$stemmer = new English();
				foreach($result_global as $row){
					$p_title = $row["Title"];
					$p_array = explode(" ", $p_title);
					foreach($p_array as $word){
						$wdrt = $stemmer->stem($word);
						if(array_key_exists($wdrt, $wdcnt)){
							$wdcnt[$wdrt] += 1;
							if(strlen($word) < strlen($wordorigin[$wdrt])){
								$wordorigin[$wdrt] = $word;
							}
						}else {
							$wordorigin[$wdrt] = $word;
							$wdcnt[$wdrt] = (int)1;
						}
					}
				}
				$retval = array();
				$banlist = json_decode(file_get_contents("./banbanban.json"));
				foreach($wdcnt as $k=>$v){
					if(array_key_exists($k, $banlist)){
						$v = 1;
					}
					$retval[] = array("name"=>$wordorigin[$k], "value"=>$v);
				}
				$retval = json_encode($retval);
				echo "<script>var word_cloud_data = $retval;</script>";
		?>
		<script>
			var option = {
				series: [
    				{
   						type: 'wordCloud',
        				sizeRange: [10, 60],
        				rotationRange: [-90, 90],
						right: '5%',
        				textStyle: {
            				normal: {
                				color: function () {
									while(1){
										//console.log("try");
										var a = Math.random()*255, b = Math.random()*255, c = Math.random()*255;
										if(a + b + c < 600 && a + b + c > 200 && (a - b) * (a - b) + (b - c) * (b - c) + (c - a)*(c - a) > 20000){
                    						return 'rgb(' + [
                            					Math.round(Math.random() * 255),
                            					Math.round(Math.random() * 255),
                            					Math.round(Math.random() * 255)
												].join(',') + ')';
										}
									}
                				}
            				},
            				emphasis: {
                				shadowBlur: 10,
                				shadowColor: '#333'
            				}
        				},
        				data: word_cloud_data,
    				}
    			]
			};
			var myChart = echarts.init(document.getElementById('image3'));
			myChart.setOption(option);
		</script>
		</div>
	</div>
	<script>
		function resize(){
			var ht = 1000 + $("#image2").height();
			if($("#left-col").height() < ht){
				$("#left-col").height(ht);
				//console.log($("#left-col").height());
			}
		}
	</script>
</body>

</html>