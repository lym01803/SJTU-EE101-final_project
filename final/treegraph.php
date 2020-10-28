<!doctype html>
<html>
<head>
<title>Tree Graph</title>
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
	<div class="panel" id="image2">
    <script>
        <?php 
            $authorid = $_GET['authorid'];
            $authorname = $_GET['authorname'];
            $link=mysqli_connect("db.lifanz.cn:3306", 'ee101_user', 'ee1012019', 'ee101');
            $result = mysqli_query($link,"SELECT e.PaperID as PaperID, e.Title as Title, e.PaperPublishYear as Year, e.refcount as cnt, f.ConferenceName as conf from (SELECT c.PaperID , c.Title, c.ConferenceID, c.PaperPublishYear, d.refcount from ((SELECT a.* from (papers a inner join paper_author_affiliation b on a.PaperID = b.PaperID) where b.AuthorID = '$authorid') c inner join paper_count d on c.PaperID = d.PaperID)) e inner join conferences f on e.ConferenceID = f.ConferenceID order by refcount desc");
            $papertitle = array();
            while($row = mysqli_fetch_array($result)){
                $papertitle[] = $row["Title"];
            }
        ?>
        var titleinfo = <?php echo json_encode($papertitle)?>;
        var authorid = "<?php echo $authorid ?>";
        $("#image2").height(400 + 35*<?php echo $_GET['nums']; ?>);
        $.ajax({
					type: "POST",
					async: "false",
					url: "./author_stat1.php",
					dataType: "json",
					data: {
						"author_id": "<?php echo $authorid; ?>",
						"author_name": "<?php echo $authorname; ?>",
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
								return titleinfo[parseInt(se) - 1];
							}
						}
						return param["data"]["name"];
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
                		left: '15%',
                		bottom: '15px',
                		right: '25%',
                		symbolSize: 10,
                        layout: '',
                        label: {
                            normal: {
                                position: 'left',
                                verticalAlign: 'middle',
                                align: 'right',
                                fontSize: 9
                            }
                        },

                        leaves: {
                            label: {
                                normal: {
                                    position: 'right',
                                    verticalAlign: 'middle',
                                    align: 'left'
                                }
                            }
                        },
						expandAndCollapse: true,
                		animationDuration: 550,
                		animationDurationUpdate: 750
            		}
        		]
			};
			echarts.init(document.getElementById('image2')).setOption(option1);
		}
    </script>
    </div>
</body>

</html>