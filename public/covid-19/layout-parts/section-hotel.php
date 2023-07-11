<style>

section.section-hotel {
	padding: 0 0 !important;
	background: #185278;
	color: whitesmoke;
}

section.section-hotel header {
  text-align: center;
	margin: 2.3em auto 2em;
}
section.section-hotel header h2 {
	color: whitesmoke;
}

.img-hotel-wrapper {
	display: none;
}

.box-text {
  display: flex;
  flex-flow: column wrap;
}
ul.griglia-lista {
	align-self: center;

	padding: 0;
	margin: 0 0 2em;

	list-style-type: none;
}

ul.griglia-lista > li {
	margin-bottom: .8em;

	text-decoration: none;
}
ul.griglia-lista > li > a {
	font-family: 'Roboto light', sans-serif, sans-serif;
  font-size: 20px;
  line-height: 1.5;
}

@media ( min-width: 500px ) {

	.griglia {
		display: grid;
		grid: 1fr / 1fr 2fr;
		height: 450px;
	}

	.img-hotel-wrapper {
		display: block;
		width: 42rem;
		height: 100%;
		width: 100%;
	}
	.img-hotel {
		height: 100%;
		width: 100%;
		object-fit: cover;
		clip-path: polygon(0% 0%, 75% 0%, 100% 50%, 75% 100%, 0% 100%);
	}

	ul.griglia-lista {
		column-count: 2;
		column-gap: 4em;
	}
}
</style>


<section class="section-hotel">
		<div class="griglia">
			<div class="img-hotel-wrapper">
				<img class="img-hotel" src="img/section-hotel-cabine-pastello.jpg">
			</div>
			<div class="box-text">
				<header>
					<h2>Scegli il tuo Hotel in Riviera Romagnola</h2>
				</header>
				<ul class="griglia-lista">
					<li><a class="alternate-link" href="https://www.info-alberghi.com/rimini.php" target="_blank">Rimini</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/riccione.php" target="_blank">Riccione</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/cattolica.php" target="_blank">Cattolica</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/misano-adriatico.php" target="_blank">Misano Adriatico</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/gabicce-mare.php" target="_blank">Gabicce</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/bellaria.php" target="_blank">Bellaria Igea Marina</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/cesenatico.php" target="_blank">Cesenatico</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/cervia.php" target="_blank">Cervia</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/milano-marittima.php" target="_blank">Milano Marittima</a></li>
					<li><a class="alternate-link" href="https://www.info-alberghi.com/lidi-ravennati.php" target="_blank">Lidi di Ravenna</a></li>
				</ul>
			</div>
		 </div>
</section>

