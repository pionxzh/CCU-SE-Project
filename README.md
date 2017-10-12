# CCU-SE-Project

### International Talents Matching System



## Installing

### Laravel Framework 5.3+
* 裝[**Laravel**](https://laravel.tw/docs/5.3/installation)
* **Windows**可以考慮裝[**Laragon**](https://laragon.org/)比較方便

### Clone the entire project
``` bash
$ git clone https://github.com/pionxzh/CCU-SE-Project.git
```


## Run Laravel Server
* **Laragon**請自行google如何使用

Run the Laravel Server:
``` bash
# move to project dictionary
$ cd ~/CCU-SE-Project/iTalents

# update laravel vendor
$ composer install

# generate laravel secrurity key
$ php artisan key:generate


# start server
$ php artisan serve
```

* Open `localhost:8000` in your browser

* See if you can see the image below
![](https://i.imgur.com/O7SpvV7.png)

* If yes, congratulations, if not..., ask other for help
