<html>
	<head>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Raleway:100,400' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<title>Dice Data Scrapper</title>
	</head>
	<body>
		
		<div id="wrapper">
			<div id="main-content">
				<div class="inner-box">
					<h1>Dice Data Scraper</h1>
						
							<div class="data-center">
								<h2>Total Records: <span id='counter'></span></h2>
								<span id='download_ready'></span>
								<div id="progressbar"></div>
							</div>
							<script>
							var total_request;
							var counter_request;
							var scrape_request;
							var total=0;
							var time_bomb;
							var file_name;
						
							$(document).ready(function(){
								
								
								
								$('#scrape_data').click(function(event){
									
									if(total_request!=undefined)
										total_request.abort;
									if(counter_request!=undefined){
										counter_request.abort;
									}
									if(scrape_request!=undefined)
										scrape_request.abort;
									if(time_bomb!=undefined)
										window.clearInterval(time_bomb);
									file_name=new Date().getTime();
									
									//var url_data='?'+$('#scrape_form').serialize()+'&country=US';
									var city = $('#city').val();
									if(city!='')
										var url_data ='?text='+$('#text').val()+'&city='+$('#city').val()+', '+$('#state').val()+'&age='+$('#age').val()+'&country=US&state='+$('#state').val();
									else
										var url_data ='?text='+$('#text').val()+'&age='+$('#age').val()+'&country=US&state='+$('#state').val();
									url_data=url_data.replace(' ','+');
									
									//url_data=url_data.replace('%2C',',');
									console.log(url_data);
									total_request=$.ajax({
										url: 'http://service.dice.com/api/rest/jobsearch/v1/simple.json'+url_data+'&pgcnt=1',
										context: document.body
									}).done(function(data){
										total=Number(data.count);
										console.log('total ' + total);
										if(total>0){
											
											if(!$('.data-center').is(":visible"))
												$('.data-center').show('slow');
											$('#download_ready').html('preparing file...please wait');
											if(!$('#progressbar').is(":visible")){
												
												$('#progressbar').show('slow');
												$( "#progressbar" ).progressbar({
													value: 0
												});
												
											}
											
											time_bomb = setInterval(function(){
													counter_request = $.ajax({
														url: 'data_counter.php?file='+file_name,
														context: document.body
													}).done(function(data){
														console.log(data);
														$('#counter').text(data);	
														
														var current=Number(data);
														var percentage = current/total*100;
														console.log('Number: '+ current);
														console.log('Total: '+ total);
														$( "#progressbar" ).progressbar({
															value: percentage
														});
														
													});
													
											},100);
											
											scrape_request = $.ajax({
												url: 'scrapper.php'+url_data+'&file='+file_name,
												context: document.body
											}).done(function(data){
												
												clearInterval(time_bomb);
												var all_count = data.all_data_count;
												var csv_file = 'dice_data_scrape_'+file_name+'.csv'
												$('#counter').text(total);
												$( "#progressbar" ).progressbar({
													value: 100
												});
												$('#progressbar').fadeOut();
												console.log(data.all_data_count);
												
												$('#download_ready').html('Your data is ready. Download <a href="'+csv_file+'">here</a>');
												window.open(csv_file,"_blank");
											});
										}else{
											$('#counter').text('0');
											$('#download_ready').html('There is no data matching your request found');
										}
									});
									
									
									event.preventDefault();
									return false;
								});
								
							});
							
							</script>
						
					<div class='scrub-form'>
						<form action="#" method="get" name="scrape_form" id="scrape_form">
							<table>
								<!--<tr>
									<th>Keywords from Job Title or Job Description</th>
									<td><input name="text" type="text" value=""/></tD>
								</tr>-->
								<tr>
									<th>Keywords</th>
									<td><input name="text" type="text" id='text'/></td>
								</tr>
								<!--<tr>
									<th>Country</th>
									<td>
										<select name="country">
											
											<option value="CA">Canada</option>
											
											<option value="US">United States of America</option>
											
										</select>
									</td>
								</tr>-->
								
								<tr>
									<th>State</th>
									<td>
										<select name='state' id='state'>
											<option value="AL">Alabama</option>
											<option value="AK">Alaska</option>
											<option value="AZ">Arizona</option>
											<option value="AR">Arkansas</option>
											<option value="CA">California</option>
											<option value="CO">Colorado</option>
											<option value="CT">Connecticut</option>
											<option value="DE">Delaware</option>
											<option value="DC">District Of Columbia</option>
											<option value="FL">Florida</option>
											<option value="GA">Georgia</option>
											<option value="HI">Hawaii</option>
											<option value="ID">Idaho</option>
											<option value="IL">Illinois</option>
											<option value="IN">Indiana</option>
											<option value="IA">Iowa</option>
											<option value="KS">Kansas</option>
											<option value="KY">Kentucky</option>
											<option value="LA">Louisiana</option>
											<option value="ME">Maine</option>
											<option value="MD">Maryland</option>
											<option value="MA">Massachusetts</option>
											<option value="MI">Michigan</option>
											<option value="MN">Minnesota</option>
											<option value="MS">Mississippi</option>
											<option value="MO">Missouri</option>
											<option value="MT">Montana</option>
											<option value="NE">Nebraska</option>
											<option value="NV">Nevada</option>
											<option value="NH">New Hampshire</option>
											<option value="NJ">New Jersey</option>
											<option value="NM">New Mexico</option>
											<option value="NY">New York</option>
											<option value="NC">North Carolina</option>
											<option value="ND">North Dakota</option>
											<option value="OH">Ohio</option>
											<option value="OK">Oklahoma</option>
											<option value="OR">Oregon</option>
											<option value="PA">Pennsylvania</option>
											<option value="RI">Rhode Island</option>
											<option value="SC">South Carolina</option>
											<option value="SD">South Dakota</option>
											<option value="TN">Tennessee</option>
											<option value="TX">Texas</option>
											<option value="UT">Utah</option>
											<option value="VT">Vermont</option>
											<option value="VA">Virginia</option>
											<option value="WA">Washington</option>
											<option value="WV">West Virginia</option>
											<option value="WI">Wisconsin</option>
											<option value="WY">Wyoming</option>
										</select>	
									</td>
								<tr>
								
								<tr>
									<th>City (Please leave blank if you want to search the whole state)</th>
									<td><input name="city" type="text" id='city'/></td>
								</tr>
								<tr>
									<th>What is the oldest the job posts can be? (In days and numbers only please)</th>
									<td><input name="age" type="number" scroll value="" id='age'/></td>
								</tr>
							</table>
							<input type="submit" value="Search" id="scrape_data">
						</form>
						
					</div>
				</div>
			</div>
		</div>
	</body>
</html>