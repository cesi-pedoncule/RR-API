# Cube CESI : Ressource Relationnel API


## Installation :

### With Docker :  
Run docker images :  

```docker-compose up -d```   

Load database :  
```make init```  

Open the API doc with your web browser at : http://localhost:8000/api  

## Testing  

### With Docker :  
```make tests```

###  Without Docker in Linux :  
```php bin/console d:d:c --env=test && php bin/console d:m:m --env=test --no-interaction && php bin/console d:f:l --env=test --no-interaction && php bin/phpunit```

###  Without Docker in Windows :  
```php bin/console d:d:c --env=test; php bin/console d:m:m --env=test --no-interaction; php bin/console d:f:l --env=test --no-interaction; php bin/phpunit```