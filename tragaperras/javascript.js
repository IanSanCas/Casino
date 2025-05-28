
/**
 * Setup
 */
const debugEl = document.getElementById('debug'),
			// Mapping of indexes to icons: start from banana in middle of initial position and then upwards
			iconMap = ["banana", "seven", "cherry", "plum", "orange", "bell", "bar", "lemon", "melon"],
			// Width of the icons
			icon_width = 79,	
			// Height of one icon in the strip
			icon_height = 79,	
			// Number of icons in the strip
			num_icons = 9,	
			// Max-speed in ms for animating one icon down
			time_per_icon = 100,
			// Holds icon indexes
			indexes = [0, 0, 0];


/** 
 * Roll one reel
 */
const roll = (reel, offset = 0) => {
	// Minimum of 2 + the reel offset rounds
	const delta = (offset + 2) * num_icons + Math.round(Math.random() * num_icons); 
	
	// Return promise so we can wait for all reels to finish
	return new Promise((resolve, reject) => {
		
		const style = getComputedStyle(reel),
					// Current background position
					backgroundPositionY = parseFloat(style["background-position-y"]),
					// Target background position
					targetBackgroundPositionY = backgroundPositionY + delta * icon_height,
					// Normalized background position, for reset
					normTargetBackgroundPositionY = targetBackgroundPositionY%(num_icons * icon_height);
		
		// Delay animation with timeout, for some reason a delay in the animation property causes stutter
		setTimeout(() => { 
			// Set transition properties ==> https://cubic-bezier.com/#.41,-0.01,.63,1.09
			reel.style.transition = `background-position-y ${(8 + 1 * delta) * time_per_icon}ms cubic-bezier(.41,-0.01,.63,1.09)`;
			// Set background position
			reel.style.backgroundPositionY = `${backgroundPositionY + delta * icon_height}px`;
		}, offset * 150);
			
		// After animation
		setTimeout(() => {
            const finalIndex = delta % num_icons;
            const alignedPosition = finalIndex * icon_height;
        
            reel.style.transition = `none`;
            reel.style.backgroundPositionY = `${alignedPosition}px`;
        
            resolve(finalIndex); // ← solo devolvemos el índice final
        }, (8 + 1 * delta) * time_per_icon + offset * 150);
        
	});
};


/**
 * Roll all reels, when promise resolves roll again
 */
function rollAll() {
	debugEl.textContent = 'rolling...';

	const reelsList = document.querySelectorAll('.slots > .reel');

	return Promise
		.all([...reelsList].map((reel, i) => roll(reel, i)))
		.then((finalIndexes) => {
			// Guardamos los nuevos índices globalmente
			finalIndexes.forEach((val, i) => indexes[i] = val);

			// Mostramos en debug los nombres de los símbolos
			debugEl.textContent = indexes.map(i => iconMap[i]).join(' - ');

			// Comprobamos si hay combinación ganadora
			if (indexes[0] === indexes[1] || indexes[1] === indexes[2]) {
				const winCls = (indexes[0] === indexes[2]) ? "win2" : "win1";
				const slots = document.querySelector(".slots");
				slots.classList.add(winCls);
				setTimeout(() => slots.classList.remove(winCls), 2000);
			}
		});
}

  
  const reelsList = document.querySelectorAll('.slots > .reel');
reelsList.forEach((reel, i) => reel.dataset.index = i);


document.getElementById('spinButton').addEventListener('click', () => {
    rollAll();

    const spinButton = document.getElementById('spinButton');

    spinButton.addEventListener('click', () => {
      // Desactiva el botón mientras gira
      spinButton.disabled = true;
      spinButton.textContent = "Girando...";
    
      // Llama a rollAll y reactiva el botón al finalizar
      rollAll().then(() => {
        spinButton.disabled = false;
        spinButton.textContent = "Girar";
      });
    });
    
  });