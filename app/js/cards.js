function initializeEvents()
{
	/*
	let cargar = document.getElementById("btnCards");
	cargar.addEventListener("click",  async ()=>{
		const r = await getWorkers()
		const arr = r['lista_trabajadores'] //asi accedo al array dentro del json object, por su nombre en corchetes a lo php
		console.log(arr)
		// Iterate over the data array
		for (let i = 0; i < arr.length; i++) {
			// Create a new card element for each object in the array
			const card = document.createElement("div");
			card.classList.add("card");
			card.classList.add("card-mine");
			card.innerHTML = `
				<div class="card-body">
					<h4 class="card-title card-mine-title">Username: ${arr[i].username}</h4>
					<h5 class="card-text card-mine-text">Rol: ${arr[i].rol}</h5>
					<p class="card-text card-mine-text">Es Admin: ${arr[i].isAdmin}</p>
					<p class="card-text card-mine-text">Rol: ${arr[i].fecha_inicio}</p>
					<p class="card-text card-mine-text">Rol: ${arr[i].fecha_fin}</p>
				</div>
			`;
	
			// Add the card element to the card container
			const cardContainer = document.getElementById("cardContainer");
			cardContainer.appendChild(card);
		}
	});
	*/
}
export{
	initializeEvents
}







