il componente che si usa è il jquery fancybox https://fancyapps.com/fancybox/3/


Nel file scheda.js ho 2 entry point per aprire il fancybox (click immagine grande oppure click sulla thumb)


se clicco nell'iimagine grande
> $(".image_gallery").click(function () {

viene creato un array di elementi "elements" da passare all'init del fancybox

	$.fancybox.open(
				elements, 
				{
          'loop' : false,
          arrows: true,
					buttons: [
						"close"
					],
					thumbs : {
						autoStart   : true,                  // Display thumbnails on opening
						hideOnClose : true,                   // Hide thumbnail grid when closing animation starts
						parentEl    : '.fancybox-container',  // Container is injected into this element
						axis        : 'x'                     // Vertical (y) or horizontal (x) scrolling
					}
				}).jumpTo(indexFoto-1);


questo array viene creato facendo un loop sulle immagini con classe image_gallery cioè le immagini grandi: siccome le immagini grandi solno delle slick hanno delle immagini che non si vedono ma vengono contate in questo loop!!

In modo empirico bisogna non considerare le prime 2 immagini (a sx ci sono 2 immagini che non si vedono) e bisogna togliere le ultime 2

$(".image_gallery").each(function() {
        
        // salto i ultimi 2 (se c'è il video ne devo saltare 1 SOLA)
        if(count>2){ 


})  // fine loop


 // tolgo gli ultimi 2
elements.splice(-2,2);



__ATTENZIONE__ se ho il video quando richiamo il fancybox ne devo saltare 1 solo

// salto i ultimi 2 (se c'è il video ne devo saltare 1 SOLA)
        if(count>1){ 

e nella gallery thumb devo fare

jumpTo(indexFotoThumb)

e non 

jumpTo(indexFoto-1);