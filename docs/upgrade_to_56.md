

1) backup di master/ (sul server) e infoalberghiThirdEye/ (locale)

1.1) Aggiornare alpha con le nuove mail

2) copio tutto il contenuto di alpha/ locale su master/ remoto (tranne .env e static/)

3) composer update da master sul server (per aggiornare la vendor di Laravel)


ATTENZIONE:

--> la cartella infoalbergi.git rimane indietro !!  


4) copio il contenuto di alpha/ in infoalberghiThirdEye/ e faccio push sul server
