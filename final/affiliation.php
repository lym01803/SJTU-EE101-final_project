<!doctype html>
<html>
<head>
<title>Affiliation Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" />
	<script src="//libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
	<script src="./js/ex/echarts.js"></script>
    <script src="./js/ex/echarts-wordcloud.min.js"></script>
    <script src="./js/ex/echarts-gl.js"></script>
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

</head>
<body>
<div class="container">
    <div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 panel panel-default" style="height:40px;font-size:25px;margin-bottom:0px;">
			Affiliation : <?php echo $_GET["affiliationname"]; ?>
    </div>
        <div class="col-md-12 col-xs-12 col-sm-12 panel panel-default" style="height:600px;font-size:25px;margin-top:0px;margin-bottom:0px;" id="image1">
            Paper
			<script>
                var conf = ["AAAI","IJCAI","CVPR","ECCV","ICCV","ICML","SIGKDD","NIPS","ACL","EMNLP","NAACL","SIGIR","WWW"];
                var year = ["1996","1997","1998","1999","2000","2001","2002","2003","2004","2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015"];

        var data = <?php $id=$_GET["affiliationid"]; echo file_get_contents("./json/$id.json"); ?>;
        var data_max = 0;
        for(var data_row in data){
            if(data[data_row][2] > data_max){
                data_max = data[data_row][2];
            }
        }
        //console.log(data_max);
        data_max *= 0.8;
        option = {
            title:{
                text: "Number of papers related to this affiliation ---- Year ---- Conference",
            },
            tooltip: {
                formatter: function(param){
                    var tmp = param["data"]["value"];
                    var y = year[tmp[0]], c = conf[tmp[1]];
                    return "<div align='left'>Year : " + y + "<br/>Conference : " + c + "<br/>Number of papers : " + tmp[2] + "</div>";
                }
            },
            visualMap: {
                max: data_max,
                inRange: {
                    color: ['#313695', '#4575b4', '#74add1', '#abd9e9', '#87CEEB', '#90EE90', '#fee090', '#fdae61', '#f46d43', '#d73027', '#a50026']
                }
            },
            xAxis3D: {
                type: 'category',
                data: year,
            },
            yAxis3D: {
                type: 'category',
                data: conf
            },
            zAxis3D: {
                type: 'value'
            },
            grid3D: {
                boxWidth: 200,
                boxDepth: 80,
                light: {
                    main: {
                        intensity: 1.2
                    },
                    ambient: {
                        intensity: 0.3
                    }
                }
            },
            series: [{
                type: 'bar3D',
                data: data.map(function(item) {
                    return {
                        value: [item[1], item[0], item[2]]
                    }
                }),
                shading: 'color',

                label: {
                    show: false,
                    textStyle: {
                        fontSize: 16,
                        borderWidth: 1
                    }
                },

                itemStyle: {
                    opacity: 0.4
                },

                emphasis: {
                    label: {
                        textStyle: {
                            fontSize: 20,
                            color: '#900'
                        }
                    },
                    itemStyle: {
                        color: '#900'
                    }
                }
            }]
        }
        var myChart = echarts.init(document.getElementById('image1'));
        myChart.on('click', function(param){
            data = param.data.value;
            var yyy = year[data[0]], ccc = conf[data[1]];
            window.open("./stat_graph_list.php?affiliationid="+"<?php echo $_GET['affiliationid']; ?>"+"&year="+yyy+"&conferencename="+ccc);
        });
        myChart.setOption(option);
            </script>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 panel panel-default" style="height:700px;font-size:25px;margin-top:0px;" id="image2">
        <script>
        //myChart.showLoading();
            var webkitDep = <?php echo file_get_contents("./json/affiliation_graph_2/$id.json"); ?>;
            if(webkitDep.links.length == 0){
                document.getElementById("image2").parentNode.removeChild(document.getElementById("image2"));
            }
               // myChart.hideLoading();
                var myChart = echarts.init(document.getElementById('image2'));
                myChart.on('click', function(param){
                    if(param.data.category == 1){
                        window.open("./search.php?field=PaperID&value=" + param.data.name);
                    }else if(param.data.category == 0){
                        window.open("./search.php?filed=AuthorName&value=" + param.data.name);
                    }
                });
                //console.log(webkitDep);
                option = {
                    title:{
                        text: "Graph : Author ---- Paper ---- Author"
                    },
                    legend: {
                        data: ["author","paper"]
                    },
                    series: [{
                        type: 'graph',
                        layout: 'force',
                        animation: false,
                        label: {
                            normal: {
                                position: 'right',
                                formatter: '{b}'
                            }
                        },
                        draggable: true,
                        data: webkitDep.nodes.map(function (node, idx) {
                            //console.log(node);
                           // node.id = idx;
                            return node;
                        }),
                        categories: webkitDep.categories,
                        force: {
                            // initLayout: 'circular'
                            // repulsion: 20,
                            edgeLength: 10,
                            repulsion: 35,
                            gravity: 0.4
                        },
                        edges: webkitDep.links,
                        roam: true
                    }]
                };
                myChart.setOption(option);
        </script>
        </div>
    </div>
</div>
</body>

</html>