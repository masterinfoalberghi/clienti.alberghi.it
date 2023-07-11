

- se sono loggato in admin NON devo vedere la cache sul frontedn

- problemi:

    - admin e front sono 2 siti separati
    - NON POSSO CONDIVIDERE LA SESSIONE tra questi 2 applicazioni laravel perché la sessione su database NON FUNZIONA

  

  Soluzione:

  > quando mi loggo in admin
  
  scrivo un recordo nella tabella custom_session con user_id, hashed_key, created_at

  scrivo un cookie custom_session con hashed_id
 

> quando navigo e verifico la cache

if(cache_enabled && Utility::session_cookie_check())


Utility::session_cookie_check() 

verifica se esiste il cookie custom_session



> quando faccio logout cancello il cookie
