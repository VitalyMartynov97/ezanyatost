document.addEventListener("DOMContentLoaded", function() {
			//first
			getEvents();
			document.querySelector(".form-control").addEventListener("change", function() {
					getEvents(this.value);
			});
			function getEvents(value) {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.querySelector(".card-deck").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "ajax/get_events.php?value=" + value, true);
				xmlhttp.send();	
			}
			//nav
			var bars = document.querySelector(".header-bars");
			bars.addEventListener("click", function() {
				var menu = document.querySelector(".header-navigation");
				menu.classList.toggle("nav-open");
			});

			var top_button = document.querySelector(".footer-gototop a");
			top_button.addEventListener("click", function() {
				'use strict';
				window.scrollTo({
					behavior: 'smooth',
					top: 0
				});
			});
		});