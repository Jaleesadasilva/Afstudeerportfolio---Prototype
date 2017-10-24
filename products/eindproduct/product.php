<!DOCTYPE html>
<html lang="en">

<?php
	$pageTitle = 'Eindproduct - Het product';

	include('../../config.php');
	include('../../includes/head.php');
	include('../../includes/resources.php');
?>

	<body>
		<main>

			<section>
					<?php include ('../../includes/menu.php'); ?>
      </section>

			<section class="sidebar">
			 <?php include('../../includes/sidebar.php') ?>
		 </section>

			<section>
				<div class="container">
					<article class="content">
						<div class="text">
							<h1>Prototype</h1>
							<ul class="post-taglist">
								<li><a href="<?php echo $serverOmgeving . 'vaardigheden/realisatie/'?>" rel="tag">Realisatie</a></li>
							</ul>

							<p>Om de werking van de dynamische mail te laten zien heb ik een prototype ontwikkeld. Via een formulier kun je je naam, de fase waarin je je bevindt, de bestemming en het land van herkomst invullen. Op basis van die gegevens wordt er een gepersonaliseerde mail gegenereerd. Per fase zal uitgelegd worden welke data er gebruikt wordt.</p>

							<p>Omdat het een prototype betreft wordt de data uit een JSON bestand opgehaald. Hierin zit niet alle data van de Withlocals API.</p>
						</div>


					</article>

					<form id="emailSettings">
						<ul class="form-style-1">
						    <li><label for="name">Voornaam</label><input type="text" name="name" class="field-long" style="text-transform:capitalize"/></li>
								<li>
										<label>Fase</label>
										<select name="fase" class="field-select">
											<option value="">-- Kies een fase --</option>
											<option value="Before">Vóór de boeking</option>
											<option value="Booking">Van boeking tot trip</option>
										</select>
								</li>
						    <li>
						        <label>Bestemming</label>
						        <select name="bestemming" class="field-select">
											<option value="">-- Kies een bestemming --</option>
							        <option value="Rome">Rome</option>
							        <option value="Amsterdam">Amsterdam</option>
							        <option value="Bangkok">Bangkok</option>
						        </select>
						    </li>
								<li>
						        <label>Land van herkomst</label>
						        <select name="herkomst" class="field-select">
											<option value="">-- Kies een land van herkomst --</option>
							        <option value="NL">Nederland</option>
							        <option value="USA">Amerika</option>
							        <option value="TL">Thailand</option>
						        </select>
						    </li>
						    <li>
						        <button>Genereer email</button>
						    </li>
						</ul>
					</form>

					<article class="content" style="margin-top:20px;">
						<div class="text">
							<h2 id="faseHeader"></h2>
							<p id="explanation1"></p>
							<p id="explanation2"></p>

						</div>
					</article>



			</section>
		</main>

		<div id="mailContainer">
			<article id="mailContent"></article>
		</div>



		<footer class="footer">
			<?php include('../../includes/footer.php'); ?>
		</footer>

		<script type='text/javascript'>

			$(function(){

				$("body").on("click", "#emailSettings button", function(){
					event.preventDefault();
					var data = $("#emailSettings").serializeArray();
					var userName = $("#emailSettings input[name='name']").val();
					var destination = $("#emailSettings select[name='bestemming']").val();
					var fase = $("#emailSettings select[name='fase']").val();
					var origin = $("#emailSettings select[name='herkomst']").val();

						$.ajax({
							type: "POST",
						  url: "process.php",
						  data: data.concat({
								name: "generateMail",
								value: true
							})
						})
						.done(function(response){
							$("#mailContent").html(response);

							if (userName.length === 0) {
								$(".welcomeMsg").text("Hi fellow traveler,");
							}
							else {
								$(".welcomeMsg").text("Hi " + userName + ",");
							}

							$(".destination").text(destination);

							titles = [];
							descriptions = [];
							urls = [];
							imgs = [];

							hostnames = [];
							hostPhoneNumbers = [];
							hostOneliners = [];
							hostPhotos = [];
							hostUrls = [];

							countries = [];
							cities = [];
							areas = [];

							review_counts = [];
							review_writers = [];
							review_writer_photos = [];
							review_post_titles = [];
							review_posts = [];

							switch (fase) {
								case "Before":
									$("#faseHeader").text("Vóór de boeking");
									$("#explanation1").text("Tijdens de fase voor de boeking heeft Withlocals nog maar weinig informatie van de gebruiker. De informatie die Withlocals heeft is wat er tijdens het registeren is ingevuld (Voornaam, achternaam en emailadres). In deze oriënterende fase is het belangrijk om te laten zien wat de USP's van Withlocals zijn en de verschillende landen waar Withlocals zich in bevindt.");
									$("#explanation2").text("De onderstaande mail wordt verstuurd aan de hand van het gedrag van de gebruiker. De gebuiker heeft een experience bekeken, maar geen boeking gemaakt. De eerste experience die in de mail staat, is de experience die de gebruiker bekeken heeft. Daaronder krijgt je enkele suggesties in dezelfde stad en enkele reviews over de host.")
								break;
								case "Booking":
									$("#faseHeader").text("Van boeking tot trip");
									$("#explanation1").text("De fase van boeking tot trip is een voorbereidende fase. De gebruiker heeft een experiene geboekt. Door deze boeking weet Withlocals meer van de gebruiker. Withlocals weet waar de gebruiker vandaan komt, wanneer de trip plaats gaat vinden, met hoeveel mensen, welke experience en welke host. In deze fase zijn upselling en app-downloads erg belangrijk.");
									$("#explanation2").text("De onderstaande mail ontvangt de gebruiker enkele dagen voor de geboekte experience, met een aantal andere suggesties in de omgeving. Deze suggesties zijn o.a. gebaseerd op waar de gebruiker vandaan komt. Iemand uit Amerika komt vaak niet voor enkele dagen naar Rome, die gaat meer van Europa zien. Geef ze suggesties in de omliggende steden/landen. Daarnaast is het niet meer van groot belang om de USP's van Withlocals te laten zien. Ze hebben immers al geboekt. ")
								break;
								default:

							}

							switch (true) {
								case (destination == "Rome") && (origin == "NL"):

									$(".herkomst").text("Other great things to do in Rome");
									$(".mailFooter").load("mails/footerEU.php");

									$.getJSON('json/rome.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[1]);
										$(".expTitle5").text(titles[2]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[0]);
										$(".expCountry3").text(countries[0]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[0]);
										$(".expCity3").text(cities[0]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[1] + " )");
										$(".expReviewCount5").text("( " + review_counts[2] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[1]);
										$(".expPhoto5").attr('src', imgs[2]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr2 + "...");
										$(".expDescr5").text(shortDescr3 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Rome") && (origin == "USA"):

									$(".herkomst").text("Other great things to do near Rome");
									$(".mailFooter").load("mails/footerEU.php");

									$.getJSON('json/rome.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[3]);
										$(".expTitle5").text(titles[4]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[3]);
										$(".expCountry3").text(countries[4]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[3]);
										$(".expCity3").text(cities[4]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[3] + " )");
										$(".expReviewCount5").text("( " + review_counts[4] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[3]);
										$(".expPhoto5").attr('src', imgs[4]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Rome") && (origin == "TL"):

									$(".herkomst").text("Other great things to do near Rome");
									$(".mailFooter").load("mails/footerEU.php");

									$.getJSON('json/rome.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[3]);
										$(".expTitle5").text(titles[4]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[3]);
										$(".expCountry3").text(countries[4]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[3]);
										$(".expCity3").text(cities[4]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[3] + " )");
										$(".expReviewCount5").text("( " + review_counts[4] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[3]);
										$(".expPhoto5").attr('src', imgs[4]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Amsterdam") && (origin == "NL"):

									$(".herkomst").text("Other great things to do in Amsterdam");
									$(".mailFooter").load("mails/footerEU.php");

									$.getJSON('json/amsterdam.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[1];
										var desc5 = descriptions[2];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[1]);
										$(".expTitle5").text(titles[2]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[0]);
										$(".expCountry3").text(countries[0]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[0]);
										$(".expCity3").text(cities[0]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[1] + " )");
										$(".expReviewCount5").text("( " + review_counts[2] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[1]);
										$(".expPhoto5").attr('src', imgs[2]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Amsterdam") && (origin == "USA"):

									$(".herkomst").text("Other great things to do near Amsterdam");
									$(".mailFooter").load("mails/footerEU.php");

									$.getJSON('json/amsterdam.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[3]);
										$(".expTitle5").text(titles[4]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[3]);
										$(".expCountry3").text(countries[4]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[3]);
										$(".expCity3").text(cities[4]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[3] + " )");
										$(".expReviewCount5").text("( " + review_counts[4] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[3]);
										$(".expPhoto5").attr('src', imgs[4]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Amsterdam") && (origin == "TL"):

									$(".herkomst").text("Other great things to do near Amsterdam");
									$(".mailFooter").load("mails/footerEU.php");

									$.getJSON('json/amsterdam.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[3]);
										$(".expTitle5").text(titles[4]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[3]);
										$(".expCountry3").text(countries[4]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[3]);
										$(".expCity3").text(cities[4]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[3] + " )");
										$(".expReviewCount5").text("( " + review_counts[4] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[3]);
										$(".expPhoto5").attr('src', imgs[4]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Bangkok") && (origin == "NL"):

									$(".herkomst").text("Other great things to do near Bangkok");
									$(".mailFooter").load("mails/footerASIA.php");

									$.getJSON('json/bangkok.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[3]);
										$(".expTitle5").text(titles[4]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[3]);
										$(".expCountry3").text(countries[4]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[3]);
										$(".expCity3").text(cities[4]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[3] + " )");
										$(".expReviewCount5").text("( " + review_counts[4] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[3]);
										$(".expPhoto5").attr('src', imgs[4]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Bangkok") && (origin == "USA"):

									$(".herkomst").text("Other great things to do near Bangkok");
									$(".mailFooter").load("mails/footerASIA.php");

									$.getJSON('json/bangkok.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[3];
										var desc5 = descriptions[4];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[3]);
										$(".expTitle5").text(titles[4]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[3]);
										$(".expCountry3").text(countries[4]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[3]);
										$(".expCity3").text(cities[4]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[3] + " )");
										$(".expReviewCount5").text("( " + review_counts[4] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[3]);
										$(".expPhoto5").attr('src', imgs[4]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;

								case (destination == "Bangkok") && (origin == "TL"):

									$(".herkomst").text("Other great things to do in Bangkok");
									$(".mailFooter").load("mails/footerASIA.php");

									$.getJSON('json/bangkok.json', function(json){
										$.each(json, function(index, experience){
											// Experience information
											titles.push(experience.title);
											descriptions.push(experience.description);
											urls.push(experience.url);
											imgs.push(experience.img);
											countries.push(experience.country);
											cities.push(experience.city);
											areas.push(experience.area);

											// Host information
											hostnames.push(experience.hostname);
											hostPhoneNumbers.push(experience.phoneNumber);
											hostOneliners.push(experience.hostOneliner);
											hostPhotos.push(experience.hostPhoto);
											hostUrls.push(experience.hostUrl);

											// Review information
											review_counts.push(experience.reviews_count);
											review_writers.push(experience.review_writer);
											review_writer_photos.push(experience.review_writer_photo);
											review_post_titles.push(experience.review_post_title);
											review_posts.push(experience.review_post);
										});
									})

									.done(function(){
										var desc1 = descriptions[0];
										var desc2 = descriptions[1];
										var desc3 = descriptions[2];
										var desc4 = descriptions[1];
										var desc5 = descriptions[2];

										var shortDescr1 = desc1.substring(0,300);
										var shortDescr2 = desc2.substring(0,120);
										var shortDescr3 = desc3.substring(0,120);
										var shortDescr4 = desc4.substring(0,150);
										var shortDescr5 = desc5.substring(0,150);

										$(".check").attr('href','https://withlocals.com/experiences/' + countries[0] + "/" + cities[0]);

										// Experience information
										$(".expTitle1").text(titles[0]);
										$(".expTitle2").text(titles[1]);
										$(".expTitle3").text(titles[2]);
										$(".expTitle4").text(titles[1]);
										$(".expTitle5").text(titles[2]);

										$(".expCountry1").text(countries[0]);
										$(".expCountry2").text(countries[0]);
										$(".expCountry3").text(countries[0]);

										$(".expCity1").text(cities[0]);
										$(".expCity2").text(cities[0]);
										$(".expCity3").text(cities[0]);

										$(".expReviewCount1").text("( " + review_counts[0] + " )");
										$(".expReviewCount2").text("( " + review_counts[1] + " )");
										$(".expReviewCount3").text("( " + review_counts[2] + " )");
										$(".expReviewCount4").text("( " + review_counts[1] + " )");
										$(".expReviewCount5").text("( " + review_counts[2] + " )");

										$(".expPhoto1").attr('src', imgs[0]);
										$(".expPhoto2").attr('src', imgs[1]);
										$(".expPhoto3").attr('src', imgs[2]);
										$(".expPhoto4").attr('src', imgs[1]);
										$(".expPhoto5").attr('src', imgs[2]);

										$(".expDescr1").text(shortDescr1 + "...");
										$(".expDescr2").text(shortDescr2 + "...");
										$(".expDescr3").text(shortDescr3 + "...");
										$(".expDescr4").text(shortDescr4 + "...");
										$(".expDescr5").text(shortDescr5 + "...");

										$(".readMore1").attr('href','https://withlocals.com/experience/' + urls[0]);
										$(".readMore2").attr('href','https://withlocals.com/experience/' + urls[1]);
										$(".readMore3").attr('href','https://withlocals.com/experience/' + urls[2]);
										$(".readMore4").attr('href','https://withlocals.com/experiences/' + countries[3] + "/" + cities[3]);
										$(".readMore5").attr('href','https://withlocals.com/experiences/' + countries[4] + "/" + cities[4]);

										// Host information
										$(".expHost1").text(hostnames[0]);

										$(".hostNr1").text(hostPhoneNumbers[0]);

										$(".hostBio1").text(hostOneliners[0]);

										$(".hostPhoto1").attr('src', hostPhotos[0]);

										$(".hostUrl1").attr('href','https://withlocals.com/host/' + hostUrls[0]);

										$(".hostPhone").text(hostPhoneNumbers[0]);

										// Review information
										$(".reviewPhoto1").attr('src', review_writer_photos[0]);

										$(".reviewTitle1").text(review_post_titles[0]);

										$(".reviewText1").text(review_posts[0]);

										$(".reviewerName1").text(review_writers[0]);
									});
								break;
							}

						})
						.fail(function(xhr, ajaxoptions, thrownError){
							alert(thrownError);
						});

				});

			});
		</script>
	</body>

</html>
