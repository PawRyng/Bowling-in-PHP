# Bowling PHP

## Opis

Aplikacja służy do obliczania punktów w grze w kręgle. Użytkownik podaje ilość strąconych kręgli, a aplikacja wyliczy liczbę punktów na podstawie zasad gry w kręgle. Aplikacja może być uruchamiana za pomocą XAMPP lub kontenera Dockerowego, w zależności od preferencji użytkownika.

## Technologie
- **Język**: PHP
-  **Konteneryzacja**: Docker, Docker Compose
  
## Wymagania

-  Interpreter PHP
-   Docker (opcjonalnie)
-   Docker Compose (opcjonalnie)

## Instalacja

1.  **Sklonuj repozytorium**
    
   ```bash
git clone https://github.com/PawRyng/Bowling-in-PHP.git; 
```
2. **Uruchom aplikację**
Użyj Docker Compose do zbudowania i uruchomienia aplikacji:
```bash
php app.php
```
3. **Docker** (opcjonalnie)
	W katalogu gdzie znajduje się docker-compose.yml uruchamiamy komendę
	```bash
	docker-compose up 
	```
	Podłączamy się pod terminal kontenera
	```bash
	docker exec -it bowling bash
	```
	Następnie uruchamiamy aplikację poleceniem z punktu 1
	```bash
		php app.php
	```
## Struktura katalogów
```bash
  ├── docker-compose.yml 
  ├── src 
      ├── Controller 
      │      └── game.php       
      ├── Tests 
      │      └── tests.php 
      └── app.php
```



## Kontakt

Jeśli masz pytania lub uwagi, skontaktuj się z nami pod adresem p_ryng@wp.pl.
