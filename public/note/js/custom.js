document.addEventListener("DOMContentLoaded", () => {

	/* IIFE TO SET target="_blank" every external link inside markdown posts */
	( async () => {
		let postContent = await document.getElementById('post-inner-content')
		if (postContent !== null) {
			let innerLinks = await postContent.querySelectorAll('a')

			innerLinks.forEach(link => {
			
				let isExternalLink = link.origin !== 'https://www.info-alberghi.com'

				if ( isExternalLink ) {
					link.target = '_blank'
					link.rel = 'noopener noreferrer'
				}
			})
		}
	})()
	

})