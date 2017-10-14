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

# install module
$ composer install
$ npm install 

# 安裝gulp(只要安裝一次)
$ npm install -g gulp

****
# 打包用
$ gulp

# 開發中會修改js用
$ gulp watch
****

# start server
$ php artisan serve
```

* 瀏覽器打開 [localhost:8000](localhost:8000)

* 看到下圖代表成功，沒有的話請問別人~
![](https://i.imgur.com/O7SpvV7.png)
