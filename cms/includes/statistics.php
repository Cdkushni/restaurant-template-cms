<?php

if ($section == "Website Statistics") {
	$popular_pages_limit = $_POST['popular_pages_limit'];
	$regions_limit = $_POST['regions_limit'];
	$traffic_limit = $_POST['traffic_limit'];
	$keyword_limit = $_POST['keyword_limit'];
	$visit_length_limit = $_POST['visit_length_limit'];
	
	if ($popular_pages_limit == "") { $popular_pages_limit = 10; }
	if ($regions_limit == "") { $regions_limit = 10; }
	if ($traffic_limit == "") { $traffic_limit = 10; }
	if ($keyword_limit == "") { $keyword_limit = 10; }
	if ($visit_length_limit == "") { $visit_length_limit = 10; }
	?>
    <script>
	$(function() {
		var dates = $( "#start_date, #end_date" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 3,
			dateFormat:"yy-mm-dd",
			onSelect: function( selectedDate ) {
				var option = this.id == "start_date" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
		
		
	});
	
	
	</script>
    <?php
	
	
	define('ga_email',$GA_email);
	define('ga_password',$GA_password);
	define('ga_profile_id',$GA_profileID);
	
	require '../cms/includes/gapi/gapi.class.php';
	
	try{
		$ga = new gapi(ga_email,ga_password);
	}catch (Exception $e){
		alert('<p>There was a problem accessing the google analytics account. Please double check the credentials in the global website settings page of the CMS.</p>',false);	
	}
	
	/* Get Account Information */
	$ga->requestAccountData();
	foreach($ga->getResults() as $result) {
	  //echo $result . ' (' . $result->getProfileId() . ")<br />";
	}
	
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	if($start_date==null) {
      $start_date=date('Y-m-d',strtotime('1 month ago'));
    }
    if($end_date==null)  {
      $end_date=date('Y-m-d');
    }
	
	
	echo "<form action='' method='post' name='edit' enctype='multipart/form-data'>";
	
	echo "<table cellpadding='3' cellspacing='0' border='0' style='width:100%;' class='removepadding'>";
	
	echo "<tr>";
	echo "<td class='overview_top' style='width:150px;'><p><b>Date Range:</b></p></td>";
	echo "<td class='overview_top'><input type='text' name='start_date' id='start_date' class='input' style='width:100px; margin-right: 10px;' value='" .$start_date ."' readonly /><input type='text' name='end_date' id='end_date' class='input' style='width:100px; margin-right: 10px;' value='" .$end_date ."' readonly /><input type='submit' name='submit' class='submit' value='Go' /></td>";
	echo "</tr>";	
	
	echo "<input type='hidden' name='popular_pages_limit' value='" .$popular_pages_limit ."'>";
	echo "<input type='hidden' name='regions_limit' value='" .$regions_limit ."'>";
	echo "<input type='hidden' name='traffic_limit' value='" .$traffic_limit ."'>";
	echo "<input type='hidden' name='keyword_limit' value='" .$keyword_limit ."'>";
	
	echo "<input type='hidden' name='section' value='" .$section ."'>";
	echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
	
	echo "</table>";
	echo "</form>";
	
	
	
	
	
	
	$start_date_values = explode("-", $start_date);
	$end_date_values = explode("-", $end_date);
	$d1 = GregorianToJD($start_date_values[1], $start_date_values[2], $start_date_values[0]);
	$d2 = GregorianToJD($end_date_values[1], $end_date_values[2], $end_date_values[0]);
	$total_days = ($d2-$d1)+1;
	$count = 0;
	
	
	
	$ga->requestReportData(ga_profile_id,array("visitorType"),array('pageviews','visits', 'pageviewsPerVisit', 'avgTimeOnSite', 'percentNewVisits', 'visitBounceRate'),'-visits',"", $start_date, $end_date);
		
	echo "<table cellpadding='0' cellspacing='0' border='0' style='width:100%;' class='removepadding' id='stats_overview'>";
	echo "<tr>";
	echo "<td><p><span class='alternate' style='color:#CCC;'>Visits <sup title='<span>Visits</span><p>The total number of visits over the selected dimension. A visit consists of a single-user session.</p>'>?</sup></span><br />" .$ga->getVisits() ."</p></td>";
	echo "<td><p><span class='alternate' style='color:#CCC;'>Pages/Visit <sup title='<span>Pages/Visit</span><p>The average number of pages viewed during a visit to your site. Repeated views of a single page are counted.</p>'>?</sup></span><br />" .round($ga->getPageviewsPerVisit(), 2) ."</p></td>";
	echo "<td><p><span class='alternate' style='color:#CCC;'>Avg. Time On Site <sup title='<span>Avg. Time On Site</span><p>The average amount of time visitors spent viewing this page or a set of pages.</p>'>?</sup></span><br />" .sec2hms($ga->getAvgTimeOnSite(), true) ."</p></td>";
	echo "<td><p><span class='alternate' style='color:#CCC;'>% New Visits <sup title='<span>% New Visits</span><p>The percentage of visits by people who had never visited your site before.</p>'>?</sup></span><br />" .round($ga->getPercentNewVisits(), 2) ."%</p></td>";
	echo "<td class='last'><p><span class='alternate'>Bounce Rate <sup title='<span>Bounce Rate</span><p>The percentage of single-page visits (i.e., visits in which the person left your site from the first page).</p>'>?</sup></span><br />" .round($ga->getVisitBounceRate(), 2) ."%</p></td>";
	echo "</tr>";
	echo "</table>";
	
	
	
	
	
	?>
<!-- CHART FOR WEBSITE STATISTICS -->
<script>
var chart;
var chart2; // Traffic Sources
var chart3; // Keyword Sources
var chart4; // Popular Pages
var chart5; // Visit Length
$(document).ready(function() {
   chart = new Highcharts.Chart({
      chart: {
         renderTo: 'chart_visits',
         defaultSeriesType: 'line',
         marginRight: 130,
         marginBottom: 50
      },
	  credits: {
		enabled: false
	  },
	  colors: ['#3C6C46', '#B5A27A'],
      title: {
         text: 'Website Statistics',
         x: -20, //center
		 style: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'16px'
		 }
      },
      subtitle: {
         text: 'Source: Google Analytics',
         x: -20, //center
		 style: {
			 color:'#999',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px'
		 }
      },
      xAxis: {
         categories: [<?php
		 $count = 0;
		 $pageviews = "";
		 $visits = "";
		 
		 for ($i=$d1; $i<=$d2; $i++) {
			$date = JDToGregorian($i);
			$date_values = explode("/", $date);
			if ($date_values[0] < 10) { $date_values[0] = "0" .$date_values[0]; }
			if ($date_values[1] < 10) { $date_values[1] = "0" .$date_values[1]; }
			$date_analytics = $date_values[2] ."-" .$date_values[0] ."-" .$date_values[1];
			
			if ($count != 0) { echo ", "; }
			echo "'" .$date_values[0] ."/" .$date_values[1] ."'";
			
			
			$ga->requestReportData(ga_profile_id,array("browser","browserVersion"),array('pageviews','visits'),'-visits',"", $date_analytics, $date_analytics);
			$pageviews .= $ga->getPageviews() .", ";
			$visits .= $ga->getVisits() .", ";
			
			$count++;
		 }
		 
		 
		 ?>]
      },
	  yAxis: [{ // Primary yAxis
         title: {
            text: 'Visits',
            style: {
               color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
			 fontWeight:'bold'
            }
         },
         labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#B5A27A'
            }
         }
      }, { // Secondary yAxis
		 labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#3C6C46'
            }
         },
         title: {
            text: 'Pageviews',
            style: {
             	color:'#3C6C46',
				 fontFamily:'Helvetica, Arial, sans-serif',
				 fontSize:'12px',
				 fontWeight:'bold'
            }
         },
         opposite: true
      }],
      tooltip: {
         formatter: function() {
                   return '<b>'+ this.series.name +'</b><br/>' + this.x +': '+ this.y;
         }
      },
      legend: {
		 layout: 'vertical',
         align: 'right',
         verticalAlign: 'top',
         x: -180,
         y: 50,
		 floating: true,
		 backgroundColor:'#FFF',
		 borderWidth: 1,
		 borderColor: '#666',
		 shadow: true,
		 itemStyle: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHoverStyle: {
			 color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHiddenStyle: {
			 color:'#CCC',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 }
      },
      series: [{
         name: 'Pageviews',
		 type: 'column',
		 yAxis: 1,
         data: [<?php echo substr($pageviews, 0, -2); ?>]
      }, {
         name: 'Visits',
         data: [<?php echo substr($visits, 0, -2); ?>]
      }]
   });
   
   
   
   
   // Traffic Sources
   chart2 = new Highcharts.Chart({
      chart: {
         renderTo: 'trafficSources',
         defaultSeriesType: 'line',
         marginRight: 50,
         marginBottom: 50
      },
	  credits: {
		enabled: false
	  },
	  colors: ['#3C6C46', '#B5A27A'],
      title: {
         text: 'Traffic Sources',
         x: -20, //center
		 style: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px'
		 }
      },
      xAxis: {
         categories: [<?php
		 $count = 0;
		 $visits = "";
		 $pagespervisit = "";
		 
		
		$ga->requestReportData(ga_profile_id,array("source"),array('pageviews', 'visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $traffic_limit);
		
		foreach($ga->getResults() as $result){
			$visits .= $result->getVisits() .", ";
			$pagespervisit .= round($result->getPageviewsPerVisit(), 2) .", ";
			
			if ($count != 0) { echo ", "; }
			echo "'" .($count+1) ."'";
			$count++;
		}
		
		 ?>]
      },
      yAxis: [{ // Primary yAxis
         title: {
            text: 'Visits',
            style: {
               color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
			 fontWeight:'bold'
            }
         },
         labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#B5A27A'
            }
         }
      }, { // Secondary yAxis
		 labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#3C6C46'
            }
         },
         title: {
            text: 'Pages/Visit',
            style: {
             	color:'#3C6C46',
				 fontFamily:'Helvetica, Arial, sans-serif',
				 fontSize:'12px',
				 fontWeight:'bold'
            }
         },
         opposite: true
      }],
      tooltip: {
         formatter: function() {
                   return '<b>'+ this.series.name +'</b><br/>' + this.x +': '+ this.y;
         }
      },
      legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'top',
         x: -100,
         y: 20,
		 floating: true,
		 backgroundColor: '#FFFFFF',
		 borderWidth: 1,
		 borderColor: '#666',
		 shadow: true,
		 itemStyle: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHoverStyle: {
			 color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHiddenStyle: {
			 color:'#CCC',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 }
      },
	  series: [{
         name: 'Pages/Visit',
		 type: 'column',
		 yAxis: 1,
         data: [<?php echo substr($pagespervisit, 0, -2); ?>]
      }, {
         name: 'Visits',
         data: [<?php echo substr($visits, 0, -2); ?>]
      }]
   });
   
   
   
   
   
   // Keyword Sources
   chart3 = new Highcharts.Chart({
      chart: {
         renderTo: 'keywordSources',
         defaultSeriesType: 'line',
         marginRight: 50,
         marginBottom: 50
      },
	  credits: {
		enabled: false
	  },
	  colors: ['#3C6C46', '#B5A27A'],
      title: {
         text: 'Keyword Sources',
         x: -20, //center
		 style: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px'
		 }
      },
      xAxis: {
         categories: [<?php
		 $count = 0;
		 $visits = "";
		 $pagespervisit = "";
		 
		
		$ga->requestReportData(ga_profile_id,array("keyword"),array('pageviews', 'visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $keyword_limit);
		
		foreach($ga->getResults() as $result){
			$visits .= $result->getVisits() .", ";
			$pagespervisit .= round($result->getPageviewsPerVisit(), 2) .", ";
			
			if ($count != 0) { echo ", "; }
			echo "'" .($count+1) ."'";
			$count++;
		}
		
		 ?>]
      },
      yAxis: [{ // Primary yAxis
         title: {
            text: 'Visits',
            style: {
               color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
			 fontWeight:'bold'
            }
         },
         labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#B5A27A'
            }
         }
      }, { // Secondary yAxis
		 labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#3C6C46'
            }
         },
         title: {
            text: 'Pages/Visit',
            style: {
             	color:'#3C6C46',
				 fontFamily:'Helvetica, Arial, sans-serif',
				 fontSize:'12px',
				 fontWeight:'bold'
            }
         },
         opposite: true
      }],
      tooltip: {
         formatter: function() {
                   return '<b>'+ this.series.name +'</b><br/>' + this.x +': '+ this.y;
         }
      },
      legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'top',
         x: -100,
         y: 20,
		 floating: true,
		 backgroundColor: '#FFFFFF',
		 borderWidth: 1,
		 borderColor: '#666',
		 shadow: true,
		 itemStyle: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHoverStyle: {
			 color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHiddenStyle: {
			 color:'#CCC',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 }
      },
	  series: [{
         name: 'Pages/Visit',
		 type: 'column',
		 yAxis: 1,
         data: [<?php echo substr($pagespervisit, 0, -2); ?>]
      }, {
         name: 'Visits',
         data: [<?php echo substr($visits, 0, -2); ?>]
      }]
   });
   
   
   
   
   
   
   // Popular Pages
   chart4 = new Highcharts.Chart({
      chart: {
         renderTo: 'popularPages',
         defaultSeriesType: 'line',
         marginRight: 50,
         marginBottom: 50
      },
	  credits: {
		enabled: false
	  },
	  colors: ['#3C6C46', '#B5A27A'],
      title: {
         text: 'Popular Pages',
         x: -20, //center
		 style: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px'
		 }
      },
      xAxis: {
         categories: [<?php
		 $count = 0;
		 $visits = "";
		 $pagespervisit = "";
		 
		
		$ga->requestReportData(ga_profile_id,array("pagePath"),array('pageviews', 'visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $popular_pages_limit);
		
		foreach($ga->getResults() as $result){
			$visits .= $result->getVisits() .", ";
			$pagespervisit .= round($result->getPageviewsPerVisit(), 2) .", ";
			
			if ($count != 0) { echo ", "; }
			echo "'" .($count+1) ."'";
			$count++;
		}
		
		 ?>]
      },
      yAxis: [{ // Primary yAxis
         title: {
            text: 'Visits',
            style: {
               color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
			 fontWeight:'bold'
            }
         },
         labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#B5A27A'
            }
         }
      }, { // Secondary yAxis
		 labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#3C6C46'
            }
         },
         title: {
            text: 'Pages/Visit',
            style: {
             	color:'#3C6C46',
				 fontFamily:'Helvetica, Arial, sans-serif',
				 fontSize:'12px',
				 fontWeight:'bold'
            }
         },
         opposite: true
      }],
      tooltip: {
         formatter: function() {
                   return '<b>'+ this.series.name +'</b><br/>' + this.x +': '+ this.y;
         }
      },
      legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'top',
         x: -100,
         y: 20,
		 floating: true,
		 backgroundColor: '#FFFFFF',
		 borderWidth: 1,
		 borderColor: '#666',
		 shadow: true,
		 itemStyle: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHoverStyle: {
			 color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHiddenStyle: {
			 color:'#CCC',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 }
      },
	  series: [{
         name: 'Pages/Visit',
		 type: 'column',
		 yAxis: 1,
         data: [<?php echo substr($pagespervisit, 0, -2); ?>]
      }, {
         name: 'Visits',
         data: [<?php echo substr($visits, 0, -2); ?>]
      }]
   });
   
   
   
   
   // Visit Length
   chart5 = new Highcharts.Chart({
      chart: {
         renderTo: 'visitLength',
         defaultSeriesType: 'line',
         marginRight: 50,
         marginBottom: 50
      },
	  credits: {
		enabled: false
	  },
	  colors: ['#3C6C46', '#B5A27A'],
      title: {
         text: 'Length Of Visit',
         x: -20, //center
		 style: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px'
		 }
      },
      xAxis: {
         categories: [1,2,3,4,5,6,7]
      },
	  yAxis: [{ // Primary yAxis
         title: {
            text: 'Visits',
            style: {
               color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
			 fontWeight:'bold'
            }
         },
         labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#B5A27A'
            }
         }
      }, { // Secondary yAxis
		 labels: {
            formatter: function() {
               return this.value +'';
            },
            style: {
               color: '#3C6C46'
            }
         },
         title: {
            text: 'Pages/Visit',
            style: {
             	color:'#3C6C46',
				 fontFamily:'Helvetica, Arial, sans-serif',
				 fontSize:'12px',
				 fontWeight:'bold'
            }
         },
         opposite: true
      }],
      tooltip: {
         formatter: function() {
                   return '<b>'+ this.series.name +'</b><br/>' + this.x +': '+ this.y;
         }
      },
      legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'top',
         x: -100,
         y: 20,
		 floating: true,
		 backgroundColor: '#FFFFFF',
		 borderWidth: 1,
		 borderColor: '#666',
		 shadow: true,
		 itemStyle: {
			 color:'#3C6C46',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHoverStyle: {
			 color:'#B5A27A',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 },
		 itemHiddenStyle: {
			 color:'#CCC',
			 fontFamily:'Helvetica, Arial, sans-serif',
			 fontSize:'12px',
		 }
      },
	  series: [{
         name: 'Pages/Visit',
		 type: 'column',
		 yAxis: 1,
         data: [<?php
		 
		$visitLength_visits = array(0,0,0,0,0,0,0);
		$visitLength_pages = array(0,0,0,0,0,0,0);
		$ga->requestReportData(ga_profile_id,array("visitLength"),array('visits', 'pageviewsPerVisit'),'-visits',"", $start_date, $end_date);
		
		foreach($ga->getResults() as $result){
			$cnt = strval($result);

			if ($cnt >= 0 && $cnt <= 10) {
				$visitLength_visits[0] = $visitLength_visits[0] + $result->getVisits();
				$visitLength_pages[0] = $visitLength_pages[0] + round($result->getPageviewsPerVisit(), 2);
				
			} else if ($cnt > 10 && $cnt <= 30) {
				$visitLength_visits[1] = $visitLength_visits[1] + $result->getVisits();
				$visitLength_pages[1] = $visitLength_pages[1] + round($result->getPageviewsPerVisit(), 2);
			
			} else if ($cnt > 30 && $cnt <= 60) {
				$visitLength_visits[2] = $visitLength_visits[2] + $result->getVisits();
				$visitLength_pages[2] = $visitLength_pages[2] + round($result->getPageviewsPerVisit(), 2);
			
			} else if ($cnt > 60 && $cnt <= 180) {
				$visitLength_visits[3] = $visitLength_visits[3] + $result->getVisits();
				$visitLength_pages[3] = $visitLength_pages[3] + round($result->getPageviewsPerVisit(), 2);
			
			} else if ($cnt > 180 && $cnt <= 600) {
				$visitLength_visits[4] = $visitLength_visits[4] + $result->getVisits();
				$visitLength_pages[4] = $visitLength_pages[4] + round($result->getPageviewsPerVisit(), 2);
			
			} else if ($cnt > 600 && $cnt <= 1800) {
				$visitLength_visits[5] = $visitLength_visits[5] + $result->getVisits();
				$visitLength_pages[5] = $visitLength_pages[5] + round($result->getPageviewsPerVisit(), 2);
				
			} else {
				$visitLength_visits[6] = $visitLength_visits[6] + $result->getVisits();
				$visitLength_pages[6] = $visitLength_pages[6] + round($result->getPageviewsPerVisit(), 2);
			}
			
		}
		echo $visitLength_pages[0] .", " .$visitLength_pages[1] .", " .$visitLength_pages[2] .", " .$visitLength_pages[3] .", " .$visitLength_pages[4] .", " .$visitLength_pages[5] .", " .$visitLength_pages[6];
		 
		 ?>]}, {
         name: 'Visits',
         data: [<?php echo $visitLength_visits[0] .", " .$visitLength_visits[1] .", " .$visitLength_visits[2] .", " .$visitLength_visits[3] .", " .$visitLength_visits[4] .", " .$visitLength_visits[5] .", " .$visitLength_visits[6]; ?>]
      }]
   });
   
});
</script>
<!-- ENDS -->
<div id="chart_visits" style="display:block; width: 100%; height: 400px;"></div>



<div id="traffic_sources">
    <div class='overview_top' style='width:100%; display:block;'>
        <div class='f_right'>
        <form action='' method='post'><select name='traffic_limit' class='select' style='width:50px;' onChange='javascript:submit();'>
        <option<?php if ($traffic_limit == 10) { echo " selected"; } ?>>10</option>
        <option<?php if ($traffic_limit == 25) { echo " selected"; } ?>>25</option>
        <option<?php if ($traffic_limit == 50) { echo " selected"; } ?>>50</option>
        <option<?php if ($traffic_limit == 75) { echo " selected"; } ?>>75</option>
        <option<?php if ($traffic_limit == 100) { echo " selected"; } ?>>100</option>
        <option<?php if ($traffic_limit == 9999) { echo " selected"; } ?> value='9999'>All</option>
        </select>
        
        <?php
        echo "<input type='hidden' name='start_date' value='" .$start_date ."'>";
        echo "<input type='hidden' name='end_date' value='" .$end_date ."'>";
        echo "<input type='hidden' name='regions_limit' value='" .$regions_limit ."'>";
        echo "<input type='hidden' name='popular_pages_limit' value='" .$popular_pages_limit ."'>";
		echo "<input type='hidden' name='keyword_limit' value='" .$keyword_limit ."'>";
		echo "<input type='hidden' name='visit_length_limit' value='" .$visit_length_limit ."'>";
        
        echo "<input type='hidden' name='section' value='" .$section ."'>";
        echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
        ?>
        </form>
        </div>
        <p><b>Traffic Sources</b></p>
    </div>
    <div id="trafficSources" style="display:block; width: 100%; height:300px;"></div>
    
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
        	<td style="width: 25px; border-bottom: 1px solid #666;">&nbsp;</td>
            <td style="border-bottom: 1px solid #666;"><p><b>Referrer</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666'><p><b>Visits</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666'><p><b>Pages/Visit</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666'><p><b>Avg. Time</p></td>
        </tr>
        <!-- TRAFFIC SOURCES -->
        <?php
        
        $ga->requestReportData(ga_profile_id,array("source"),array('pageviews', 'visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $traffic_limit);
        $count=1;
        foreach($ga->getResults() as $result){
			if ($count % 2) {
				echo "<tr class='row1'>";
			} else {
				echo "<tr class='row2'>";
			}
			echo "<td><p><b>" .$count ."</b></p></td>";
            echo "<td><p>" .$result ."</p></td>";
            echo "<td><p>" .$result->getVisits() ."</p></td>";
            echo "<td><p>" .round($result->getPageviewsPerVisit(), 2) ."</p></td>";
			echo "<td><p>" .sec2hms($result->getAvgTimeOnSite(), true) ."</p></td>";
            echo "</tr>";
			$count++;
        }
        
        ?>
        <!-- ENDS -->
    </table>
</div>







<div id="keyword_sources">
    <div class='overview_top' style='width:100%; display:block;'>
        <div class='f_right'>
        <form action='' method='post'><select name='keyword_limit' class='select' style='width:50px;' onChange='javascript:submit();'>
        <option<?php if ($keyword_limit == 10) { echo " selected"; } ?>>10</option>
        <option<?php if ($keyword_limit == 25) { echo " selected"; } ?>>25</option>
        <option<?php if ($keyword_limit == 50) { echo " selected"; } ?>>50</option>
        <option<?php if ($keyword_limit == 75) { echo " selected"; } ?>>75</option>
        <option<?php if ($keyword_limit == 100) { echo " selected"; } ?>>100</option>
        <option<?php if ($keyword_limit == 9999) { echo " selected"; } ?> value='9999'>All</option>
        </select>
        
        <?php
        echo "<input type='hidden' name='start_date' value='" .$start_date ."'>";
        echo "<input type='hidden' name='end_date' value='" .$end_date ."'>";
        echo "<input type='hidden' name='regions_limit' value='" .$regions_limit ."'>";
        echo "<input type='hidden' name='popular_pages_limit' value='" .$popular_pages_limit ."'>";
		echo "<input type='hidden' name='traffic_limit' value='" .$traffic_limit ."'>";
		echo "<input type='hidden' name='visit_length_limit' value='" .$visit_length_limit ."'>";
        
        echo "<input type='hidden' name='section' value='" .$section ."'>";
        echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
        ?>
        </form>
        </div>
        <p><b>Keyword Sources</b></p>
    </div>
    <div id="keywordSources" style="display:block; width: 100%; height:300px;"></div>
    
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
        	<td style="width: 25px; border-bottom: 1px solid #666;">&nbsp;</td>
            <td style="border-bottom: 1px solid #666;"><p><b>Keyword</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Visits</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Pages/Visit</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Avg. Time</p></td>
        </tr>
        <!-- POPULAR PAGES -->
        <?php
        
        $ga->requestReportData(ga_profile_id,array("keyword"),array('pageviews','visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $keyword_limit);
        $count=1;
        foreach($ga->getResults() as $result){
            if ($count % 2) {
				echo "<tr class='row1'>";
			} else {
				echo "<tr class='row2'>";
			}
			echo "<td><p><b>" .$count ."</b></p></td>";
            echo "<td><p>" .$result ."</a></td>";
            echo "<td><p>" .$result->getVisits() ."</p></td>";
            echo "<td><p>" .round($result->getPageviewsPerVisit(), 2) ."</p></td>";
			echo "<td><p>" .sec2hms($result->getAvgTimeOnSite(), true) ."</p></td>";
            echo "</tr>";
			$count++;
        }
        
        ?>
        <!-- ENDS -->
    </table>
</div>








<div id="popular_pages">
    <div class='overview_top' style='width:100%; display:block;'>
        <div class='f_right'>
        <form action='' method='post'><select name='popular_pages_limit' class='select' style='width:50px;' onChange='javascript:submit();'>
        <option<?php if ($popular_pages_limit == 10) { echo " selected"; } ?>>10</option>
        <option<?php if ($popular_pages_limit == 25) { echo " selected"; } ?>>25</option>
        <option<?php if ($popular_pages_limit == 50) { echo " selected"; } ?>>50</option>
        <option<?php if ($popular_pages_limit == 75) { echo " selected"; } ?>>75</option>
        <option<?php if ($popular_pages_limit == 100) { echo " selected"; } ?>>100</option>
        <option<?php if ($popular_pages_limit == 9999) { echo " selected"; } ?> value='9999'>All</option>
        </select>
        
        <?php
        echo "<input type='hidden' name='start_date' value='" .$start_date ."'>";
        echo "<input type='hidden' name='end_date' value='" .$end_date ."'>";
        echo "<input type='hidden' name='regions_limit' value='" .$regions_limit ."'>";
        echo "<input type='hidden' name='traffic_limit' value='" .$traffic_limit ."'>";
        echo "<input type='hidden' name='keyword_limit' value='" .$keyword_limit ."'>";
        echo "<input type='hidden' name='visit_length_limit' value='" .$visit_length_limit ."'>";
        
        echo "<input type='hidden' name='section' value='" .$section ."'>";
        echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
        ?>
        </form>
        </div>
        <p><b>Popular Pages</b></p>
    </div>
    <div id="popularPages" style="display:block; width: 100%; height:300px;"></div>
    
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="width: 25px; border-bottom: 1px solid #666;">&nbsp;</td>
            <td style="border-bottom: 1px solid #666;"><p><b>Page</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Visits</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Pages/Visit</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Avg. Time</p></td>
        </tr>
        <!-- POPULAR PAGES -->
        <?php
        
        $ga->requestReportData(ga_profile_id,array("pagePath"),array('pageviews','visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $popular_pages_limit);
        $count=1;
        foreach($ga->getResults() as $result){
            if ($count % 2) {
				echo "<tr class='row1'>";
			} else {
				echo "<tr class='row2'>";
			}
            echo "<td><p><b>" .$count ."</b></p></td>";
            echo "<td><p><a href='" .$result ."' target='_blank'>" .$result ."</a></p></td>";
            echo "<td><p>" .$result->getVisits() ."</p></td>";
            echo "<td><p>" .round($result->getPageviewsPerVisit(), 2) ."</p></td>";
            echo "<td><p>" .sec2hms($result->getAvgTimeOnSite(), true) ."</p></td>";
            echo "</tr>";
            $count++;
        }
        ?>
        <!-- ENDS -->
    </table>
    
</div>





    
    
<div id="visit_length">
    <div class='overview_top' style='width:100%; display:block;'>
        <p><b>Length Of Visit</b></p>
    </div>
    <div id="visitLength" style="display:block; width: 100%; height:300px;"></div>
    
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="width: 25px; border-bottom: 1px solid #666;">&nbsp;</td>
            <td style="border-bottom: 1px solid #666;"><p><b>Duration Of Visit</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Visits</b></p></td>
            <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Pages/Visit</b></p></td>
        </tr>
        <!-- VISIT LENGTH -->
        <tr class='row1'>
        	<td><p><b>1</b></p></td>
            <td><p>0 - 10 secs</p></td>
            <td><p><?php echo $visitLength_visits[0]; ?></p></td>
            <td><p><?php echo $visitLength_pages[0]; ?></p></td>
        </tr>
        <tr class='row2'>
        	<td><p><b>2</b></p></td>
            <td><p>11 - 30 secs</p></td>
            <td><p><?php echo $visitLength_visits[1]; ?></p></td>
            <td><p><?php echo $visitLength_pages[1]; ?></p></td>
        </tr>
        <tr class='row1'>
        	<td><p><b>3</b></p></td>
            <td><p>31 - 60 secs</p></td>
            <td><p><?php echo $visitLength_visits[2]; ?></p></td>
            <td><p><?php echo $visitLength_pages[2]; ?></p></td>
        </tr>
        <tr class='row2'>
        	<td><p><b>4</b></p></td>
            <td><p>61 - 180 secs</p></td>
            <td><p><?php echo $visitLength_visits[3]; ?></p></td>
            <td><p><?php echo $visitLength_pages[3]; ?></p></td>
        </tr>
        <tr class='row1'>
        	<td><p><b>5</b></p></td>
            <td><p>181 - 600 secs</p></td>
            <td><p><?php echo $visitLength_visits[4]; ?></p></td>
            <td><p><?php echo $visitLength_pages[4]; ?></p></td>
        </tr>
        <tr class='row2'>
        	<td><p><b>6</b></p></td>
            <td><p>601 - 1800 secs</p></td>
            <td><p><?php echo $visitLength_visits[5]; ?></p></td>
            <td><p><?php echo $visitLength_pages[5]; ?></p></td>
        </tr>
        <tr class='row1'>
        	<td><p><b>7</b></p></td>
            <td><p>1801+ secs</p></td>
            <td><p><?php echo $visitLength_visits[6]; ?></p></td>
            <td><p><?php echo $visitLength_pages[6]; ?></p></td>
        </tr>
        <!-- ENDS -->
    </table>
</div>
    
    
    
    
    
    
    
    
    <!-- CHART FOR REGIONS -->
<?php
// create locations array
$locations = array();
$ga->requestReportData(ga_profile_id,array("country"),array('pageviews','visits', 'pageviewsPerVisit', 'avgTimeOnSite'),'-visits',"", $start_date, $end_date, 1, $regions_limit);
foreach($ga->getResults() as $result){		
	if ($locations[strval($result)][1] == null) {
		$locations[strval($result)][1] = 0;
	}
	
	$locations[strval($result)][0] = strval($result);
	
	$cnt = $locations[strval($result)][1];
	if ($cnt > 0) {
		$locations[strval($result)][1] = ($cnt + $result->getVisits());
	} else {
		$locations[strval($result)][1] = $result->getVisits();
	}
	
}
?>
<script type='text/javascript' src='http://www.google.com/jsapi'></script>
<script type='text/javascript'>
google.load('visualization', '1', {'packages': ['geomap']});
google.setOnLoadCallback(drawMap);

function drawMap() {
  var data = new google.visualization.DataTable();
  
  data.addRows(<?php echo count($locations); ?>);
  data.addColumn('string', 'Country');
  data.addColumn('number', 'Popularity');
  
<?php
$count=0;
foreach($locations as $loc) {
?>
data.setValue(<?php echo $count; ?>, 0, "<?php echo $loc[0]; ?>");
data.setValue(<?php echo $count; ?>, 1, <?php echo $loc[1]; ?>);
<?php
$count++;
}
?>
  

  var options = {};
  options['dataMode'] = 'regions';
  options['width'] = '100%';
  options['height'] = '100%';

  var container = document.getElementById('chart_regions');
  var geomap = new google.visualization.GeoMap(container);
  geomap.draw(data, options);
  
};
</script>
<!-- ENDS -->



    
    
    
    <div id="data_regions">
    	<div class='overview_top' style='width:100%; display:block;'>
        	<div class='f_right'>
            <form action='' method='post'><select name='regions_limit' class='select' style='width:50px;' onChange='javascript:submit();'>
            <option<?php if ($regions_limit == 10) { echo " selected"; } ?>>10</option>
            <option<?php if ($regions_limit == 25) { echo " selected"; } ?>>25</option>
            <option<?php if ($regions_limit == 50) { echo " selected"; } ?>>50</option>
            <option<?php if ($regions_limit == 75) { echo " selected"; } ?>>75</option>
            <option<?php if ($regions_limit == 100) { echo " selected"; } ?>>100</option>
            <option<?php if ($regions_limit == 9999) { echo " selected"; } ?> value='9999'>All</option>
            </select>
            
            <?php
            echo "<input type='hidden' name='start_date' value='" .$start_date ."'>";
            echo "<input type='hidden' name='end_date' value='" .$end_date ."'>";
            echo "<input type='hidden' name='popular_pages_limit' value='" .$popular_pages_limit ."'>";
			echo "<input type='hidden' name='traffic_limit' value='" .$traffic_limit ."'>";
			echo "<input type='hidden' name='keyword_limit' value='" .$keyword_limit ."'>";
			echo "<input type='hidden' name='visit_length_limit' value='" .$visit_length_limit ."'>";
            
            echo "<input type='hidden' name='section' value='" .$section ."'>";
            echo "<input type='hidden' name='xssid' value='" .$_COOKIE['xssid']. "'/>";
            ?>
            </form>
            </div>
            <p><b>Map Overlay</b></p>
        </div>
        
        <div id="chart_regions" style="width:100%; height:400px; margin-bottom: 20px; border-bottom: 1px solid #333;"></div>
        
        
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="border-bottom: 1px solid #666;";><p><b>Country</b></p></td>
                <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Visits</b></p></td>
                <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Pages/Visit</b></p></td>
                <td style='width:100px; border-bottom: 1px solid #666;'><p><b>Avg. Time</p></td>
            </tr>
            <?php
			$count=0;
			foreach($ga->getResults() as $result){
				if ($count % 2) {
					echo "<tr class='row2'>";
				} else {
					echo "<tr class='row1'>";
				}
				echo "<td><p>" .$result ."</p></td>";
				echo "<td><p>" .$result->getVisits() ."</p></td>";
				echo "<td><p>" .round($result->getPageviewsPerVisit(), 2) ."</p></td>";
				echo "<td><p>" .sec2hms($result->getAvgTimeOnSite(), true) ."</p></td>";
				echo "</tr>";
				$count++;
				if ($count > $regions_limit) { break; }
			}
			?>
        </table>
    </div>
    
    
    
	<?php
	
}
								
?>
