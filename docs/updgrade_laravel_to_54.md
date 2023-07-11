**alpha**



1. copiare la directory "alphaThirdEye" sul server dentro una copia di infoalberghi 
2. togliere la direcory .git 
3. diventa un progetto normale e da qui creo il "bare repository"


4. rinomino infoalberghi.git e dopo
	git clone --bare alphaThirdEye/ infoalberghi.git 


dallla mia macchina clono il nuovo bare repository

git clone info-alberghi-te:/var/www/html/infoalberghi.git e viene creata la folder infoalberghi 


==========================================================================================================================



1. copiare alphaThirdEye sulla infoalberghiThirdEye locale

2. fare il push sul server in modo che la origin prenda le modifiche

3. lanciare sul server "composer update" per aggiornare la vendor alla versione corrente


==========================================================================================================================